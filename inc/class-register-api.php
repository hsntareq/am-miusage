<?php
/**
 * Register API
 *
 * @package Miusage
 * @since   1.0.0
 */

namespace Miusase;

/**
 * This class is responsible for registering the API endpoint.
 */
class Register_API {
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * register_routes
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route( 'amiusage', 'data', array(
			'methods'  => 'GET',
			'callback' => array( $this, 'amapi_awesome_func' ),
		) );
	}

	/**
	 * amapi_awesome_func
	 *
	 * @return void
	 */
	public function amapi_awesome_func() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'am_miusage_api';
		$table_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %i", $table_name ) );
		// https://developer.wordpress.org/reference/classes/wpdb/prepare/#changelog

		wp_send_json( $table_data );
	}
}
