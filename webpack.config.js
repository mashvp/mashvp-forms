/* eslint-disable no-undef */

const path = require('path');

const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
  entry: path.resolve(__dirname, 'js/index.js'),

  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'index.min.js',
    chunkFilename: 'chunks/[id]-[chunkhash].chunk.js',
    publicPath: process.env.WEBPACK_MODE
      ? '/wp-content/plugins/mashvp-forms/dist/'
      : '/sps/wp-content/plugins/mashvp-forms/dist/',
  },

  devtool: 'eval-source-map',

  plugins: [
    new TerserPlugin({
      extractComments: false,
    }),
  ],

  module: {
    rules: [
      {
        test: /\.js$/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
          },
        },
        exclude: [/core-js/],
      },
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
        },
      },
    ],
  },

  resolve: {
    extensions: ['.js', '.jsx'],
  },
};
