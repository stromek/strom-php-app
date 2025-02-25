const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');


module.exports = {
  mode: 'development',
  entry: './srcClient/index.js',
  plugins: [
    new HtmlWebpackPlugin({
      title: "strom-php-app"
    })
  ],
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'public/dist'),
    clean: true,
  },
  devtool: 'inline-source-map',
  devServer: {
    static: {
      directory : './public',
      publicPath : '/public'
    },
  },
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: ['style-loader', 'css-loader'],
      },
      {
        test: /\.s[ac]ss$/i,
        use: ['style-loader', 'css-loader', 'sass-loader'],
      },
      {
        test: /\.(?:js|jsx|mjs|cjs)$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            targets: "defaults",
            cacheDirectory: path.resolve(__dirname, 'tmp'),
            presets: [
              '@babel/preset-env',
              ["@babel/preset-react", {"runtime": "automatic"}]
            ]
          }
        }
      }
    ],
  },
};