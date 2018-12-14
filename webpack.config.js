var Encore = require('@symfony/webpack-encore');
const path = require('path');
const glob = require('glob');
const PurifyCSSPlugin = require('purifycss-webpack');
const purifyCSSParameter = Encore.isProduction() ? '' : '*';


Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.ts')
    // .addEntry('app1', './assets/js/app.js')
    //.addStyleEntry('scss/app', './assets/css/app.scss')
    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')

    .configureBabel(function () {
        return {
            "plugins": [
                ["@babel/plugin-proposal-class-properties", { "loose": true }]
            ],
            "presets": [
                [
                    "@babel/preset-env",
                    {
                        "targets": {
                            ie: "8",
                            edge: "17",
                            firefox: "60",
                            chrome: "67",
                            safari: "11.1",
                        },
                        "useBuiltIns": "entry"
                    }
                ]
            ]
        }
    })

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    // allow TypeScript files to be processed


    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    // .addPlugin(new ExtractTextPlugin('[name].[contenthash].css'))

    // uncomment if you use TypeScript
    // .enableTypeScriptLoader()
    .enableTypeScriptLoader(function (typeScriptConfigOptions) {
        typeScriptConfigOptions.transpileOnly = true;
        typeScriptConfigOptions.configFile = 'tsconfig.json';
    })
    .addPlugin(
        new PurifyCSSPlugin(
            {
                minimize: Encore.isProduction(),
                paths: glob.sync(path.join(__dirname, 'templates/*/*.twig')),
                purifyOptions: {
                    whitelist: [purifyCSSParameter]
                }
            }
        )
    )

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()

// uncomment if you use API Platform Admin (composer req api-admin)
//.enableReactPreset()
//.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();
