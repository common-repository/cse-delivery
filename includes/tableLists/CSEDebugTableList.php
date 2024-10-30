<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class CSEDebugTableList extends WP_List_Table {
	/**
	 * @var array
	 */
	private $data;

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Отладка' ), //singular name of the listed records
			'plural'   => __( 'Отладка' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );
	}

	/**
	 * Retrieve customer’s data from the database
	 *
	 * @param int $per_page
	 * @param int $current_page
	 *
	 * @return mixed
	 */
	public function get_data( int $per_page = 20, int $current_page = 1 ) {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::DEBUG_TABLE_NAME;

		$offset = $per_page * ( $current_page - 1 );

		return $wpdb->get_results( "SELECT * FROM {$table_name } ORDER BY id DESC LIMIT {$offset}, {$per_page};", 'ARRAY_A' );
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public function record_count() {
		global $wpdb;
		$table_name = $wpdb->prefix . CSEConstants::DEBUG_TABLE_NAME;

		return $wpdb->get_var( "SELECT COUNT(*) FROM {$table_name };" );
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'Нет отладочных данных' );
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
			case 'xml':
				echo '<a href="' . admin_url( 'admin-ajax.php' ) . '?action=get_debug&id=' . $item['id'] . '&type=xml" target="_blank"><span class="dashicons dashicons-media-code"></span>Открыть</a>';
				break;
			case 'response':
				echo '<a href="' . admin_url( 'admin-ajax.php' ) . '?action=get_debug&id=' . $item['id'] . '&type=response" target="_blank"><span class="dashicons dashicons-media-code"></span>Открыть</a>';
				break;
			case 'create_date':
				echo $item['date'];
				break;
			case 'curl_error':
				echo base64_decode( $item['curl_error'] );
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
			'cb'            => '<input type="checkbox" />',
			'create_date'   => __( 'Время' ),
			'xml'           => __( 'Запрос' ),
			'response'      => __( 'Ответ' ),
			'curl_error'    => __( 'CURL ошибка' ),
			'function_name' => __( 'Функция' ),
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
		return [
			'delete' => 'Удалить',
			'clear'  => 'Очистить таблицу',
		];
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 *
	 */
	public function prepare_items() {

		$this->processBulkActions();
		$this->_column_headers = $this->get_column_info();

		$per_page     = $this->get_items_per_page( 'cse_debug_per_page', 20 );
		$current_page = $this->get_pagenum();

		$this->set_pagination_args( [
			'total_items' => $this->record_count(),
			'per_page'    => $per_page,
		] );
		$this->items = $this->get_data( $per_page, $current_page );
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="debug_rows[]" value="%s" />', $item['id']
		);
	}

	private function processBulkActions() {
		$action = $this->current_action();
		switch ( $action ) {
			case 'delete':
				$ids = array_map( 'sanitize_text_field', $_POST['debug_rows'] );
				$this->deleteRows( $ids );
				break;
			case 'clear':
				$this->clearAll();
				break;
		}
	}

	private function deleteRows( $ids ) {
		global $wpdb;
		$wpdb->query( 'DELETE FROM ' . $wpdb->prefix . CSEConstants::DEBUG_TABLE_NAME . ' WHERE id IN (' . implode( ',', $ids ) . ')' );
	}

	private function clearAll() {
		global $wpdb;
		$wpdb->query( 'DELETE FROM ' . $wpdb->prefix . CSEConstants::DEBUG_TABLE_NAME . ' WHERE id > 0' );
	}
}