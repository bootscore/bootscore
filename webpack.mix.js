const mix = require('laravel-mix');

mix.sass('css/scss/style.scss', './');

mix.setResourceRoot('./'); //for fix address font