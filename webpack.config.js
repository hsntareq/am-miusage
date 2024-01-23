const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		'ampi_script': [
			'./src/js/ajax_request.js',
			'./src/js/lib.js',
		],
		'ampi_block': ['./src/js/block.js'],
		'ampi_style': ['./src/scss/plugin.scss'],
	},
	/* output: {
		...defaultConfig.output,
		path: path.resolve(__dirname, 'assets'),
	}, */
};
