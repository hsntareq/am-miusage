<?php
/**
 * AMPI Table for admin page.
 *
 * @package Miusage
 * @since   1.0.0
 */

$transientTimestamp = get_transient( 'timeout_amapi_data_loaded' );

?>
<div id="amapi-page-header" style="display:flex;align-items:center;justify-content:space-between">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<div class="toast_message" style="display:none">
		<div class="notice is-dismissible"></div>
	</div>
</div>


<div class="wrap" id="wp-mail-smtp">
	<div style="display: flex;align-items:center;justify-content:space-between">
		<div class="amapi-page-title" style="width:100%;display:flex;align-items:center;justify-content:space-between;">
			<a href="javascript:void(0)" class="tab active"> General </a>
			<div style="display:flex;align-items:center;gap:10px;">
				<?php if ( $transientTimestamp ) { ?>
					<p id="viewTime"></p>
				<?php } ?>
				<div style="width:22px;">
					<div class="loader" style="display:none;"><span class="spinner is-active"
							style="float:none;margin:0"></span>
					</div>
				</div>
				<button class="button button-primary" id="refresh_button">
					Refresh
				</button>
			</div>
		</div>
	</div>

	<div class="amapi-page-content">
		<form action="" method="post"> <?php load_amapi_data_table(); ?></form>
	</div>
</div>
<script>
	document.addEventListener("DOMContentLoaded", function () {
		var viewTime = document.getElementById("viewTime");
		if (viewTime) {
			var transientTime = <?php echo json_encode( $transientTimestamp ); ?>;
			function updateTime() {
				viewTime.innerText = 'timeout duration:' + new Date((transientTime - (Date.now() / 1000)) * 1000).toISOString().substr(11, 8);
			};
			updateTime();
			setInterval(updateTime, 1000);
		}
	});
</script>
