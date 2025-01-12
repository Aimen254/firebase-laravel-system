const mix = require('laravel-mix');

// Copy AdminLTE CSS and JS to the public directory
mix.styles([
    'node_modules/admin-lte/dist/css/adminlte.min.css'
], 'public/css/adminlte.css');

mix.scripts([
    'node_modules/admin-lte/dist/js/adminlte.min.js'
], 'public/js/adminlte.js');
