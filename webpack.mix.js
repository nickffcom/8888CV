const mix = require("laravel-mix");
const ESLintPlugin = require("eslint-webpack-plugin");
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
  output: {
    filename: "[name].js",
    chunkFilename: "js/chunks/[name].js",
  },
  plugins: [
  ],
});

mix
  .vue({
    extractStyles: false,
    globalStyles: "./resources/assets/sass/common/global.scss",
  })
  .js("resources/assets/js/app.js", "public/js")
  .js("resources/assets/js/auth.js", "public/js")
  .sass("resources/assets/sass/application.scss", "public/css")
  .version("public/js", "public/css");
// .sourceMaps()
