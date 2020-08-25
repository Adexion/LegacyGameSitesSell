let Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('paymentStatistic', './assets/js/panel/paymentStatistic.js')
    .addEntry('shopStatistic', './assets/js/panel/shopStatistic.js')

    .addStyleEntry('articleStyle', './assets/css/articleStyle.scss')
    .addStyleEntry('ruleStyle', './assets/css/ruleStyle.scss')

    .addEntry('app', './assets/js/app/app.ts')

    .addStyleEntry('main', './assets/css/main.scss')
    .addStyleEntry('nav', './assets/css/nav.scss')
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
