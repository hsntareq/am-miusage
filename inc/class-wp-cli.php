<?php
/**
 * WP-CLI Command to refresh data forcefully.
 *
 * @package Miusage\WP-CLI
 */

namespace Miusage;

/**
 * WP-CLI Command to refresh data forcefully.
 *
 */
if ( ! class_exists( 'WP_CLI_Command' ) ) {
	// Include the necessary WP-CLI files
	require_once AMAPI_PLUGIN_FILE . '/vendor/wp-cli/wp-cli/php/class-wp-cli.php';
	require_once AMAPI_PLUGIN_FILE . '/vendor/wp-cli/wp-cli/php/class-wp-cli-command.php';
}
if ( ! class_exists( 'Force_Refresh_Data' ) ) {
	/**
	 * Class Force_Refresh_Data
	 *
	 */
	class Force_Refresh_Data extends \WP_CLI_Command {

		/**
		 * Execute the custom command.
		 *
		 * @param array $args       Command arguments.
		 * @param array $assoc_args Command associative arguments.
		 */
		public function execute( $args, $assoc_args ) {
			( new \Miusase\Class_Ajax_Request() )->load_amapi_data( true );
			\WP_CLI::success( __( 'Data refreshed successfully.', 'amapi' ) );
		}
	}

	// Register the command with WP-CLI.
	\WP_CLI::add_command( 'refresh_forcefully', 'Miusage\Force_Refresh_Data' );
}

