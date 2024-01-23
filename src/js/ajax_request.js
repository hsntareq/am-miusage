import { updateTimeStamp } from "./lib";
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
	let datasetTransientTime = document.getElementById("viewTime").dataset.transientTime;
	updateTimeStamp(datasetTransientTime);

	var table = document.querySelector('.wp-list-table');
	var loader = document.querySelector('.loader');
	loader.style.display = 'block';

	if (table && table.querySelector('tbody tr.no-items') == null) {
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
					let toast_html = (message) => `<div class="notice notice-${typeof (response.data) == 'string' ? 'warning' : 'success'} notice-alt is-dismissible" style="transition:all 300ms;"><p>${message}</p>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>`;

					toast_message.style.display = 'block';

					toast_message.innerHTML = toast_html(typeof (response.data) == 'string' ? response.data : 'Data updated successfully');

					let isNoticeBtn = toast_message.querySelector('.notice-dismiss');

					isNoticeBtn && isNoticeBtn.addEventListener('click', function () {
						toast_message.innerHTML = '';
					});
					if (response.data.transient_time) {
						updateTimeStamp(response.data.transient_time);
					}

					setTimeout(() => {
						toast_message.innerHTML = '';
					}, 3000);
				}
			})
			.catch(error => {
				console.error(error);
			});
	});
});
