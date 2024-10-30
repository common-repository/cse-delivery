<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       brainforce.ru
 * @since      1.0.0
 *
 * @package    CSEDelivery
 * @subpackage CSEDelivery/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    CSEDelivery
 * @subpackage CSEDelivery/includes
 * @author     BrainForce <support@brainforce.com>
 */
class CSEDelivery {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      CSEDeliveryLoader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $pluginName The string used to uniquely identify this plugin.
	 */
	protected $pluginName;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CSEDELIVERY_VERSION' ) ) {
			$this->version = CSEDELIVERY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->pluginName = 'csedelivery';

		$this->loadDependencies();
		$this->setLocale();
		$this->defineAdminHooks();
		$this->definePublicHooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - CSEDeliveryLoader. Orchestrates the hooks of the plugin.
	 * - CSEDeliveryI18n. Defines internationalization functionality.
	 * - CSEDeliveryAdmin. Defines all hooks for the admin area.
	 * - CSEDeliveryPublic. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function loadDependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/CSEDeliveryLoader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/CSEDeliveryI18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/CSE.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/CSEHelper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/CSEOrder.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tableLists/CSEStatisticTableList.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tableLists/CSEWaybillsTableList.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tableLists/CSEDebugTableList.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tableLists/CSEOrdersTableList.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/CSEDeliveryAdmin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/CSEAdminActions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/CSEAdminSettings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/CSEDeliveryPublic.php';

		$this->loader = new CSEDeliveryLoader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the CSEDeliveryI18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function setLocale() {

		$pluginI18n = new CSEDeliveryI18n();

		$this->loader->addAction( 'plugins_loaded', $pluginI18n, 'loadPluginTextdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function defineAdminHooks() {

		$pluginAdmin = new CSEDeliveryAdmin( $this->getPluginName(), $this->getVersion() );

		$this->loader->addAction( 'admin_enqueue_scripts', $pluginAdmin, 'enqueueStyles' );
		$this->loader->addAction( 'admin_enqueue_scripts', $pluginAdmin, 'enqueueScripts' );
		$this->loader->addAction( 'admin_init', $pluginAdmin->settings, 'optionSettings' );
		$this->loader->addAction( 'admin_menu', $pluginAdmin, 'adminMenu' );

		/* AJAX ACTIONS */
		$this->loader->addAction( 'wp_ajax_get_cities', $pluginAdmin->actions, 'getCities' );
		$this->loader->addAction( 'wp_ajax_nopriv_get_cities', $pluginAdmin->actions, 'getCities' );
		$this->loader->addAction( 'wp_ajax_get_debug', $pluginAdmin->actions, 'getDebugInfo' );
		$this->loader->addAction( 'wp_ajax_get_waybill_create_form', $pluginAdmin->actions, 'getWaybillCreateForm' );
		$this->loader->addAction( 'wp_ajax_send_new_waybill_to_cse', $pluginAdmin->actions, 'sendNewWaybillToCSE' );
		$this->loader->addAction( 'wp_ajax_get_way_bill_pdf', $pluginAdmin->actions, 'getWaybillPdf' );
		/* END AJAX ACTIONS */

		/* POST ACTIONS */
		$this->loader->addAction( 'admin_post_create_cse_order', $pluginAdmin->actions, 'createCSEOrder' );
		/* END POST ACTIONS */

		$this->loader->addAction( 'woocommerce_shipping_init', $pluginAdmin, 'shippingMethod' );
		$this->loader->addFilter( 'woocommerce_shipping_methods', $pluginAdmin, 'shippingMethods', 10 );
		$this->loader->addFilter( 'woocommerce_new_order', $pluginAdmin, 'beforeOrderCreate', 100, 2 );
		$this->loader->addFilter( 'woocommerce_order_actions', $pluginAdmin, 'orderActions', 10, 1 );
		$this->loader->addFilter( 'woocommerce_formatted_address_replacements', $pluginAdmin, 'formattedAddressReplacements', 10, 2 );
		$this->loader->addFilter( 'set-screen-option', $pluginAdmin, 'setScreenOption', 10, 3 );

		$this->loader->addFilter( 'cron_schedules', $pluginAdmin, 'addCronSchedules' );

		$this->loader->addAction( 'woocommerce_admin_order_data_after_order_details', $pluginAdmin, 'addCustomOrderDataAfterOrderDetail', 10, 1 );
		$this->loader->addAction( 'admin_notices', $pluginAdmin, 'adminNotices' );

		$this->loader->addAction( 'update_orders_from_cse', $pluginAdmin, 'updateOrdersFromCse' );

		$this->loader->addAction( 'add_meta_boxes', $pluginAdmin, 'addMetaBoxes', 10, 2 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function definePublicHooks() {

		$pluginPublic = new CSEDeliveryPublic( $this->getPluginName(), $this->getVersion() );

		$this->loader->addAction( 'wp_enqueue_scripts', $pluginPublic, 'enqueueStyles' );
		$this->loader->addAction( 'wp_enqueue_scripts', $pluginPublic, 'enqueueScripts' );
		$this->loader->addFilter( 'woocommerce_shipping_estimate_html', $pluginPublic, 'shippingEstimateHtml', 10, 1 );
		$this->loader->addFilter( 'woocommerce_form_field_hidden', $pluginPublic, 'formFieldHidden', 10, 4 );
		$this->loader->addFilter( 'woocommerce_checkout_update_order_meta', $pluginPublic, 'checkoutUpdateOrderMeta', 10, 1 );

		$this->loader->addAction( 'woocommerce_shipping_show_shipping_calculator', $pluginPublic, 'shippingShowShippingCalculator', 10, 3 );
		$this->loader->addFilter( 'woocommerce_checkout_fields', $pluginPublic, 'checkoutFields', 100 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
		if ( ! wp_next_scheduled( 'update_orders_from_cse' ) ) {
			wp_schedule_event( time(), 'cse_cron', 'update_orders_from_cse' );
		}
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function getPluginName() {
		return $this->pluginName;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    CSEDeliveryLoader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function getLoader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function getVersion() {
		return $this->version;
	}

}
