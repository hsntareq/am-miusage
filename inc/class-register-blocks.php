<?php

namespace Miusase;

class Register_Blocks {
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_block' ] );
		add_action( 'admin_menu', [ $this, 'amapi_add_table_page' ] );
	}

	/**
	 * Register Block
	 *
	 * @return void
	 */
	public function register_block() {
		register_block_type( AMAPI_PLUGIN_FILE . '/build', array(
			'render_callback' => 'amapi_data_block_render',
		) );
	}
	/**
	 * Amapi Add Table Page
	 *
	 * @return void
	 */
	public function amapi_add_table_page() {
		add_menu_page(
			__( 'Awsome API Table Data', 'amapi' ),
			__( 'AM API Data', 'amapi' ),
			'manage_options',
			'amapi-table-page',
			array( $this, 'amapi_table_page_content' ),
			'dashicons-admin-generic',
			20
		);
	}

	/**
	 * Amapi Table Page Content
	 *
	 * @return void
	 */
	public function amapi_table_page_content() {
		if ( file_exists( AMAPI_PLUGIN_FILE . 'views/awsome-table.php' ) ) {
			require_once AMAPI_PLUGIN_FILE . 'views/awsome-table.php';
		} else {
			die( 'Some how your plugin table file is deleted or name changed. please install this plugin again' );
		}
	}

}
