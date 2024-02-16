/**
 * Ajax requests of the amapi plugin.
 *
 * @package Miusage
 * @since 1.0.0
 */

import { __ } from '@wordpress/i18n';
import { navbarMessage, loadApiDataFromDatabase, toastMessage } from "./lib";

document.addEventListener("DOMContentLoaded", function () {
	let remainingResetTime = document.getElementById("viewTime").dataset.transientTime;
	let amapiValue = document.getElementById("viewTime").dataset.amapi;
	// remainingResetTime = 0;

	let refreshButton = document.getElementById("refresh_button");

	navbarMessage(remainingResetTime); // View the remaining time to reset the data by clicking the refresh button

	console.log(remainingResetTime);
	console.log('' !== remainingResetTime && remainingResetTime <= 0);

	if ('' !== remainingResetTime && remainingResetTime <= 0 && amapiValue === 'false') {
		loadApiDataFromDatabase();
	}

	refreshButton && refreshButton.addEventListener("click", function () {
		loadApiDataFromDatabase(true);
	});
});
