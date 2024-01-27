<?php
/**
 * Load Amapi Data Table
 *
 * @package Miusage
 * @since 1.0.0
 */

if ( ! function_exists( 'amapi_generate_dynamic_table' ) ) {
	function amapi_generate_dynamic_table( $attributes, $table_data ) {

		$html = '<table>';
		$html .= '<thead>';
		$html .= '<tr>';
		if ( $attributes['showIdColumn'] ) {
			$html .= '<th>' . __( 'ID', 'amapi' ) . '</th>';
		}
		if ( $attributes['showFirstNameColumn'] ) {
			$html .= '<th>' . __( 'First Name', 'amapi' ) . '</th>';
		}
		if ( $attributes['showLastNameColumn'] ) {
			$html .= '<th>' . __( 'Last Name', 'amapi' ) . '</th>';
		}
		if ( $attributes['showEmailColumn'] ) {
			$html .= '<th>' . __( 'Email', 'amapi' ) . '</th>';
		}
		if ( $attributes['showDateColumn'] ) {
			$html .= '<th>' . __( 'Date', 'amapi' ) . '</th>';
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
}

if ( ! function_exists( 'amapi_data_block_render' ) ) {
	/**
	 * Amapi Data Block Render
	 *
	 * @param mixed $attributes
	 * @param mixed $content
	 *
	 * @return void
	 */
	function amapi_data_block_render( $attributes, $content ) {
		ob_start();
		$html = '<div class="am-apidata-table">';
		$html .= amapi_generate_dynamic_table( $attributes, amapi_get_all_data() );
		$html .= '</div>';
		echo $html;
		echo ob_get_clean();
	}
}

if ( ! function_exists( 'amapi_get_all_data' ) ) {
	/**
	 * Amapi get all data
	 *
	 * @param mixed $args
	 *
	 * @return void
	 */
	function amapi_get_all_data( $args = [] ) {
		global $wpdb;

		$args = wp_parse_args( $args, [
			'number'  => 20,
			'offset'  => 0,
			'orderby' => 'id',
			'order'   => 'ASC',
		] );

		$table_name = $wpdb->prefix . 'am_miusage_api';
		$order      = 'DESC' === strtoupper( $args['order'] ) ? 'DESC' : 'ASC';
		$order      = sanitize_sql_orderby( "{$order}" );


		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM %i ORDER BY %i {$order} LIMIT %d, %d",
				$table_name, $args['orderby'], $args['offset'], $args['number']
			)
		);
		// https://developer.wordpress.org/reference/classes/wpdb/prepare/#changelog
	}
}


if ( ! function_exists( 'load_amapi_data_table' ) ) {
	/**
	 * Load amapi data table
	 *
	 * @return void
	 */
	function load_amapi_data_table() {
		$table = new Miusase\Class_Data_List_Table();
		$table->prepare_items();
		$table->display();
	}
}

if ( ! function_exists( 'plugin_activation' ) ) {
	/**
	 * Plugin activation
	 *
	 * @return void
	 */
	function plugin_activation() {
		( new \Miusase\Class_Ajax_Request() )->load_amapi_data();
	}
	register_activation_hook( __FILE__, 'plugin_activation' );
}
