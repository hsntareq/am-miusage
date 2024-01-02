<?php
namespace Miusase;

class Enqueue {
	public function __construct() {
		// echo 'Hello World!';die;
		add_action( 'admin_enqueue_scripts', array( $this, 'load_plugin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_plugin_scripts' ) );
		add_action( 'enqueue_block_assets', array( $this, 'load_plugin_block_scripts' ) );
	}

	/**
	 * ... enqueue plugin scripts.
	 */
	public function load_plugin_block_scripts() {
		wp_enqueue_script( 'ampi_block_script', AMAPI_PLUGIN_URI . 'build/ampi_block.js', array(), AMAPI_VERSION );
	}

	/**
	 * ... enqueue plugin scripts.
	 */
	public function load_plugin_scripts() {
		if (isset($_GET['page']) && $_GET['page'] === 'amapi-table-page' ) {
			wp_enqueue_script( 'ampi_script', AMAPI_PLUGIN_URI . 'build/ampi_script.js', array(), AMAPI_VERSION );
			wp_localize_script( 'ampi_script', 'amapidata', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			) );
			wp_enqueue_style( 'ampi_style', AMAPI_PLUGIN_URI . 'build/ampi_style.css', null, AMAPI_VERSION );
			wp_enqueue_style( 'ampi_smtp_style', AMAPI_PLUGIN_URI . 'src/smtp-admin.min.css', null, AMAPI_VERSION );
		}
	}

}
