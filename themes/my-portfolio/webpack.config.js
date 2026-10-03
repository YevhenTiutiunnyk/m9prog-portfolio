const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const outputPath = path.resolve(__dirname, 'dist');
const bootstrapSvgWarningPattern = /postcss-svgo: .*SvgoParserError: <input>:1:1: Non-whitespace before first tag\./;

const sharedRules = [
    {
        test: /\.s?css$/i,
        use: [
            MiniCssExtractPlugin.loader,
            'css-loader',
            {
                loader: 'sass-loader',
                options: {
                    sassOptions: {
                        quietDeps: true,
                        silenceDeprecations: ['import', 'global-builtin'],
                    },
                },
            },
        ],
    },
    {
        test: /\.m?js$/i,
        exclude: /node_modules/,
        type: 'javascript/auto',
    },
];

module.exports = (env, argv) => {
    const isDev = argv.mode === 'development';

    const rawConfig = {
        name: 'raw',
        mode: isDev ? 'development' : 'none',
        devtool: false,
        entry: {
            'js/main':   './src/js/main.js',
            'css/main':  './src/scss/main.scss',
        },
        output: {
            path: outputPath,
            filename: '[name].js',
        },
        module: { rules: sharedRules },
        plugins: [
            new RemoveEmptyScriptsPlugin(),
            new MiniCssExtractPlugin({ filename: '[name].css' }),
            new CopyWebpackPlugin({
                patterns: [
                    {
                        from: 'src/images',
                        to: 'images',
                        noErrorOnMissing: true,
                        globOptions: {
                            ignore: ['**/*.zip', '**/*.psd', '**/*.ai'],
                        },
                    },
                    {
                        from: 'src/fonts',
                        to: 'fonts',
                        noErrorOnMissing: true,
                        globOptions: {
                            ignore: ['**/*.zip', '**/*.psd', '**/*.ai'],
                        },
                    },
                ],
            }),
        ],
        optimization: { minimize: false },
    };

    const minifiedConfig = {
        name: 'minified',
        mode: 'production',
        devtool: false,
        entry: {
            'js/main.min':   './src/js/main.js',
            'css/main.min':  './src/scss/main.scss',
        },
        output: {
            path: outputPath,
            filename: '[name].js',
        },
        module: { rules: sharedRules },
        plugins: [
            new RemoveEmptyScriptsPlugin(),
            new MiniCssExtractPlugin({ filename: '[name].css' }),
            new CopyWebpackPlugin({
                patterns: [
                    {
                        from: 'src/images',
                        to: 'images',
                        noErrorOnMissing: true,
                        globOptions: {
                            ignore: ['**/*.zip', '**/*.psd', '**/*.ai'],
                        },
                    },
                    {
                        from: 'src/fonts',
                        to: 'fonts',
                        noErrorOnMissing: true,
                        globOptions: {
                            ignore: ['**/*.zip', '**/*.psd', '**/*.ai'],
                        },
                    },
                ],
            }),
        ],
        optimization: {
            minimize: true,
            minimizer: [
                '...',
                new CssMinimizerPlugin({
                    warningsFilter: (warning) => !bootstrapSvgWarningPattern.test(String(warning)),
                }),
            ],
        },
    };

    return [rawConfig, minifiedConfig];
};
