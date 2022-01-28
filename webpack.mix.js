const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/site.js', 'public/js/site.js')
mix.js('resources/js/lve.js', 'public/js/lve.js')
mix.js('resources/js/slider.js', 'public/js/slider.js')

mix.postCss('resources/css/tailwind.css', 'public/css/site.css', [
    require('tailwindcss'),
])

if (mix.inProduction()) {
    mix.version();
}
