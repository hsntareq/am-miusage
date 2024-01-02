<?php
require_once( dirname( __FILE__ ) . '/../../../wp-load.php' );
// Path to the WordPress installation
$wp_path = ABSPATH;

// Full path to wp executable
$wp_cli_path = AMAPI_PLUGIN_FILE . '/wp-cron.php'; // Replace with the actual path

// WP CLI command to trigger a cron job
$wp_cli_command = "wp cron event run amapi_cron_hook";

// Build the full command
$full_command = "cd $wp_path && $wp_cli_command";

// Execute the command using shell_exec
$result = shell_exec($full_command);

// Output the result (you can handle this as needed)
echo $result;
?>
