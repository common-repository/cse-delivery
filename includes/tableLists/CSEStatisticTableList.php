<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class CSEStatisticTableList extends WP_List_Table {
	/**
	 * @var array
	 */
	private $data;

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Статистика' ), //singular name of the listed records
			'plural'   => __( 'Статистика' ), //plural name of the listed records
			'ajax'     => true //should this table support ajax?
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
	public function get_data( $per_page = 5, $page_number = 1 ) {
		$offset = ( $page_number - 1 ) * $per_page;

		return array_slice( $this->data, $offset, $per_page );
	}

	public function set_data( $data = [] ) {
		$this->data = $data;
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public function record_count() {
		return count( $this->items );
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'Нет данных для статистики' );
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
			case 'take_date':
				echo '--';
				break;
			case 'download':
				echo '<a href="' . admin_url( 'admin-ajax.php' ) . '?action=get_way_bill_pdf&type=waybill&number=' . $item['numberbill'] . '" target="_blank"><span class="dashicons dashicons-format-aside"></span></a>';
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
			'number'     => __( '№ заказа' ),
			'numberbill' => __( '№ накладной' ),
			'date'       => __( 'Дата оформления' ),
			'take_date'  => __( 'Дата забора' ),
			'poluchatel' => __( 'Получатель' ),
			'mesta'      => __( 'Кол-во мест' ),
			'vesFakt'    => __( 'Вес фактический, кг' ),
//			'volumeWeight' => __( 'Вес объемный'),
			'cost'       => __( 'Стоимость заказа (СОД) , RUR.' ),
			'delivdate'  => __( 'Плановая дата доставки' ),
			'download'   => __( 'Накладная' ),
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

		$per_page     = $this->get_items_per_page( 'cse_statistic_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = count( $this->data );

		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page ),
		] );

		$this->items = $this->get_data( $per_page, $current_page );
	}
}