<?php

/**
 * Fired during plugin deactivation
 *
 * @link       brainforce.ru
 * @since      1.0.0
 *
 * @package    CSEDelivery
 * @subpackage CSEDelivery/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CSEDelivery
 * @subpackage CSEDelivery/includes
 * @author     BrainForce <support@brainforce.com>
 */
class CSEDeliveryDeactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'update_orders_from_cse' );
		global $wpdb;
		$tableName = $wpdb->prefix . CSEConstants::CITIES_TABLE_NAME;
		$query     = "DROP TABLE IF EXISTS $tableName;";
		$wpdb->query( $query );
		$tableName = $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME;
		$query     = "DROP TABLE IF EXISTS $tableName;";
		$wpdb->query( $query );
		$tableName = $wpdb->prefix . CSEConstants::DEBUG_TABLE_NAME;
		$query     = "DROP TABLE IF EXISTS $tableName;";
		$wpdb->query( $query );
		delete_option( 'csedelivery_activate_finished' );
	}

}
