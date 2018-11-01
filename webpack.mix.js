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
  .styles([
    'resources/admin/icons/icomoon/styles.css',
    'resources/admin/css/bootstrap.min.css',
    'resources/admin/css/bootstrap_limitless.min.css',
    'resources/admin/css/layout.min.css',
    'resources/admin/css/components.min.css',
    'resources/admin/css/colors.min.css'
  ], 'public/assets/admin/css/app.css')
  .js([
    'resources/admin/js/jquery.min.js',
    'resources/admin/js/bootstrap.bundle.min.js',
    'resources/admin/js/blockui.min.js',
    'resources/admin/js/ripple.min.js',
    'resources/admin/js/app.js',
    'resources/js/app.js',
  ], 'public/assets/admin/js/core.js')
  .extract(['vue'])
  .copyDirectory('resources/admin/icons/icomoon/fonts', 'public/assets/admin/css/fonts')
  .copyDirectory('resources/admin/images', 'public/assets/admin/images')
  .webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                'window.jQuery': 'jquery',
            })
        ]
    };
});
