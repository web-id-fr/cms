const mix = require('laravel-mix');

mix.setPublicPath('dist')
    .js('Resources/js/newsletter.js', 'js');
