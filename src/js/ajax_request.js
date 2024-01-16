import './working';

const ajax_request = (action, { type = 'GET', ...rest } = {}) => {
	console.log(type);
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
	// console.log(table.querySelector('tbody tr.no-items'));
	loader.style.display = 'block';

	if (table && table.querySelector('tbody tr.no-items') !== null) {
		ajax_request('load_amapi_data', { type: 'POST' })
			.then(() => {
				//location.reload()
			})

	} else {
		if (loader) {
			loader.style.display = 'none';
		}
	}


	var refreshButton = document.getElementById("refresh_button");
	refreshButton && refreshButton.addEventListener("click", function () {
		loader.style.display = 'block';
		ajax_request('load_amapi_data', { type: 'POST' })
			.then(response => {
				if (response.success === true) {
					location.reload();
				}
			})
			.catch(error => {
				console.error(error);
			});
	});

	var wpcliButton = document.getElementById("wpcli_button");
	wpcliButton && wpcliButton.addEventListener("click", function () {
		loader.style.display = 'block';
		ajax_request('load_amapi_wpcli_data', { type: 'POST' })
			.then(response => {
				if (response.success === true) {
					// location.reload();
				}
			})
			.catch(error => {
				console.error(error);
			});
	});
});
