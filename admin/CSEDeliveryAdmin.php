<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       brainforce.ru
 * @since      1.0.0
 *
 * @package    CSEDelivery
 * @subpackage CSEDelivery/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CSEDelivery
 * @subpackage CSEDelivery/admin
 * @author     BrainForce <support@brainforce.com>
 */
class CSEDeliveryAdmin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;
	private $pageSlug = "wc_cse";
	private $optionsName = CSEConstants::OPTIONS_NAME;
	private $options = [];
	/**
	 * @var CSEStatisticTableList
	 */
	private $cseStatisticList;
	/**
	 * @var CSEWaybillsTableList
	 */
	private $cseWaybilsList;
	/**
	 * @var CSEDebugTableList
	 */
	private $cseDebugList;
	/**
	 * @var CSEOrdersTableList
	 */
	private $cseOrdersList;
	/**
	 * @var CSEAdminSettings
	 */
	public $settings;
	/**
	 * @var CSEAdminActions
	 */
	public $actions;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->options     = get_option( $this->optionsName );

		$this->settings = new CSEAdminSettings( $this->optionsName );
		$this->actions  = new CSEAdminActions( $this->options );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueueStyles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in CSEDeliveryLoader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The CSEDeliveryLoader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/csedelivery-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/csedelivery-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '_select2', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '_ru_select2', plugin_dir_url( __FILE__ ) . 'js/ru.js', array(
			'jquery',
			$this->plugin_name . '_select2'
		), $this->version, false );

	}

	public function adminMenu() {
		add_menu_page( ' Статистика взаиморасчетов КСЕ', 'Курьер Сервис Экспресс', 'manage_options', $this->pageSlug, null, CSEDELIVERY_ROOT_URL . 'assets/img/logo.png' );
		$statisticHook = add_submenu_page( $this->pageSlug, 'Статистика взаиморасчетов КСЕ', 'Статистика', 'manage_options', $this->pageSlug, array(
			$this,
			'statisticsPage'
		) );
		$ordersHook    = add_submenu_page( $this->pageSlug, "Список заказов", "Список заказов", 'manage_options', $this->pageSlug . "_orders", array(
			$this,
			'ordersPage'
		) );
		$sendOrderHook = add_submenu_page( $this->pageSlug, "Отправка заказа в КСЕ", "Отправка заказа", 'manage_options', $this->pageSlug . "_send_order", array(
			$this,
			'sendOrderPage'
		) );
		add_submenu_page( $this->pageSlug, "Настройки КСЕ", "Настройки", 'manage_options', $this->optionsName, array(
			$this->settings,
			'addOptionPage'
		) );
		$debugHook = add_submenu_page( $this->pageSlug, "Отладка", "Отладка", 'manage_options', $this->pageSlug . '_debug', array(
			$this,
			'debugPage'
		) );

		add_action( 'load-' . $statisticHook, array( $this, 'loadStatisticScreenOptions' ) );
		add_action( 'load-' . $sendOrderHook, array( $this, 'loadSendOrderScreenOptions' ) );
		add_action( 'load-' . $ordersHook, array( $this, 'loadOrdersScreenOptions' ) );
		add_action( 'load-' . $debugHook, array( $this, 'loadDebugScreenOptions' ) );
	}

	public function statisticsPage() {
		if ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) == $this->pageSlug ) {
			$login    = $this->options["wc_cse_login"];
			$password = $this->options["wc_cse_password"];
			if ( empty( $login ) || empty( $password ) ): $data = [];
			else:
				$obKCE    = new CSEOrder( $login, $password );
				$date     = strtotime( "-14 day" );
				$dateFrom = CSEHelper::dateToCseDate( date( 'Y-m-d', $date ) );
				$dateTo   = CSEHelper::dateToCseDate( date( 'Y-m-d' ) );
				$data     = [];
				$data     = $obKCE->GetOrders( $dateFrom, $dateTo );

				$this->cseStatisticList->set_data( $data['orders'] );
				$this->cseStatisticList->prepare_items();

				include __DIR__ . '/partials/kce_statistics.php';
			endif;
		}
	}

	public function sendOrderPage() {
		if ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) == $this->pageSlug . '_send_order' ) {
			$wayBills = CSEHelper::getNoOrderWayBills();

			$times = CSEFunctions::getTimesRange();

			$obKCE = new CSE( $this->options['wc_cse_login'], $this->options['wc_cse_password'] );

			$urgency       = $obKCE->getUrgencies();
			$payer         = $obKCE->getPayerCode();
			$paymentTypes  = $obKCE->getPayMethods();
			$shippingTypes = $obKCE->getShippingMethods();
			$cargoTypes    = $obKCE->getCargoTypes();
			$this->cseWaybilsList->prepare_items();

			include __DIR__ . '/partials/kce_create_cse_order.php';
		}
	}

	public function ordersPage() {
		if ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) == $this->pageSlug . '_orders' ) {
			$this->cseOrdersList->prepare_items();

			include __DIR__ . '/partials/kce_orders.php';
		}
	}

	public function debugPage() {
		if ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) == $this->pageSlug . '_debug' ) {
			$this->cseDebugList->prepare_items();
			?>
            <div class="wrap">
                <h2><?php echo get_admin_page_title() ?></h2>
                <form id="wc_cse_debug_list" method="post">
                    <input type="hidden" name="page" value="<?= sanitize_text_field($_REQUEST['page']) ?>" />
					<?php $this->cseDebugList->display(); ?>
                </form>
            </div>
			<?php
		}
	}

	public function loadStatisticScreenOptions() {
		$arguments = array(
			'label'   => __( 'Колечество данных на странице' ),
			'default' => 5,
			'option'  => 'cse_statistic_per_page'
		);
		add_screen_option( 'per_page', $arguments );
		$this->cseStatisticList = new CSEStatisticTableList();
	}

	public function loadSendOrderScreenOptions() {
		$this->cseWaybilsList = new CSEWaybillsTableList();
	}

	public function loadOrdersScreenOptions() {
		$arguments = array(
			'label'   => __( 'Колечество заказов на странице' ),
			'default' => 5,
			'option'  => 'cse_orders_per_page'
		);
		add_screen_option( 'per_page', $arguments );
		$this->cseOrdersList = new CSEOrdersTableList();
	}

	public function loadDebugScreenOptions() {

		$arguments = array(
			'label'   => __( 'Колечество строк на странице' ),
			'default' => 20,
			'option'  => 'cse_debug_per_page'
		);
		add_screen_option( 'per_page', $arguments );
		$this->cseDebugList = new CSEDebugTableList();
	}

	public function shippingMethod() {
		if ( ! class_exists( 'WCShippingCSE' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/WCShippingCSE.php';
		}
	}

	/**
	 * @param $shipping_methods
	 *
	 * @return mixed
	 */
	public function shippingMethods( $shipping_methods ) {
		$shipping_methods["cse"] = "WCShippingCSE";

		return $shipping_methods;
	}

	public function orderActions( $actions ) {
		add_thickbox();
		$actions['send_new_waybill_to_cse'] = __( "Сформировать накладную КСЕ" );

		return $actions;
	}

	public function adminNotices() {
		$notices = CSEFunctions::getMessages();
		foreach ( $notices as $notice ):
			?>
            <div class="notice notice-<?php echo $notice['type'] ?> is-dismissible">
                <p><?php echo $notice['message'] ?></p>
            </div>
		<?php
		endforeach;
	}

	/**
	 * @param $order WC_Order
	 */
	public function addCustomOrderDataAfterOrderDetail( $order ) {
		global $wpdb;

		$waybill = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME . ' WHERE `order_id` = ' . $order->get_id() . ' ORDER BY number DESC LIMIT 1' );
		if ( $waybill ) {
			echo '<p class="form-field form-field-wide"><strong>' . __( 'Номер накладной КСЭ' ) . ':</strong> ' . $waybill->number . '</p>';
		}
	}

	public function beforeOrderCreate( $orderID ) {
		$order        = wc_get_order( $orderID );
		$billingCity  = $order->get_billing_city();
		$shippingCity = $order->get_shipping_city();

		$order->set_billing_city( CSEHelper::getCityName( $billingCity ) );
		$order->set_shipping_city( CSEHelper::getCityName( $shippingCity ) );
		$order->update_meta_data( '_shipping_city_guid', $shippingCity );
		$order->save();
	}

	public function formattedAddressReplacements( $fields, $args ) {
		if ( $city = CSEHelper::getCityName( $args["city"] ) ) {
			$fields["{city}"] = $city;
		}

		return $fields;
	}

	public function setScreenOption( $status, $option, $value ) {
		switch ( $option ) {
			case 'cse_debug_per_page':
			case 'cse_statistic_per_page':
			case 'cse_orders_per_page':
			    add_filter('set_screen_option_' . $option, function( $status, $option, $value ){
				    return (int) $value;
			    }, 10, 3 );
				return $value;
		}

		return $status;
	}

	public function updateOrdersFromCse() {
		global $wpdb;
		$waybills = $wpdb->get_results( "SELECT number,order_id FROM " . $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME . ' WHERE is_done=0 LIMIT 20', 'ARRAY_A' );

		if ( empty( $waybills ) ) {
			return;
		}

		$options = CSEFunctions::getOptions();

		$obCSE = new CSEOrder( $options['wc_cse_login'], $options['wc_cse_password'] );

		$statuses = $obCSE->getDocumentStatus( CSEOrder::WAYBILL, array_column( $waybills, 'number' ) );

		if ( ! $statuses ) {
			return;
		}

		foreach ( $statuses as $number => $status ) {
			$order       = wc_get_order( $waybills[ $number ] );
			$orderStatus = $options[ 'wc_cse_status_' . $status['GUID'] ] ?? false;
			if ( $orderStatus ) {
				$order->set_status( $orderStatus, '', true );
				$order->save();
				if ( $orderStatus == 'wc-completed' ) {
					$wpdb->update(
						$wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME,
						[ 'is_done' => 1 ],
						[ 'number' => $number ],
						[ '%d' ],
						[ '%s' ]
					);
				}
			}
		}
	}

	public function addCronSchedules( $schedules ) {
		$options               = CSEFunctions::getOptions();
		$schedules['cse_cron'] = [
			'interval' => HOUR_IN_SECONDS,
			'display'  => __( 'Частота обновления из настроек КСЕ' )
		];

		return $schedules;
	}

	public function addMetaBoxes( $postType, $post ) {
		global $wpdb;
		$waybill = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME . ' WHERE `order_id` = ' . $post->ID . ' ORDER BY number DESC LIMIT 1' );
		if ( $waybill && $postType === 'shop_order' ) {
			add_meta_box( 'csedelivery_order_meta_box', 'Курьер Сервис Экспресс', [
				$this,
				'showMetaBox'
			], 'shop_order', 'normal', 'high' );
		}
	}

	/**
	 * Output the metabox.
	 *
	 * @param WP_Post $post
	 */
	public function showMetaBox( $post ) {
		global $order;
		global $wpdb;

		if ( ! is_object( $order ) ) {
			$order = wc_get_order( $post->ID );
		}
		$waybill = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME . ' WHERE `order_id` = ' . $order->get_id() . ' ORDER BY number DESC LIMIT 1' );
		if ( $waybill ) {
			echo $waybill->cse_order_id ? '<p class="form-field form-field-wide"><strong>Номер заказа:</strong> ' . esc_html( $waybill->cse_order_id ) . '</p>' : '';
			echo '<p class="form-field form-field-wide"><strong>Номер накладной:</strong> ' . esc_html( $waybill->number ) . '</p>';
		}
	}
}
