const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		'amapi-scripts': [
			'./src/js/amapi-scripts.js',
			'./src/js/lib.js',
		],
		'amapi-styles': ['./src/scss/plugin.scss'],
	},
};
