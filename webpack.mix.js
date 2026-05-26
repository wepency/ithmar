const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .vue({ version: 3 })
    .webpackConfig({
        plugins: [
            new (require('webpack')).DefinePlugin({
                '__VUE_OPTIONS_API__': true,
                '__VUE_PROD_DEVTOOLS__': false,
                '__VUE_PROD_HYDRATION_MISMATCH_DETAILS__': false,
            }),
        ],
    })
    .sass('resources/sass/app.scss', 'public/css', {
        sassOptions: {
            silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'legacy-js-api'],
            quietDeps: true,
        },
    });
