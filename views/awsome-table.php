<div id="amapi-page-header">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
</div>

<div class="wrap" id="wp-mail-smtp">
	<div style="display: flex;align-items:center;justify-content:space-between">
		<div class="amapi-page-title" style="width:100%;display:flex;align-items:center;justify-content:space-between;">
			<a href="javascript:void(0)" class="tab active"> General </a>

			<div style="display:flex;align-items:center;gap:20px;">
				<div class="loader" style="display:none;"><span class="spinner is-active"
						style="float:none;margin-top:0"></span> Loading...</div>
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
