/**
 * Controls the oik-block build
 * 
 * All of the blocks are built into the one package.
 * All the frontend CSS is built into one file.
 * The Editor CSS is built into another.
 * 
 * @copyright (C) Copyright Bobbing Wide 2018
 * @author Herb Miller @bobbingwide
 */
const path = require( 'path' );
const webpack = require( 'webpack' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
// const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );

// Set different CSS extraction for editor only and common block styles
const blocksCSSPlugin = new ExtractTextPlugin( {
  filename: './blocks/build/css/blocks.style.css',
} );
const editBlocksCSSPlugin = new ExtractTextPlugin( {
  filename: './blocks/build/css/blocks.editor.css',
} );

// Configuration for the ExtractTextPlugin.
const extractConfig = {
  use: [
    { loader: 'raw-loader' },
    {
      loader: 'postcss-loader',
      options: {
        plugins: [ require( 'autoprefixer' ) ],
      },
    },
    {
      loader: 'sass-loader',
			query: {
				outputStyle:
					'production' === process.env.NODE_ENV ? 'compressed' : 'nested',
			},
    },
  ],
};

module.exports = {
  entry: {
    './blocks/build/js/editor.blocks' : './blocks/index.js',
    //'./blocks/build/js/frontend.blocks' : './blocks/frontend.js',
  },
  output: {
    path: path.resolve( __dirname ),
    filename: '[name].js',
  },
  watch: false,
  devtool: 'cheap-eval-source-map',
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
        },
      },
      {
        test: /style\.s?css$/,
        use: blocksCSSPlugin.extract( extractConfig ),
      },
      {
        test: /editor\.s?css$/,
        use: editBlocksCSSPlugin.extract( extractConfig ),
      },
    ],
  },
  plugins: [
    blocksCSSPlugin,
    editBlocksCSSPlugin,
    // new BrowserSyncPlugin({
    //   // Load localhost:3333 to view proxied site
    //   host: 'localhost',
    //   port: '3333',
    //   // Change proxy to your local WordPress URL
    //   proxy: 'https://gutenberg.local'
    // })
  ],
};
