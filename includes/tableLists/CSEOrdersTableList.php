<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class CSEOrdersTableList extends WP_List_Table {
	/**
	 * @var array
	 */
	private $data;

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Заказ' ), //singular name of the listed records
			'plural'   => __( 'Заказы' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );
	}

	/**
	 * Retrieve customer’s data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public function get_data( $per_page, $page_number ) {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME;

		$offset = $per_page * ( $page_number - 1 );

		$rows = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY number DESC", 'ARRAY_A' );

		return array_slice( $rows, $offset, $per_page );
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public function record_count() {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::WAYBILLS_TABLE_NAME;

		return $wpdb->get_var( "SELECT count(*) FROM {$table_name};" );
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'Нет дынных о заказах' );
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
			case 'status':
				echo '<span>' . ( $item['status']['NAME'] ?? '' ) . '</span>';
				break;
			case 'cse_order_id':
				echo '<span>' . $item[ $column_name ] . '</span>';
				if ( $item[ $column_name ] ) {
					echo ' (<a href="' . admin_url( 'admin-ajax.php' ) . '?action=get_way_bill_pdf&type=order&number=' . $item[ $column_name ] . '" target="_blank">Печатная форма</a>)';
				}
				break;
			case 'number':
				echo '<span>' . $item[ $column_name ] . '</span>';
				if ( $item[ $column_name ] ) {
					echo ' (<a href="' . admin_url( 'admin-ajax.php' ) . '?action=get_way_bill_pdf&type=waybill&number=' . $item[ $column_name ] . '" target="_blank">Печатная форма</a>)';
				}
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
			'cse_order_id' => __( 'Номер заказа' ),
			'number'       => __( 'Номер накладной' ),
			'status'       => __( 'Статус накладной' ),
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

		$per_page     = $this->get_items_per_page( 'cse_orders_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = $this->record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		] );

		$data = $this->get_data( $per_page, $current_page );

		$options = CSEFunctions::getOptions();

		$obCSE = new CSEOrder( $options['wc_cse_login'], $options['wc_cse_password'] );

		foreach ( $data as &$item ) {
			if ( ! $item['cse_order_id'] ) {
				continue;
			}
			$status = $obCSE->getDocumentStatus( CSEOrder::WAYBILL, $item['number'] );
			if ( $status ) {
				$item['status'] = reset( $status );
			}
		}

		$this->items = $data;
	}
}