<?php
/**
 * Plugin Name: AM Miusage API
 * Plugin URI: https://github.com/hsntareq/am-miusage-api
 * Description: This plugin is to create a block for the Miusage API data to display on the front end and manage the data from the back end. Api url: <a target="_blank" href="https://miusage.com/v1/challenge/1" >https://miusage.com/v1/challenge/1</a>
 * Version: 1.0.0
 * Requires at least: 6.2
 * Requires PHP: 8.2
 * Author: Hasan Tareq
 * Author URI: https://github.com/hsntareq
 * License: GPLv2 or later
 * Text Domain: amapi
 *
 * @package api-block
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AMAPI_PLUGIN_FILE', plugin_dir_path( __FILE__ ) );
define( 'AMAPI_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'AMAPI_VERSION', '1.0.0' );
// return;
if ( file_exists( AMAPI_PLUGIN_FILE . 'vendor/autoload.php' ) ) {
	require_once AMAPI_PLUGIN_FILE . 'vendor/autoload.php';
}

if ( ! function_exists( 'amapi_table_page_content' ) ) {
	function amapi_table_page_content() {
		if ( file_exists( AMAPI_PLUGIN_FILE . 'awsome-table.php' ) ) {
			require_once AMAPI_PLUGIN_FILE . 'awsome-table.php';
		} else {
			die( 'Some how your plugin table file is deleted or name changed. please install this plugin again' );
		}
	}
}
if ( ! function_exists( 'amapi_create_db_table' ) ) {
	function amapi_create_db_table() {
		global $wpdb;

		$table_name      = $wpdb->prefix . 'am_miusage_api';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL,
			first_name varchar(255) NOT NULL,
			last_name varchar(255) NOT NULL,
			email varchar(255) NOT NULL,
			date varchar(255) NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}
register_activation_hook( __FILE__, 'amapi_create_db_table' );

if ( ! function_exists( 'amapi_delete_db_table' ) ) {
	function amapi_delete_db_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'am_miusage_api';
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
		wp_clear_scheduled_hook( 'amapi_cron_hook' );
	}
}
register_deactivation_hook( __FILE__, 'amapi_delete_db_table' );

if ( ! function_exists( 'amapi_get_all_data' ) ) {
	function amapi_get_all_data( $args = [] ) {
		global $wpdb;
		$defaults = [
			'number'  => 20,
			'offset'  => 0,
			'orderby' => 'id',
			'order'   => 'ASC',
		];

		$args = wp_parse_args( $args, $defaults );

		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}am_miusage_api ORDER BY {$args['orderby']} {$args['order']} LIMIT %d, %d",
				$args['offset'], $args['number']
			)
		);
		return $items;
	}
}

if ( ! function_exists( 'amapi_data_count' ) ) {
	function amapi_data_count() {
		global $wpdb;
		return (int) $wpdb->get_var( "SELECT COUNT(id) FROM {$wpdb->prefix}am_miusage_api" );
	}
}
if ( file_exists( AMAPI_PLUGIN_FILE . 'inc/class-data-list-table.php' ) ) {
	require_once AMAPI_PLUGIN_FILE . 'inc/class-data-list-table.php';
}
if ( ! function_exists( 'load_amapi_data_table' ) ) {
	function load_amapi_data_table() {
		$table = new Miusase\Class_Data_List_Table();
		$table->prepare_items();
		$table->display();
	}
}
if ( ! function_exists( 'amapi_custom_cron_schedule' ) ) {
	function amapi_custom_cron_schedule( $schedules ) {
		if ( ! isset( $schedules['every_minute'] ) ) {
			$schedules['every_minute'] = array(
				'interval' => 60,
				'display'  => __( 'Every Minute' )
			);
		}
		return $schedules;
	}
	add_action( 'cron_schedules', 'cron_custom_schedule' );
}

if ( ! function_exists( 'amapi_cron_job' ) ) {
	function amapi_cron_job() {
		( new Miusase\Class_Ajax_Request() )->load_amapi_data();
	}
	add_action( 'amapi_cron_hook', 'amapi_cron_job' );
}
if ( ! function_exists( 'plugin_activation' ) ) {
	function plugin_activation() {
		if ( ! wp_next_scheduled( 'amapi_cron_hook' ) ) {
			wp_schedule_event( time(), 'every_minute', 'amapi_cron_hook' );
		}
	}
	add_action( 'init', 'plugin_activation' );
}
