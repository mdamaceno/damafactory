let mix = require('laravel-mix');

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

mix
  .js('node_modules/jquery/dist/jquery.slim.js', 'public/js')
  .js('node_modules/popper.js/dist/umd/popper.js', 'public/js')
  .js('node_modules/bootstrap/dist/js/bootstrap.js', 'public/js')
  .js('resources/js/app.js', 'public/js')
  .css('node_modules/bootstrap/dist/css/bootstrap.css', 'public/css')
  .sass('resources/sass/app.scss', 'public/css');
