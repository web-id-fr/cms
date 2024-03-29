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

mix.sass('src/resources/sass/language.scss', 'src/public/cms/css')
    .sass('src/resources/sass/override_nova.scss', 'src/public/cms/css');

if (mix.inProduction()) {
    mix.options({ uglify: { uglifyOptions: { compress: { drop_console: true, } } } });
} else {
    mix.sourceMaps();
    mix.webpackConfig({
        devtool: "inline-source-map"
    });
}

mix.version();
