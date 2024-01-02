<?php

namespace Miusase;

class Register_Blocks {
	public function __construct() {
		add_action( 'init', [ $this, 'register_block' ] );
		add_action('admin_menu', [$this, 'amapi_add_table_page']);
	}

	public function register_block() {
		register_block_type( AMAPI_PLUGIN_FILE . '/build' );
	}
	public function amapi_add_table_page() {
		add_menu_page(
			'Awsome Table Data',
			'Awsome Table',
			'manage_options',
			'amapi-table-page',
			'amapi_table_page_content',
			'dashicons-admin-generic',
			20
		);
	}
}
