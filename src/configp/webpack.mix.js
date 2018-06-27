let mix = require('laravel-mix');

mix.sass("resources/assets/honey-comb/sass/honey-comb.scss", "public/css/hc-admin-panel.css").options({
    processCssUrls: false
});
sdfsdfsdfdsf
mix.react('resources/assets/honey-comb/components/full.js', 'public/js/hc-full.js');

mix.version();
