<?php
namespace Miusase;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Class_Data_List_Table extends \WP_List_Table {
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Data', 'amapi' ),
			'plural'   => __( 'Datas', 'amapi' ),
			'ajax'     => false,
			'screen'   => 'amapi-table-page'
		] );
	}

	public function get_columns() {
		return [
			'cb'         => '<input type="checkbox" />',
			'id'         => __( 'ID', 'amapi' ),
			'first_name' => __( 'First Name', 'amapi' ),
			'last_name'  => __( 'Last Name', 'amapi' ),
			'email'      => __( 'Email', 'amapi' ),
			'date'       => __( 'Date', 'amapi' ),
		];
	}
	public function get_sortable_columns() {
		return [
			'id'         => [ 'ID', true ],
			'first_name' => [ 'first_name', true ],
			'last_name'  => [ 'last_name', true ],
			'email'      => [ 'email', true ],
			'date'       => [ 'date', true ],
		];
	}
	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'value':
				# code...
				break;
			default:
				return isset( $item->$column_name ) ? $item->$column_name : '';
		}
	}
	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="data_id[]" value="%s" />', $item->id
		);
	}
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = [];
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$per_page              = 10;
		$current_page          = $this->get_pagenum();
		$total_items           = amapi_data_count();
		$offset                = ( $current_page - 1 ) * $per_page;
		$this->set_pagination_args( [
			'total_items' => $total_items,
			'per_page'    => $per_page
		] );

		$args = [
			'number' => $per_page,
			'offset' => $offset,
		];
		if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
			$args['orderby'] = $_REQUEST['orderby'];
			$args['order']   = $_REQUEST['order'];
		}
		$this->items = amapi_get_all_data( $args );
	}
}
