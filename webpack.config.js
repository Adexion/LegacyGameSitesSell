let Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('paymentStatistic', './assets/js/panel/paymentStatistic.js')
    .addEntry('shopStatistic', './assets/js/panel/shopStatistic.js')

    .addEntry('app', './assets/js/app/app.ts')

    .addStyleEntry('main', './assets/css/base/main.scss')
    .addStyleEntry('main-1.0', './assets/css/1.0/main.scss')
    .addStyleEntry('main-2.0', './assets/css/2.0/main.scss')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    .enableTypeScriptLoader()

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    });

module.exports = Encore.getWebpackConfig();
