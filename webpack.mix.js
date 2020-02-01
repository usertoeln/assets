const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js').extract(['vue', 'axios', 'bootstrap', 'jquery', 'bootstrap-vue', 'vue-router']);
// .sass('resources/sass/app.scss', 'public/css');
if (mix.inProduction()) {
    mix.version();
}