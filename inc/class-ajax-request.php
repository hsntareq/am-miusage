<?php
 namespace Miusase;
/**
 * Ajax Request
 *
 * @package Miusage
 * @since   1.0.0
 */


// if ( ! class_exists( 'WP_CLI_Command' ) ) {
// 	// Include the necessary WP-CLI files
// 	require_once AMAPI_PLUGIN_FILE . '/vendor/wp-cli/wp-cli/php/class-wp-cli.php';
// 	require_once AMAPI_PLUGIN_FILE . '/vendor/wp-cli/wp-cli/php/class-wp-cli-command.php';
// }

class Class_Ajax_Request {
	public function __construct() {
		add_action( 'wp_ajax_load_amapi_data', [ $this, 'load_amapi_data' ] );
		add_action( 'wp_ajax_nopriv_load_amapi_data', [ $this, 'load_amapi_data' ] );
		add_action( 'wp_ajax_load_amapi_wpcli_data', [ $this, 'load_amapi_wpcli_data' ] );
		add_action( 'wp_ajax_nopriv_load_amapi_wpcli_data', [ $this, 'load_amapi_wpcli_data' ] );
	}

	public function load_amapi_wpcli_data() {
		// wp_send_json_success( 'WP CLI Command Executed!' );
		// ( new \Miusage\Force_Refresh_Data() )->execute( [], [] );
		(new \Miusage\Force_Refresh_Data())->cli_run();
	}

	public function amapi_reschedule_cron() {
		if ( wp_next_scheduled( 'amapi_cron_hook' ) ) {
			wp_clear_scheduled_hook( 'amapi_cron_hook' );
		}
		wp_schedule_event( time(), 'ampi_five_minutes', 'amapi_cron_hook' );
	}

	public function load_amapi_data($cli = false) {
		$request_args = array(
			'headers' => array(
				'Content-Type' => 'application/json',
			),
		);

		$apiEndpoint = "https://miusage.com/v1/challenge/1/";
		$response    = wp_remote_get( esc_url( $apiEndpoint ), $request_args );

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
		// https://developer.wordpress.org/reference/classes/wpdb/prepare/#description

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

		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'WP_CLI' ) && WP_CLI ) && wp_next_scheduled( 'amapi_cron_hook' ) ) {
			$this->amapi_reschedule_cron();
		}

		wp_send_json_success( $response_body->data );
	}

}
