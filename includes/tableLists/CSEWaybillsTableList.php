<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class CSEWaybillsTableList extends WP_List_Table {
	/**
	 * @var array
	 */
	private $data;

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Накладная' ), //singular name of the listed records
			'plural'   => __( 'Накладные' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );
	}

	/**
	 * Retrieve customer’s data from the database
	 *
	 * @return mixed
	 */
	public function get_data() {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME;

		return $wpdb->get_results( "SELECT * FROM $table_name WHERE cse_order_id IS NULL AND is_done=0 ORDER BY number DESC;", 'ARRAY_A' );
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public function record_count() {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME;

		return $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE cse_order_id IS NULL AND is_done=0 ;", 'ARRAY_A' );
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'Нет накладных для отправки' );
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {
		return '<strong>' . $item['name'] . '</strong>';
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'add_to_order':
				echo '<input type="checkbox" name="numbers[]" value="' . $item['number'] . '">';
				break;
			case 'number':
				echo '<span>' . $item[ $column_name ] . '</span>';
				echo ' (<a href="' . admin_url( 'admin-ajax.php' ) . '?action=get_way_bill_pdf&type=waybill&number=' . $item[ $column_name ] . '" target="_blank"><span style="font-size: 14px; line-height: 1.5;" class="dashicons dashicons-format-aside"></span></a>)';
				break;
			default:
				echo $item[ $column_name ];
				break;
		}
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$result = [
			'add_to_order' => __( 'Добавить в заказ' ),
			'number'       => __( 'Номер накладной' ),
			'date'         => __( 'Дата оформления' ),
			'deliveryFrom' => __( 'Адрес отправителя' ),
			'order_id'     => __( 'ID заказа в магазине' ),
			'weight'       => __( 'Масса груза, кг' ),
			'qty'          => __( 'Кол-во мест' ),
		];

		return $result;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return [];
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return [];
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 *
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		$options = CSEFunctions::getOptions();

		$obCSE = new CSEOrder( $options['wc_cse_login'], $options['wc_cse_password'] );

		$data = $this->get_data();

		$this->items = $data;
	}


	public function print_column_headers( $with_id = true ) {
		if ( ! $with_id ) {
			echo '';
		} else {
			parent::print_column_headers( $with_id );
		}
	}
}