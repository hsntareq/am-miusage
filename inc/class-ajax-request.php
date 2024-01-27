<?php
namespace Miusase;

/**
 * Ajax Request handller.
 *
 * @package Miusage
 * @since   1.0.0
 */

/**
 * This class is responsible for execute ajax requests.
 */
class Class_Ajax_Request {
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_amapi_data', [ $this, 'load_amapi_data' ] );
		add_action( 'wp_ajax_nopriv_load_amapi_data', [ $this, 'load_amapi_data' ] );
	}
	/**
	 * Load Amapi Data
	 *
	 * @param bool $cli
	 *
	 * @return [type]
	 */
	public function load_amapi_data( $cli = false ) {
		if ( $cli == "" && get_transient( 'amapi_data_loaded' ) == true ) {
			wp_send_json_success( 'Data already loaded.' );
			exit;
		}

		$apiEndpoint = "https://miusage.com/v1/challenge/1/";
		$response    = wp_remote_get( esc_url( $apiEndpoint ), array(
			'headers' => array(
				'Content-Type' => 'application/json',
			),
		) );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( "Error retrieving data from the API: " . esc_html( $response->get_error_message() ) );
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! $response_body || ! isset( $response_body->data ) || ! isset( $response_body->data->rows ) ) {
			wp_send_json_error( "Invalid response from the API." );
		}

		$rows = $response_body->data->rows;

		global $wpdb;
		$table_name = $wpdb->prefix . 'am_miusage_api';
		$wpdb->query( $wpdb->prepare( "TRUNCATE TABLE %i", $table_name ) );
		// https://developer.wordpress.org/reference/classes/wpdb/prepare/#changelog

		foreach ( $rows as $data ) {
			$data_to_insert = array(
				'id'         => intval( $data->id ),
				'first_name' => sanitize_text_field( $data->fname ),
				'last_name'  => sanitize_text_field( $data->lname ),
				'email'      => sanitize_email( $data->email ),
				'date'       => gmdate( 'Y-m-d H:i:s', intval( $data->date ) ),
			);

			$result = $wpdb->insert( $table_name, $data_to_insert );

			if ( $result === false ) {
				wp_send_json_error( "Error inserting data: " . esc_html( $wpdb->last_error ) );
			}
		}

		set_transient( 'amapi_data_loaded', true, 60 * 60 );

		if ( $cli && get_transient( 'amapi_data_loaded' ) ) {
			return;
		} else {
			$response = [
				'response_data'  => $response_body->data->rows,
				'transient_time' => get_transient( 'timeout_amapi_data_loaded' ),
			];
			wp_send_json_success( $response );
		}
	}
}
