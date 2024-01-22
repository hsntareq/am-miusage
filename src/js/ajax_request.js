import './working';

const ajax_request = (action, { type = 'GET', ...rest } = {}) => {
	const formData = new FormData();
	formData.append('action', action);

	if (type.toUpperCase() === 'GET' || type.toUpperCase() === 'HEAD') {
		// Exclude body for GET or HEAD requests
		return fetch(amapidata.ajax_url, {
			method: type,
		})
			.then(function (response) {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			});
	} else {
		// Include body for other request types
		formData.append('data', JSON.stringify({ type, ...rest }));
		return fetch(amapidata.ajax_url, {
			method: type,
			body: formData,
		})
			.then(function (response) {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			});
	}
};

document.addEventListener("DOMContentLoaded", function () {
	var table = document.querySelector('.wp-list-table');
	var loader = document.querySelector('.loader');
	loader.style.display = 'block';

	if (table && table.querySelector('tbody tr.no-items') !== null) {
		ajax_request('load_amapi_data', { type: 'POST' })
			.then(() => {
				location.reload()
			})

	} else {
		if (loader) {
			loader.style.display = 'none';
		}
	}

	var refreshButton = document.getElementById("refresh_button");
	refreshButton && refreshButton.addEventListener("click", function () {
		loader.style.display = 'block';
		let toast_message = document.querySelector('.toast_message');
		ajax_request('load_amapi_data', { type: 'POST' })
			.then(response => {
				loader.style.display = 'none';
				toast_message.innerHTML = '';
				if (response.success === true) {
					let toast_html = (message) => `<div class="notice notice-warning notice-alt is-dismissible" style="transition:all 300ms;"><p>${message}</p>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>`;

					toast_message.style.display = 'block';

					toast_message.innerHTML = toast_html(response.data);

					let isNoticeBtn = toast_message.querySelector('.notice-dismiss');

					isNoticeBtn && isNoticeBtn.addEventListener('click', function () {
						toast_message.innerHTML = '';
					});

					setTimeout(() => {
						toast_message.innerHTML = '';
					}, 3000);
				}
			})
			.catch(error => {
				console.error(error);
			});
	});

	// var wpcliButton = document.getElementById("wpcli_button");
	// wpcliButton && wpcliButton.addEventListener("click", function () {
	// 	loader.style.display = 'block';
	// 	ajax_request('load_amapi_wpcli_data', { type: 'POST' })
	// 		.then(response => {
	// 			if (response.success === true) {
	// 				// location.reload();
	// 			}
	// 		})
	// 		.catch(error => {
	// 			console.error(error);
	// 		});
	// });

	var wpcliButton = document.getElementById("wpcli_button");
	wpcliButton && wpcliButton.addEventListener("click", function () {
		loader.style.display = 'block';
		const paramValue = 'your_parameter_value';
		fetch(amapidata.ajax_url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: new URLSearchParams({
				action: 'load_amapi_wpcli_data',
				param_value: paramValue,
			}),
		})
			.then(response => response.json())
			.then(data => {
				console.log(data);
			})
			.catch(error => {
				console.error('Error:', error);
			});

	});
});
