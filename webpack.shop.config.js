const webpack = require('webpack')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const path = require('path')

const {
  NODE_ENV
} = process.env

/* -----------------------------------------------------------------------------
 * Plugins
 * -------------------------------------------------------------------------- */

const uglifyJs = new webpack.optimize.UglifyJsPlugin({
  output: {
    beautify: NODE_ENV == 'development',
    comments: NODE_ENV == 'development'
  },
  mangle: NODE_ENV == 'production'
})

const provide = new webpack.ProvidePlugin({
  $: 'jquery',
  jQuery: 'jquery',
  _: 'lodash'
})

const extractText = new ExtractTextPlugin(
  'application.css.liquid'
)

/* -----------------------------------------------------------------------------
 * Modules
 * -------------------------------------------------------------------------- */

const extractTextLoader = ExtractTextPlugin.extract({
  use: [{
    loader: 'css-loader',
    options: {
      minimize: NODE_ENV == 'production'
    }    
  }, {
    loader: 'postcss-loader'
  }, {
    loader: 'sass-loader',
    options: {
      outputStyle: NODE_ENV == 'production' ? 'compressed' : null,
      includePaths: [
        'node_modules/slick-carousel/slick',
        'node_modules/nprogress'
      ]
    }
  }]
})

/* -----------------------------------------------------------------------------
 * Main
 * -------------------------------------------------------------------------- */

module.exports = {
  entry: './source/js/shop/index.js',
  output: {
    filename: 'application.js',
    path: path.resolve(__dirname, 'shop/assets')
  },
  plugins: [
    uglifyJs,
    provide,
    extractText
  ],
  module: {
    rules: [{
      test: /\.scss$/,
      use: extractTextLoader
    }, {
      test: /\.modernizrrc.js$/,
      use: 'modernizr-loader'
    }, {
      test: /\.modernizrrc(\.json)?$/,
      use: [ 'modernizr-loader', 'json-loader' ]
    }, {
      test: /\.js$/,
      exclude: /(node_modules|bower_components)/,
      use: {
        loader: 'babel-loader',
        options: {
          presets: ['@babel/preset-env']
        }
      }
    }]
  },
  resolve: {
    alias: {
      modernizr$: path.resolve(__dirname, '.modernizrrc'),
      'application.scss': path.resolve(__dirname, 'source/scss/shop/index.scss')
    },
    modules: [
      path.resolve(__dirname, 'source/js'),
      path.resolve(__dirname, 'source/css'),
      'node_modules'
    ]
  },
  watch: NODE_ENV == 'development',
  watchOptions: {
    ignored: /node_modules/
  }
}
