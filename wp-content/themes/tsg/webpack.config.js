const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const BrowserSyncPlugin = require('browsersync-webpack-plugin');
const WebpackAssetsManifest = require('webpack-assets-manifest');

const isProd = process.env.NODE_ENV === 'production';

let config = {
	entry: {
		app: './src/index.js'
	},
	output: {
		filename: isProd ? 'js/[hash].[name].js' : 'js/[name].js',
		path: path.resolve(__dirname, 'dist'),
		publicPath: isProd ? '/wp-content/themes/tsg/dist/' : 'http://localhost:3000/wp-content/themes/tsg/dist/',
		pathinfo: !isProd
	},
	devtool: isProd ? false : '#cheap-module-source-map',
	stats: isProd,
	module: {
		rules: [
			{
				test: /\.(s*)css$/,
				use: ExtractTextPlugin.extract({
					fallback: 'style-loader',
					use: [
						{
							loader: 'css-loader',
							options: {
								modules: true,
								sourceMap: !isProd,
								camelCase: true,
								minimize: isProd,
								importLoaders: 1,
								localIdentName: isProd ? '[hash:base64:5]' : '[name]-[local]-[hash:base64:5]',
								discardComments: {
									removeAll: true
								}
							}
						},
						{
							loader: 'postcss-loader'
						},
						{
							loader: 'sass-loader'
						}
					]
				})
			},
			{
				test: /\.js$/,
				exclude: /(node_modules|bower_components)/,
				loader: 'babel-loader'
			},
			{
				test: /\.(png|jpg|gif|svg|ico)$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: './img/[name].[ext]'
						}
					}
				],
				include: path.resolve('./src/img')
			},
			{
				test: /\.(ttf|eot|woff|woff2|svg)$/,
				use: {
					loader: 'file-loader',
					options: {
						name: 'fonts/[name].[ext]'
					}
				},
				include: [path.resolve('./src/css'), path.resolve('./src/fonts')],
				exclude: path.resolve('./src/img')
			}
		]
	},
	plugins: [
		new webpack.DefinePlugin({
			'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development'),
			API_URL: JSON.stringify(isProd ? 'https://tsg.d3applications/wp-json' : 'http://tsg.info/wp-json')
		}),
		new ExtractTextPlugin({
			// Options similar to the same options in webpackOptions.output
			// both options are optional
			filename: isProd ? 'css/[name].[hash].css' : 'css/[name].css',
			allChunks: true,
			disable: !isProd
		}),
		new WebpackAssetsManifest({
			output: 'assets.json',
			space: 2,
			writeToDisk: false,
			publicPath: isProd ? '/wp-content/themes/tsg/dist/' : 'http://localhost:3000/wp-content/themes/tsg/dist/'
		})
	]
};

if (!isProd) {
	config.entry = addHot(config.entry);
	config.plugins = config.plugins.concat([
		new webpack.optimize.OccurrenceOrderPlugin(),
		new webpack.HotModuleReplacementPlugin(),
		new webpack.NoEmitOnErrorsPlugin(),
		new BrowserSyncPlugin({
			target: 'http://tsg.info',
			open: true,
			proxyUrl: 'http://localhost:3000',
			watch: ['**/*.php'],
			delay: 500
		})
	]);
}

// If true JS and CSS files will be minified
if (isProd) {
	config.plugins.push(new UglifyJSPlugin());
}

module.exports = config;

function addHot(entry) {
	const results = {};

	Object.keys(entry).forEach(name => {
		results[name] = Array.isArray(entry[name]) ? entry[name].slice(0) : [entry[name]];
		results[name].unshift(`${__dirname}/hmr.js`);
	});
	return results;
}
