<?php

/**
 * @link              https://cse.ru
 * @since             1.0.2
 * @package           CSEDelivery
 *
 * @wordpress-plugin
 * Plugin Name:       Доставка КСЭ для WooCommerce
 * Plugin URI:        www.cse.ru
 * Description:       Модуль интеграции со службой доставки Курьер Сервис Экспресс: расчет стоимости доставки, формирование накладных и заказов на доставку, отслеживание грузов и получение статистики
 * Version:           1.0.2
 * Author:            CSE
 * Author URI:        https://cse.ru
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cse-delivery
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'CSEDELIVERY_VERSION', '1.0.1' );
define( 'CSEDELIVERY_ROOT_DIR', plugin_dir_path( __FILE__ ) );
define( 'CSEDELIVERY_ROOT_URL', plugin_dir_url( __FILE__ ) );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

if ( ! class_exists( CSEConstants::class ) ) {
	require_once CSEDELIVERY_ROOT_DIR . 'includes/CSEConstants.php';
}
if ( ! class_exists( CSEFunctions::class ) ) {
	require_once CSEDELIVERY_ROOT_DIR . 'includes/CSEFunctions.php';
}
if ( ! class_exists( Csedelivery::class ) ) {
	require_once CSEDELIVERY_ROOT_DIR . 'includes/CSEDelivery.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/CSEDeliveryActivator.php
 */
if ( ! function_exists( 'activateCsedelivery' ) ) {
	function activateCsedelivery() {
		require_once CSEDELIVERY_ROOT_DIR . 'includes/CSEDeliveryActivator.php';
		CSEDeliveryActivator::activate();
	}
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/CSEDeliveryDeactivator.php
 */
if ( ! function_exists( 'deactivateCsedelivery' ) ) {
	function deactivateCsedelivery() {
		require_once CSEDELIVERY_ROOT_DIR . 'includes/CSEDeliveryDeactivator.php';
		CSEDeliveryDeactivator::deactivate();
	}
}

register_activation_hook( __FILE__, 'activateCsedelivery' );
register_deactivation_hook( __FILE__, 'deactivateCsedelivery' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the Csedelivery file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'runCsedelivery' ) ) {
	function runCsedelivery() {
		$plugin = new CSEDelivery();
		$plugin->run();
	}
}

add_filter( 'plugin_action_links', 'csedeliveryPluginActionLinks', 10, 4 );
if ( ! function_exists( 'csedeliveryPluginActionLinks' ) ) {
	function csedeliveryPluginActionLinks( $actions, $pluginFile, $pluginData, $context ) {
		if ( $pluginFile === 'cse-delivery/cse-delivery.php' && ! get_option( CSEConstants::PLUGIN_INSTALLED_OPTION_NAME ) ) {
			global $wpdb;
			$count   = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . CSEConstants::CITIES_TABLE_NAME );
			$percent = intval( $count / CSEConstants::CITY_COUNT_IN_DB * 100 );
			$actions = array_merge( [
				'<div class="cse-delivery-install-progressbar"
				style="
				width: 100%;
			    height: 20px;
			    color: black;
			    text-align: center;
				background: linear-gradient(90deg,  #00a0d2 ' . $percent . '% , transparent ' . $percent . '%);
				">'
				. $percent . '%'
				. '</div>',
				'<span style="color: black">...Производится настройка плагина</span><br>' .
				'<script>' .
				'(function ($){
                    let progress =setInterval(function () {
					    wp.ajax.send(  "csedelivery-install-progress", {
					        success: function (data) {
					            if (typeof data.percent === "number" ){
					                $(".cse-delivery-install-progressbar").text(data.percent + "%");
					                $(".cse-delivery-install-progressbar").css("background", "linear-gradient(90deg,  #00a0d2 " + data.percent + "% , transparent " + data.percent + "%");
					                if (data.percent === 100){
					                    window.location.reload();
					                    clearInterval(progress);
					                }
					            }
					        }
					    });
					}, 1000);
                })(jQuery);' .
				'</script>'
			], $actions );
		}

		return $actions;
	}
}
add_action( 'wp_ajax_csedelivery-install-progress', 'csedeliveryInstallProgressStatus' );
if ( ! function_exists( 'csedeliveryInstallProgressStatus' ) ) {
	function csedeliveryInstallProgressStatus() {
			global $wpdb;
			$count   = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . CSEConstants::CITIES_TABLE_NAME );
			$percent = intval( $count / CSEConstants::CITY_COUNT_IN_DB * 100 );
			wp_send_json_success(["percent" => $percent], 200);
	}
}


if ( get_option( CSEConstants::PLUGIN_INSTALLED_OPTION_NAME, false ) ) {
	runCsedelivery();
} else {
	if ( ! class_exists( 'CSEDbImporter' ) ) {
		require_once CSEDELIVERY_ROOT_DIR . 'assets/CSEDbImporter.php';
	}
	add_action( 'init', [ ( new CSEDbImporter() ), 'run' ] );
}

