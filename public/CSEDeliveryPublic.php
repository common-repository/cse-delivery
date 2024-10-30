<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       brainforce.ru
 * @since      1.0.0
 *
 * @package    CSEDelivery
 * @subpackage CSEDelivery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CSEDelivery
 * @subpackage CSEDelivery/public
 * @author     BrainForce <support@brainforce.com>
 */
class CSEDeliveryPublic {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $pluginName The ID of this plugin.
	 */
	private $pluginName;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $pluginName The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $pluginName, $version ) {

		$this->pluginName = $pluginName;
		$this->version    = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->pluginName, plugin_dir_url( __FILE__ ) . 'css/csedelivery-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts() {

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

		if ( is_checkout() ) {
			wp_enqueue_script( $this->pluginName, plugin_dir_url( __FILE__ ) . 'js/csedelivery-public.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'select_woo_ru', plugin_dir_url( __FILE__ ) . 'js/ru.js', array( 'selectWoo' ), $this->version, false );
			wp_localize_script( $this->pluginName, 'wc_cse_ajax',
				array(
					'url'       => admin_url( 'admin-ajax.php' ),
					'wpnonce'   => wp_create_nonce( 'wc_cse_ajax' ),
					'city_guid' => WC()->customer->get_shipping_city() ?? ''
				)
			);
		}
	}

	public function shippingEstimateHtml( $html ) {
		return '';
	}

	public function shippingShowShippingCalculator( $first, $i, $package ) {
		return false;
	}

	public function formFieldHidden( $field, $key, $args, $value ) {
		return '<input type="' . esc_attr( $args['type'] ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( $value ) . '" />';
	}

	public function checkoutUpdateOrderMeta( $orderID ) {
		if ( ! empty( $_POST['cse_pvz'] ) ) {
			update_post_meta( $orderID, '_cse_pvz_guid', sanitize_text_field( $_POST['cse_pvz'] ) );
		}
	}

	public function checkoutFields( $fields ) {

		unset( $fields["billing"]["billing_city"] );
		unset( $fields["billing"]["billing_state"] );
		unset( $fields["billing"]["billing_postcode"] );

		unset( $fields["shipping"]["shipping_city"] );
		unset( $fields["shipping"]["shipping_state"] );
		unset( $fields["shipping"]["shipping_postcode"] );

		$shippingCity                        = WC()->customer->get_shipping_city() ?? '';
		$shippingCityName                    = CSEHelper::getCityName( $shippingCity ) ?? '';
		$fields["billing"]["billing_city"]   = [
			"class"        => [ "form-row-wide", "address-field", "update_totals_on_change" ],
			"label"        => "Населенный пункт",
			"type"         => "select",
			"autocomplete" => "wc_cse_address",
			"placeholder"  => "Введите название населенного пункта",
			"required"     => true,
			"options"      => [ $shippingCity => $shippingCityName ],
			'priority'     => 42
		];
		$fields["shipping"]["shipping_city"] = [
			"class"        => [ "form-row-wide", "address-field", "update_totals_on_change" ],
			"label"        => "Населенный пункт",
			"type"         => "select",
			"autocomplete" => "wc_cse_address",
			"placeholder"  => "Введите название населенного пункта",
			"required"     => true,
			"options"      => [ $shippingCity => $shippingCityName ],
			'priority'     => 42
		];
		$fields['order']['cse_pvz']          = [
			'type' => 'hidden'
		];

		return $fields;
	}
}
