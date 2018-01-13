let mix = require('laravel-mix');

mix.copy('node_modules/font-awesome/fonts/fontawesome-webfont.woff2', 'public/fonts/fontawesome-webfont.woff2');

mix.sass(
    "resources/assets/honey-comb/sass/honey-comb.scss",

    "public/css/hc-admin-panel.css").version();

mix.react('resources/assets/honey-comb/components/full.js', 'public/js/hc-full.js').version();

mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js').version();