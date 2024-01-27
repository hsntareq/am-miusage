/**
 * External dependencies
 */

import { __ } from '@wordpress/i18n';
export function updateTimeStamp(transientTime) {
	var viewTime = document.getElementById("viewTime");
	const fallbackMessage = __('Hit the Refresh button to get new data from the server', 'amapi');
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

					viewTime.innerText = __('Data will be refreshed in', 'amapi') + ' ' + formattedTime;
				} else {
					clearInterval(intervalId);
					viewTime.innerText = fallbackMessage;
				}
			}
			var intervalId = setInterval(updateTime, 1000);
			updateTime();
		} else {
			viewTime.innerText = fallbackMessage;
		}
	}

	function padZero(num) {
		return (num < 10 ? "0" : "") + num;
	}
}
