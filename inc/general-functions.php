<?php
/**
 * load_amapi_data_table
 *
 *
 */


// Function to generate the dynamic table
function generate_dynamic_table( $attributes, $table_data ) {
	$html = '<table class="' . $attributes['className'] . '">';
	$html .= '<thead>';
	$html .= '<tr>';
	if ( $attributes['showIdColumn'] ) {
		$html .= '<th>ID</th>';
	}
	if ( $attributes['showFirstNameColumn'] ) {
		$html .= '<th>First Name</th>';
	}
	if ( $attributes['showLastNameColumn'] ) {
		$html .= '<th>Last Name</th>';
	}
	if ( $attributes['showEmailColumn'] ) {
		$html .= '<th>Email</th>';
	}
	if ( $attributes['showDateColumn'] ) {
		$html .= '<th>Date</th>';
	}
	$html .= '</tr>';
	$html .= '</thead>';
	$html .= '<tbody>';
	foreach ( $table_data as $row ) {
		$html .= '<tr>';
		if ( $attributes['showIdColumn'] ) {
			$html .= '<td>' . $row->id . '</td>';
		}
		if ( $attributes['showFirstNameColumn'] ) {
			$html .= '<td>' . $row->first_name . '</td>';
		}
		if ( $attributes['showLastNameColumn'] ) {
			$html .= '<td>' . $row->last_name . '</td>';
		}
		if ( $attributes['showEmailColumn'] ) {
			$html .= '<td>' . $row->email . '</td>';
		}
		if ( $attributes['showDateColumn'] ) {
			$html .= '<td>' . $row->date . '</td>';
		}
		$html .= '</tr>';
	}
	$html .= '</tbody>';
	$html .= '</table>';
	return $html;
}

// Your render callback function
function amapi_data_block_render( $attributes, $content ) {
	// Generate the HTML for your block
	$html = '<div class="am-apidata-table">';
	$html .= generate_dynamic_table( $attributes, amapi_get_all_data() );
	$html .= '</div>';

	return $html;
}

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
