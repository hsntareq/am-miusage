<?php
namespace Miusase;

class Class_Database {
	public function __construct() {
		// echo 'Hello World!';
	}
	public function amapi_get_data($table_name, $colum_name = '*',) {
		global $wpdb;
		$table_name = $wpdb->prefix . $table_name;
		return $wpdb->get_results( "SELECT $colum_name FROM $table_name" );
	}
}
