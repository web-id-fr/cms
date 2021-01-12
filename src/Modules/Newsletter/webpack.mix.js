const mix = require('laravel-mix');

mix.setPublicPath('Resources/dist')
    .js('Resources/js/newsletter.js', 'js');
