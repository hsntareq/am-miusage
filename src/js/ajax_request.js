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
	var element = document.getElementById("refresh_button");
	element && element.addEventListener("click", function () {
		ajax_request('load_amapi_data')
			.then(response => {
				console.log(response);
				location.reload();
			})
			.catch(error => {
				console.error(error);
			});
	});
});
