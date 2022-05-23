
const entries = require('./wconfig/webpack.entries.js')
const output = require('./wconfig/webpack.output.js')
const plugIns = require('./wconfig/webpack.plugins.js')
const rules = require('./wconfig/webpack.rules.js')
const optimize = require('./wconfig/webpack.optimize.js')

let config = {
    entry: entries.baseCode(),
    output: output.webRoot(),
    resolve: {
        extensions: ['.js','.jsx','.ts','.vue','.scss','.json'],
        modules: ['node_modules'],
    },
    module: {
        rules: rules.filters(),
    },
    plugins: plugIns.buildPlugs(),

    optimization: {},
    
    devServer: {

        port: 3000,
        compress: true,
        hot: true,
        allowedHosts: [
            'all',
        ],
        headers: {
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, PATCH, OPTIONS",
            "Access-Control-Allow-Headers": "X-Requested-With, content-type, Authorization"
            }
    },
    performance: {
        hints: false
    }
  };


module.exports = ( env, argv ) => {
    if (argv.mode === 'development') {
        config.devtool = 'source-map';
        console.log("Running on Developer Mode "  );
    }
    if (argv.mode === 'production') {
        config.optimization =  optimize.optimizer();
        console.log("Enabled Production Plugins ");
    }
    return config;
  };

  

