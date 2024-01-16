<?php
/**
 * Database
 *
 * @package Miusase
 */

namespace Miusase;

class Class_Database {
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'amapi_create_db_table' ) );
	}
	public function amapi_get_data( $table_name, $column_name = '*', ) {
		global $wpdb;
		$table_name = $wpdb->prefix . $table_name;
		return $wpdb->get_results( $wpdb->prepare( "SELECT %s FROM %s", $column_name, $table_name ) );
	}

	public function amapi_create_db_table() {
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

	public function amapi_delete_db_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'am_miusage_api';
		$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %s", $table_name ) );

		if(get_transient( 'amapi_data_loaded' )){
			delete_transient( 'amapi_data_loaded' );
		}
	}
}
