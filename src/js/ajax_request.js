import './working';

const ajax_request = (action, data = null) => {
	const formData = new FormData();
	formData.append('action', action);
	formData.append('data', JSON.stringify(data));
	return fetch(amapidata.ajax_url, {
		method: 'POST', // Use POST method
		body: formData // Send form data
	})
		.then(function (response) {
			if (!response.ok) {
				throw new Error('Network response was not ok');
			}
			return response.json();
		});
};

document.addEventListener("DOMContentLoaded", function () {
	var table = document.querySelector('.wp-list-table');
	var loader = document.querySelector('.loader');
	// console.log(table.querySelector('tbody tr.no-items'));
	loader.style.display = 'block';

	if (table && table.querySelector('tbody tr.no-items') !== null) {
		ajax_request('load_amapi_data')
			.then(() => location.reload())

	} else {
		if (loader) {
			loader.style.display = 'none';
		}
	}


	var element = document.getElementById("refresh_button");
	element && element.addEventListener("click", function () {
		loader.style.display = 'block';
		ajax_request('load_amapi_data')
			.then(response => {
				if (response.success === true) {
					location.reload();
				}
			})
			.catch(error => {
				console.error(error);
			});
	});
});
