/**
 * Ajax requests of the amapi plugin.
 *
 * @package Miusage
 * @since 1.0.0
 */

import { __ } from '@wordpress/i18n';
import { viewTimeMessage, loadApiDataFromDatabase, toastMessage } from "./lib";

document.addEventListener("DOMContentLoaded", function () {
	let remainingResetTime = document.getElementById("viewTime").dataset.transientTime;
	// remainingResetTime = 0;

	let refreshButton = document.getElementById("refresh_button");

	viewTimeMessage(remainingResetTime); // View the remaining time to reset the data by clicking the refresh button

	console.log(remainingResetTime);
	console.log('' !== remainingResetTime && remainingResetTime <= 0);

	if ('' === remainingResetTime) {
		loadApiDataFromDatabase();
	}

	refreshButton && refreshButton.addEventListener("click", function () {

		/* if (remainingResetTime > 0) {
			toastMessage('success', 'You can refresh the data after 5 minutes.');
			return;
		} */

		loadApiDataFromDatabase();
	});
});
