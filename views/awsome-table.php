<?php
/**
 * AMAPI Table for admin page.
 *
 * @package Miusage
 * @since   1.0.0
 */

$transient_timestamp = get_transient( 'timeout_amapi_data_loaded' );
$transient_timestamp = ( $transient_timestamp - time() ) < 0 ? 0 : $transient_timestamp;


?>
<div id="amapi-page-header" style="display:flex;align-items:center;justify-content:space-between">
	<h1><?php esc_attr_e( get_admin_page_title(), 'amapi' ); ?></h1>
	<div class="toast_message" style="display:none">
		<div class="notice is-dismissible"></div>
	</div>
</div>


<div class="wrap" id="wp-mail-smtp">
	<div style="display: flex;align-items:center;justify-content:space-between">
		<div class="amapi-page-title" style="width:100%;display:flex;align-items:center;justify-content:space-between;">
			<a href="javascript:void(0)" class="tab active"> <?php _e( 'General', 'amapi' ); ?> </a>
			<div id="refresh_button_parent" style="display:flex;align-items:center;gap:10px;">
				<p id="viewTime" data-transient-time="<?php esc_attr_e( $transient_timestamp ?? 0, 'amapi' ); ?>"></p>
				<div style="width:22px;">
					<div class="loader" style="display:none;">
						<span class="spinner is-active" style="float:none;margin:0"></span>
					</div>
				</div>
				<button class="button button-primary" id="refresh_button">
					<?php _e( 'Refresh', 'amapi' ); ?>
				</button>
			</div>
		</div>
	</div>

	<div class="amapi-page-content">
		<form action="" method="post"> <?php load_amapi_data_table(); ?></form>
	</div>
</div>
