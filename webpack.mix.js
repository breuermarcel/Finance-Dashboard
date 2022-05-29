const mix = require('laravel-mix');

mix
    .sass('resources/sass/login.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css');