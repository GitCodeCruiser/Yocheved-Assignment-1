const mix = require("laravel-mix");
const webpack = require('webpack');
var path = require("path");
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

mix.js("resources/js/app.js", "public/js")
    .vue()
    .postCss('resources/css/app.css', 'public/css')
    .postCss('resources/css/custom.css', 'public/css')
    

mix.webpackConfig({
    output: {
        publicPath: "/public/", // production
        chunkFilename: `js/[name].js?id=[chunkhash]`,
    },

    plugins: [
        new webpack.DefinePlugin({
        'process.env': {
        MIX_API_BASE_URL: JSON.stringify(process.env.MIX_API_BASE_URL),
        },
    }),
    ],
    
    resolve: {
        enforceExtension: false,
        alias: {
            Vue$: "vue/dist/vue.runtime.esm.js",
            Vue: "vue/dist/vue.esm-bundler.js",
            "@asset": path.resolve(__dirname, "./public/assets"),
        },
    },
});