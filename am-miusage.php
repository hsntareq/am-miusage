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
 * @package Miusage
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AMAPI_PLUGIN_FILE', plugin_dir_path( __FILE__ ) );
define( 'AMAPI_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'AMAPI_VERSION', '1.0.0' );

if ( file_exists( AMAPI_PLUGIN_FILE . 'vendor/autoload.php' ) ) {
	require_once AMAPI_PLUGIN_FILE . 'vendor/autoload.php';
}

register_activation_hook( __FILE__, array( ( new Miusase\Class_Database() ), 'amapi_create_db_table' ) );
register_deactivation_hook( __FILE__, array( ( new Miusase\Class_Database() ), 'amapi_delete_db_table' ) );

if ( file_exists( AMAPI_PLUGIN_FILE . 'inc/class-data-list-table.php' ) ) {
	require_once AMAPI_PLUGIN_FILE . 'inc/class-data-list-table.php';
}


