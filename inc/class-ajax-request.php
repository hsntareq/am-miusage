<?php
namespace Miusase;

class Class_Ajax_Request {
	public function __construct() {
		add_action( 'wp_ajax_load_amapi_data', [ $this, 'load_amapi_data' ] );
		add_action( 'wp_ajax_nopriv_load_amapi_data', [ $this, 'load_amapi_data' ] );
	}
	public function load_amapi_data() {
		$request_args = array(
			'headers' => array(
				'Content-Type' => 'application/json'
			),
		);
		$apiEndpoint  = "https://miusage.com/v1/challenge/1/";
		$response     = wp_remote_get( $apiEndpoint, $request_args );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( "Error retrieving data from the API: " . $response->get_error_message() );
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ) );
		if ( ! $response_body || ! isset( $response_body->data ) || ! isset( $response_body->data->rows ) ) {
			wp_send_json_error( "Invalid response from the API." );
		}

		$rows = $response_body->data->rows;

		global $wpdb;
		$table_name = $wpdb->prefix . 'am_miusage_api';

		// Delete existing data before inserting new data
		$wpdb->query( "TRUNCATE TABLE $table_name" );

		foreach ( $rows as $data ) {
			$data_to_insert = array(
				'id'         => $data->id,
				'first_name' => $data->fname,
				'last_name'  => $data->lname,
				'email'      => $data->email,
				'date'       => date( 'Y-m-d H:i:s', $data->date ),
			);

			$result = $wpdb->insert( $table_name, $data_to_insert );

			if ( $result === false ) {
				wp_send_json_error( "Error inserting data: " . $wpdb->last_error );
			}
		}

		if ((defined('DOING_AJAX') && DOING_AJAX) || (defined('WP_CLI') && WP_CLI) && wp_next_scheduled('amapi_cron_hook')) {
			$this->amapi_reschedule_cron();
		}

		wp_send_json_success( $response_body );
	}

	public function amapi_reschedule_cron() {
		if ( wp_next_scheduled( 'amapi_cron_hook' ) ) {
			// wp_clear_scheduled_hook('amapi_cron_hook');
		}
		wp_schedule_event( time(), 'every_minute', 'amapi_cron_hook' );
	}
}
