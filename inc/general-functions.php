<?php
/**
 * load_amapi_data_table
 *
 *
 */


// Add your block in your theme or plugin functions.php file
// function my_dynamic_block_init() {
// 	register_block_type( 'hsntareq/am-miusage-api', array(
// 		'render_callback' => 'my_dynamic_block_render',
// 	) );
// }

// add_action( 'init', 'my_dynamic_block_init' );

// Your render callback function
function amapi_data_block_render( $attributes, $content ) {
	// Fetch and process dynamic data
	$dynamic_data = my_fetch_dynamic_data();

	// Generate the HTML for your block
	$html = '<div class="am-apidata-table">';
	$html .= '<table>';
	$html .= '<thead><tr>';

	// Add table headers based on attributes
	if ( $attributes['showIdColumn'] ) {
		$html .= '<th>ID</th>';
	}
	// Add other headers based on other attributes

	$html .= '</tr></thead>';
	$html .= '<tbody>';

	// Add table rows based on dynamic data and attributes
	foreach ( $dynamic_data as $dataItem ) {
		$html .= '<tr>';

		if ( $attributes['showIdColumn'] ) {
			$html .= '<td>' . esc_html( $dataItem['id'] ) . '</td>';
		}
		// Add other cells based on other attributes

		$html .= '</tr>';
	}

	$html .= '</tbody></table>';
	$html .= '</div>';

	return $html;
}

// Function to fetch dynamic data
function my_fetch_dynamic_data() {
	// Your code to fetch dynamic data from the API or database
	// For example:
	// $data = get_data_from_api();
	$data = array(
		array( 'id' => 1, 'name' => 'John Doe' ),
		array( 'id' => 2, 'name' => 'Jane Doe' ),
	);

	return $data;
}



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
