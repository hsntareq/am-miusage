<?php
/**
 * Data List Table
 *
 * @package Miusage
 * @since   1.0.0
 */

namespace Miusase;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Class_Data_List_Table extends \WP_List_Table {
	public function __construct() {
		parent::__construct( [
			'singular' => esc_html__( 'Data', 'amapi' ),
			'plural'   => esc_html__( 'Datas', 'amapi' ),
			'ajax'     => false,
			'screen'   => 'amapi-table-page'
		] );
	}

	public function get_columns() {
		return [
			'cb'         => '<input type="checkbox" />',
			'id'         => esc_html__( 'ID', 'amapi' ),
			'first_name' => esc_html__( 'First Name', 'amapi' ),
			'last_name'  => esc_html__( 'Last Name', 'amapi' ),
			'email'      => esc_html__( 'Email', 'amapi' ),
			'date'       => esc_html__( 'Date', 'amapi' ),
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
				// Sanitize and escape as needed
				$value = sanitize_text_field( $item->$column_name );
				return esc_html( $value );
			default:
				// Escape HTML output
				return isset( $item->$column_name ) ? esc_html( $item->$column_name ) : '';
		}
	}

	protected function column_cb( $item ) {
		// Escape attribute value
		return sprintf(
			'<input type="checkbox" name="data_id[]" value="%s" />', esc_attr( $item->id )
		);
	}

	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = [];
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = [ $columns, $hidden, $sortable ];
		$per_page              = 10;
		$current_page          = $this->get_pagenum();
		$total_items           = $this->amapi_data_count();
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
			$args['orderby'] = sanitize_text_field( $_REQUEST['orderby'] );
			$args['order']   = sanitize_text_field( $_REQUEST['order'] );
		}
		$this->items = amapi_get_all_data( $args );
	}

	public function amapi_data_count() {
		global $wpdb;
		return (int) $wpdb->get_var( "SELECT COUNT(id) FROM {$wpdb->prefix}am_miusage_api" );
	}
}
