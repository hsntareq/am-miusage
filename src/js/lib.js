/**
 * External dependencies
 */

import { __ } from '@wordpress/i18n';

export function viewLoading(show = true) {
	let loader = document.querySelector('.loader');
	loader.style.display = show ? 'block' : 'none';
}

export function formattedDate(date) {
	return new Date(date * 1000).toISOString().replace("T", " ").replace(/\.\d+Z$/, '')
};

export function loadApiDataFromDatabase() {
	let table = document.querySelector('.wp-list-table');

	viewLoading(true);
	let isTableEmpty = table.querySelector('tbody tr.no-items') === null ? true : false;


	ajaxRequest('load_amapi_data', { type: 'POST' })
		.then(response => {
			console.log(response.data);
			if (response.success === true) {

				let get_tbody_html = (rowData = {}) => {
					console.log(typeof rowData, Array.isArray(rowData));
					if (typeof rowData !== 'object') {
						console.error('rowData must be an object');
						return;
					}

					return Object.entries(rowData).map(([key, row]) => {
						return `
							<tr><th scope="row" class="check-column"><input type="checkbox" name="data_id[]" value="${row.id}"></th><td class="id column-id has-row-actions column-primary" data-colname="ID">${row.id}<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td><td class="first_name column-first_name" data-colname="First Name">${row.fname}</td><td class="last_name column-last_name" data-colname="Last Name">${row.lname}</td><td class="email column-email" data-colname="Email">${row.email}</td><td class="date column-date" data-colname="Date">${__(formattedDate(row.date), 'amapi')}</td></tr>`;
					}).join('');
				}


				if (response.data.response_data) {
					table.querySelector('tbody').innerHTML = get_tbody_html(response.data.response_data);
				}

				let responseMessage = typeof (response.data) == 'string'
					? response.data : __('Data updated successfully', 'amapi');


				if (isTableEmpty) {
					viewLoading(false);
				} else {
					toastMessage(response.data, responseMessage);
				}

				if (response.data.transient_time) {
					viewTimeMessage(response.data.transient_time);
				} else {
					toastMessage(response.data, __(responseMessage, 'amapi'));
				}

			}
		})
		.catch(error => {
			console.error(error);
		});
}

export function toastMessage(type, message) {
	let toast_element = document.querySelector('.toast_message');

	toast_element.style.display = 'block';
	let isNoticeBtn = toast_element.querySelector('.notice-dismiss');
	let toast_html = `<div class="notice notice-${type ?? 'warning'} notice-alt is-dismissible" style="transition:all 300ms;"><p>${__(message, 'amapi')}</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">${__('Dismiss this notice.', 'amapi')}</span></button></div>`;
	toast_element.innerHTML = message ? toast_html : '';

	isNoticeBtn && isNoticeBtn.addEventListener('click', function () {
		toast_element.innerHTML = '';
	});

	setTimeout(() => {
		toast_element.innerHTML = '';
		toast_element.style.display = 'none';
	}, 3000);
}

export function ajaxRequest(action, { type = 'GET', ...rest } = {}) {
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

// This function is used to display a the remaining time related message
export function viewTimeMessage(transientTime) {

	var viewTime = document.getElementById("viewTime");
	const fallbackMessage = __('Click Refresh to get updated data from the <a href="https://miusage.com/v1/challenge/1/" target="_blank">miusage.com</a> server', 'amapi');
	if (viewTime) {
		var remainingTime = parseInt(transientTime, 10) - (Math.floor(Date.now() / 1000));
		if (!remainingTime) return;
		if (remainingTime > 0) {
			function updateTime() {
				var updatedRemainingTime = parseInt(transientTime, 10) - Math.floor(Date.now() / 1000);
				if (updatedRemainingTime > 0) {
					var hours = Math.floor(updatedRemainingTime / 3600);
					var minutes = Math.floor((updatedRemainingTime % 3600) / 60);
					var seconds = updatedRemainingTime % 60;
					var formattedTime = padZero(hours) + ":" + padZero(minutes) + ":" + padZero(seconds);

					viewTime.innerHTML = __('Data will be refreshed in', 'amapi') + ' ' + formattedTime;
				} else {
					clearInterval(intervalId);
					viewTime.innerHTML = fallbackMessage;
				}
			}
			var intervalId = setInterval(updateTime, 1000);
			updateTime();
		} else {
			viewTime.innerHTML = fallbackMessage;
		}
	}

	function padZero(num) {
		return (num < 10 ? "0" : "") + num;
	}
}
