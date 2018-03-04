let mix = require('laravel-mix');

mix.js('js/app.js', 'dist/')
   .sass('scss/app.scss', 'dist/')
   .webpackConfig({
      node: {
          fs: 'empty',
      },
    });
