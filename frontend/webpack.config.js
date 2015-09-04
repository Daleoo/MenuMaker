module.exports = {
    debug: true,
    entry: {
        app: ["webpack/hot/dev-server", "./MenuMaker.js"]
    },
    output: {
        path: '../public/js',
        filename: 'MenuMaker.js'
    },
    module: {
        loaders: [
            {test: /\.js?$/, exclude: /node_modules/,loader: 'babel'},
            {
                test: /\.jsx?$/,
                exclude: new RegExp('node_modules|bower_components'),
                loader: 'react-hot!babel'
            }
        ]
    },
    devServer: {
        inline: true,
        progress: true,
        host: 'localhost',
        port: 8080,
        contentBase: '../public',
        publicPath: '/'
    }
};
