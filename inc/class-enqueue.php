<?php
/**
 * Enqueue
 *
 * @package Miusase
 */

namespace Miusase;

class Enqueue {

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_plugin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_plugin_scripts' ) );
		add_action( 'enqueue_block_assets', array( $this, 'load_plugin_block_scripts' ) );
	}

	/**
	 * Load Plugin Block Scripts
	 *
	 * @return void
	 */
	public function load_plugin_block_scripts() {
		wp_enqueue_script( 'amapi_block_script', AMAPI_PLUGIN_URI . 'build/amapi_block.js', array(), AMAPI_VERSION );
		wp_enqueue_style( 'amapi_style', AMAPI_PLUGIN_URI . 'build/amapi_style.css', null, AMAPI_VERSION );
	}

	/**
	 * Load Plugin Scripts
	 *
	 * @return void
	 */
	public function load_plugin_scripts() {
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'amapi-table-page' ) {
			wp_enqueue_script( 'amapi_script', AMAPI_PLUGIN_URI . 'build/amapi_script.js', array('wp-i18n'), AMAPI_VERSION, true );
			wp_localize_script( 'amapi_script', 'amapidata', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			) );
			wp_enqueue_style( 'amapi_smtp_style', AMAPI_PLUGIN_URI . 'build/amapi_style.css', null, AMAPI_VERSION );
		}
	}

}
