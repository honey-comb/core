let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts(
    [
        "node_modules/jquery/dist/jquery.js",
        "node_modules/bootstrap/dist/js/bootstrap.js",
        "node_modules/admin-lte/dist/js/adminlte.js",
    ],
    'public/js/external.js').version();

mix.scripts(
    [
        "resources/assets/honey-comb/js/shared/hc-helpers.js",
        "resources/assets/honey-comb/js/shared/hc-functions.js",
        "resources/assets/honey-comb/js/shared/hc-objects.js",
        "resources/assets/honey-comb/js/shared/hc-loader.js",
        "resources/assets/honey-comb/js/shared/hc-service.js"
    ],
    "public/js/hc-shared.js").version();

mix.scripts(
    [
        "resources/assets/honey-comb/js/form/hc-form-manager.js",
        "resources/assets/honey-comb/js/form/hc-form.js",
        "resources/assets/honey-comb/js/form/hc-form-basic-field.js",
        "resources/assets/honey-comb/js/form/hc-form-button.js",
        "resources/assets/honey-comb/js/form/hc-form-single-line.js",
        "resources/assets/honey-comb/js/form/hc-form-email.js",
        "resources/assets/honey-comb/js/form/hc-form-password.js",
        "resources/assets/honey-comb/js/form/hc-form-date-time-picker.js",
        "resources/assets/honey-comb/js/form/hc-form-text-area.js",
        "resources/assets/honey-comb/js/form/hc-form-rich-text-area.js",
        "resources/assets/honey-comb/js/form/hc-form-check-box-list.js",
        "resources/assets/honey-comb/js/form/hc-form-radio-list.js",
        "resources/assets/honey-comb/js/form/hc-form-drop-down-list.js",
        "resources/assets/honey-comb/js/form/hc-form-upload-file.js",
        "resources/assets/honey-comb/js/form/hc-form-google-map.js",
        "resources/assets/honey-comb/js/popup/hc-popup.js"
    ],
    "public/js/hc-form.js").version();


mix.copy('node_modules/font-awesome/fonts/fontawesome-webfont.woff2', 'public/fonts/fontawesome-webfont.woff2');

mix.sass(
    "resources/assets/honey-comb/sass/honey-comb.scss",

    "public/css/hc-admin-panel.css").version();

mix.react('resources/assets/honey-comb/react/HCAdminList.js', 'public/js').version();