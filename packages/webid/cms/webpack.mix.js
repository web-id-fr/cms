const mix = require('laravel-mix');

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

mix.sass('src/resources/sass/language.scss', 'src/public/css')
    .js('src/resources/js/newsletter.js', 'src/public/js')
    .js('src/resources/js/send_form.js', 'src/public/js');

if (mix.inProduction()) {
    mix.options({ uglify: { uglifyOptions: { compress: { drop_console: true, } } } });
} else {
    mix.sourceMaps();
    mix.webpackConfig({
        devtool: "inline-source-map"
    });
}

mix.version();
