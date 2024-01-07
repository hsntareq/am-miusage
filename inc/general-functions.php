<?php
/**
 * load_amapi_data_table
 *
 * 
 */

if ( ! function_exists( 'load_amapi_data_table' ) ) {
	function load_amapi_data_table() {
		$table = new Miusase\Class_Data_List_Table();
		$table->prepare_items();
		$table->display();
	}
}

if ( ! function_exists( 'amapi_cron_job' ) ) {
	/**
	 * amapi_cron_job
	 *
	 * @return void
	 */
	function amapi_cron_job() {
		( new Miusase\Class_Ajax_Request() )->load_amapi_data();
	}
	add_action( 'amapi_cron_hook', 'amapi_cron_job' );
}

if ( ! function_exists( 'plugin_activation' ) ) {
	/**
	 * plugin_activation
	 *
	 * @return void
	 */
	function plugin_activation() {
		if ( ! wp_next_scheduled( 'amapi_cron_hook' ) ) {
			wp_schedule_event( time(), 'every_five_minutes', 'amapi_cron_hook' );
		}
	}
	add_action( 'init', 'plugin_activation' );
}

if ( ! function_exists( 'amapi_custom_cron_schedule' ) ) {
	/**
	 * amapi_custom_cron_schedule
	 *
	 * @param mixed $schedules
	 *
	 * @return array
	 */
	function amapi_custom_cron_schedule( $schedules ) {
		if ( ! isset( $schedules['every_minute'] ) ) {
			$schedules['every_minute'] = array(
				'interval' => 60,
				'display'  => __( 'Every Minute' )
			);
		}
		if ( ! isset( $schedules['every_hour'] ) ) {
			$schedules['every_hour'] = array(
				'interval' => 3600,
				'display'  => __( 'Every Hour' )
			);
		}
		if ( ! isset( $schedules['every_five_minutes'] ) ) {
			$schedules['every_five_minutes'] = array(
				'interval' => 300,
				'display'  => __( 'Every 5 Minutes' )
			);
		}
		return $schedules;
	}
	add_filter( 'cron_schedules', 'amapi_custom_cron_schedule' );
}
