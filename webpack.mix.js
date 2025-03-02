const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications()
	.js('resources/js/app.js', 'public/js')
	.sass('resources/sass/map.scss', 'public/css')
	.less('resources/less/app.less', 'public/css')
	.options({
		postCss: [
			tailwindcss({
				purge: false
			}),
		]
	})

if (mix.inProduction()) {
	mix.version();
}