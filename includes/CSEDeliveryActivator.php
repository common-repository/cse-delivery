<?php

/**
 * Fired during plugin activation
 *
 * @link       brainforce.ru
 * @since      1.0.0
 *
 * @package    CSEDelivery
 * @subpackage CSEDelivery/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CSEDelivery
 * @subpackage CSEDelivery/includes
 * @author     BrainForce <support@brainforce.com>
 */
class CSEDeliveryActivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			wp_die( 'Установите Woocommerce' );
		}

		global $wpdb;
		$tableName = $wpdb->prefix . CSEConstants::CITIES_TABLE_NAME;
		$query     = "CREATE TABLE IF NOT EXISTS $tableName (
            guid VARCHAR(50) NOT NULL PRIMARY KEY,
            parent_guid VARCHAR(50),
            type INT(2),
            cladr VARCHAR(20),
            alter_code VARCHAR(10),
            priority VARCHAR(10),
            name VARCHAR(255) NOT NULL,
            name_eng VARCHAR(255)
        );";
		$wpdb->query( $query );

		$tableName = $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME;
		$query     = "create table IF NOT EXISTS $tableName
        (
            number       varchar(255) not null primary key,
            order_id     int          null,
            date         varchar(30)  null,
            deliveryFrom   varchar(255) not null,
            weight       float        null,
            qty          float        null,
            cse_order_id varchar(255) null,
    		is_done      tinyint default 0 null
        );";
		$wpdb->query( $query );

		$tableName = $wpdb->prefix . CSEConstants::DEBUG_TABLE_NAME;
		$query     = "create table if not exists {$tableName}
			(
			    id            int auto_increment
			        primary key,
			    date          varchar(255) null,
			    xml           blob         null,
			    response      blob         null,
			    curl_error    blob         null,
			    function_name varchar(255) null
			);";
		$wpdb->query( $query );

		add_option( 'csedelivery_activate_finished', false );
		add_option( 'csedelivery_auth_token', wp_generate_password( 24 ) );

		add_action( 'activated_plugin', [ __CLASS__, 'runImport' ] );

	}

	public static function runImport() {
		if ( ! class_exists( 'CSEDbImporter' ) ) {
			require_once CSEDELIVERY_ROOT_DIR . 'assets/CSEDbImporter.php';
		}
		$token = get_option( CSEConstants::AUTH_TOKEN_OPTION_NAME, wp_generate_password( 24 ) );
		wp_remote_get( home_url() . '/?' . CSEDbImporter::URL_PATH . '=' . urlencode( $token ), [
			'timeout'             => 1,
			'sslverify'           => false,
			'limit_response_size' => 0,
		] );
	}
}
