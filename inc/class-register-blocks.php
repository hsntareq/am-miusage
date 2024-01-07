<?php

namespace Miusase;

class Register_Blocks {
	public function __construct() {
		add_action( 'init', [ $this, 'register_block' ] );
		add_action( 'admin_menu', [ $this, 'amapi_add_table_page' ] );
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
			array( $this, 'amapi_table_page_content' ),
			'dashicons-admin-generic',
			20
		);
	}

	public function amapi_table_page_content() {
		if ( file_exists( AMAPI_PLUGIN_FILE . 'awsome-table.php' ) ) {
			require_once AMAPI_PLUGIN_FILE . 'awsome-table.php';
		} else {
			die( 'Some how your plugin table file is deleted or name changed. please install this plugin again' );
		}
	}

}
