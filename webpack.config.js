const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		'amapi_script': [
			'./src/js/ajax_request.js',
			'./src/js/lib.js',
		],
		// 'amapi_block': ['./src/js/block.js'],
		'amapi_style': ['./src/scss/plugin.scss'],
	},
};
