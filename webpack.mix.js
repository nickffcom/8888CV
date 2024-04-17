const mix = require("laravel-mix");
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
  .js("resources/assets/js/app.js", "public/user/js")
  .js("resources/assets/js/auth.js", "public/user/js")
  .copy("resources/assets/css", "public/user/css")
  .sass("resources/assets/sass/application.scss", "public/user/css")
  .version("public/js", "public/css");

// mix.copy("resources/assets/css", "public/user/css")

