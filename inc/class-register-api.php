<?php

namespace Miusase;

class Register_API {
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		register_rest_route( 'amiusage', 'data', array(
			'methods'  => 'GET',
			'callback' => array( $this, 'amapi_awesome_func' ),
		) );
	}

	public function amapi_awesome_func() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'am_miusage_api';
		$table_data = $wpdb->get_results( "SELECT * FROM {$table_name}" );
		wp_send_json( $table_data );
	}
}
