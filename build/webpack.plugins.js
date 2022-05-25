const path = require("path");

const webpack = require("webpack");
// const HtmlWebpackPlugin = require("html-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyPlugin = require('copy-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

exports.buildPlugs = () => {
    
    return  [
        new webpack.DefinePlugin({
            VERSION: JSON.stringify('5fa3b9'),
        }),
        // new HtmlWebpackPlugin({
        //     title: 'Skeleton',
        //     // template: './assets/template/index_drop.html',
        //     template: './assets/template/index.html',
        //     filename: 'index.html',
        //     inject: 'head',
        //     minify: false,
        // }),

        new BrowserSyncPlugin(
            {
                // proxy: 'http://application.org/',
                proxy: '192.168.18.18',
                // host: 'application.org',
                host: '192.168.18.18',
                port: 3000,
                open: false,
                files: [
                    {
                        match: [
                            '**/*.php',
                            '**/*.twig',
                        ],
                        // eslint-disable-next-line no-unused-vars
                        fn: function(event, file) {
                            if (event === "change") {
                                const bs = require('browser-sync').get('bs-webpack-plugin');
                                bs.reload();
                            }
                        }
                    }
                ]
            },
            {
                reload: true,
                //reload: false
            }),
        new CopyPlugin({
            patterns:[
                {from: './assets/template/map.php', to: path.resolve(__dirname,'../public') },
                {from: './assets/template/slave.php', to: path.resolve(__dirname,'../public') },
                {from: './assets/template/index.php', to: path.resolve(__dirname,'../public') },
                {from: './assets/template/favicon.ico', to:path.resolve(__dirname,'../public') },
                {from: './assets/template/.htaccess', to: path.resolve(__dirname,'../public') },
            ]
        }),

        new MiniCssExtractPlugin({
            // filename: "./css/[name].[hash].css",
            filename: "./css/[name].min.css",
        }),
    ]
}
