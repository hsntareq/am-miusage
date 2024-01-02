<?php
// namespace Miusase;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Class_Custom_List extends WP_List_Table {

	// Constructor function
	public function __construct() {
		parent::__construct( array(
			'singular' => 'custom_item',
			'plural'   => 'custom_items',
			'ajax'     => false,
		) );
	}

	// Define columns
	public function get_columns() {
		return array(
			'cb'    => '<input type="checkbox" />',
			'id'    => __( 'ID', 'your-text-domain' ),
			'name'  => __( 'Name', 'your-text-domain' ),
			'email' => __( 'Email', 'your-text-domain' ),
			// Add more columns as needed
		);
	}

	// Define sortable columns
	public function get_sortable_columns() {
		return array(
			'id'    => array( 'id', true ),
			'name'  => array( 'name', false ),
			'email' => array( 'email', false ),
			// Add more sortable columns as needed
		);
	}

	// Define default column values
	protected function column_default( $item, $column_name ) {
		if ( $item === null || ! isset( $item[ $column_name ] ) ) {
			return '';
		}

		switch ( $column_name ) {
			case 'id':
			case 'name':
			case 'email':
				return $item[ $column_name ];
			// Add more cases for additional columns
			default:
				return '';
		}
	}

    // Helper function to get items per page from screen options

	protected function get_items_per_page($option, $default_value = 20) {
        $per_page = get_user_option($option);
        return empty($per_page) ? $default_value : absint($per_page);
    }


	// Define checkbox column
	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="id[]" value="%s" />',
			$item['id']
		);
	}

	// Define the data to be displayed
	public function prepare_items() {
		// Your data retrieval logic goes here
		$data = array(
			array( 'id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com' ),
			array( 'id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com' ),
			array( 'id' => 3, 'name' => 'Jane Doe', 'email' => 'jane@example.com' ),
			array( 'id' => 4, 'name' => 'Jane Doe', 'email' => 'jane@example.com' ),
			// Add more data as needed
		);

		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

        $per_page = $this->get_items_per_page('custom_items_per_page', 10);
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
		) );

		// Ensure $data is an array and not null
		if ( is_array( $data ) ) {
			$this->items = array_slice( $data, ( $current_page - 1 ) * $per_page, $per_page );
		} else {
			$this->items = array();
		}
	}
}
