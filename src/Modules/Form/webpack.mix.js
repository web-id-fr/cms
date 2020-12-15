const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

const moduleName = 'form';
const modulePublicPath = 'modules/' + moduleName;
const publicFolderPath = '../../../../../../resources';

mix.copyDirectory(__dirname + '/Resources/js', publicFolderPath + '/' + modulePublicPath + '/js');
