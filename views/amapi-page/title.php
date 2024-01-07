<div style="display: flex;align-items:center;justify-content:space-between">
	<div class="amapi-page-title" style="width:100%;display:flex;align-items:center;justify-content:space-between;">
		<a href="javascript:void(0)" class="tab active"> General </a>

		<?php
		$timestamp = wp_next_scheduled( 'amapi_cron_hook' );


		if ( $timestamp ) {
			$remaining_time = $timestamp - time();
			echo 'Remaining time for the scheduled event: ' . human_time_diff( time(), $timestamp );
		} else {
			echo 'No scheduled event found.';
		}
		?>
		<div style="display:flex;align-items:center;gap:20px;">
			<div class="loader" style="display:none;"><span class="spinner is-active"
					style="float:none;margin-top:0"></span> Loading...</div>
			<button class="button button-primary" id="refresh_button">
				Refresh
			</button>
		</div>
	</div>

</div>
