const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');


const optDevServer = {
  headers : {
    'Access-Control-Allow-Origin' : '*'
  },
  historyApiFallback: {
    rewrites: [
      {
        // Odchytenme /public/* URL a vratime ji zase zpět
        from: /^\/public\//,
        to: function (context){
          return '/public/'+context.parsedUrl.pathname.replace(/^\/public\//, '')
        }
      }
    ],
  },
  static: {
    directory : './public',
    publicPath : '/public'
  }
}


const optModule = {
  rules: [
    {
      test: /\.html$/,
      loader: 'html-loader'
    },
    {
      test: /\.css$/i,
      use: ['style-loader', 'css-loader'],
    },
    {
      test: /\.s[ac]ss$/i,
      use: ['style-loader', 'css-loader', 'sass-loader'],
    },
    {
      test: /\.ya?ml$/,
      use: 'yaml-loader'
    },
    {
      test: /\.svg$/,
      loader: 'svg-inline-loader'
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
}


module.exports = [
  {
    name: 'snippet',
    mode: 'development',
    entry: './srcClient/snippet/index.js',
    output: {
      filename: 'snippet.js',
      path: path.resolve(__dirname, 'public/app/snippet'),
      clean: true,
    },
    plugins: [
      new HtmlWebpackPlugin({
        title: "stromcom-snippet",
        publicPath : '/',
        template: './srcClient/snippet/index.html'
      })
    ],
    devtool: 'inline-source-map',
    devServer: Object.assign({}, optDevServer, {
      port: 8080,
    }),
    module: optModule
  },

  
  {
    name: 'client',
    mode: 'development',
    entry: './srcClient/client/client.js',
    output: {
      filename: 'client.js',
      path: path.resolve(__dirname, 'public/app/client'),
      clean: true,
    },
    plugins: [
      new HtmlWebpackPlugin({
        title: "stromcom-thread",
        publicPath : '/'
      })
    ],
    devtool: 'inline-source-map',
    devServer: Object.assign({}, optDevServer, {
      port: 8081
    }),
    module: optModule
  }
]