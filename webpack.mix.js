let mix = require('laravel-mix');
let path = require('path');

mix.alias({
    'laravel-nova$': path.join(__dirname, 'vendor/laravel/nova/resources/js/mixins/packages.js'),
    '@': path.join(__dirname, 'vendor/laravel/nova/resources/js')
});

require('./mix');

mix.setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .vue({ version: 3 })
    .css('resources/css/field.css', 'css')
    .nova('kirschbaum-development/inline-select');
