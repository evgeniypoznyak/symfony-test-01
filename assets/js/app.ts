/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// add extensions for typescript.
// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');

interface Test {
  justString: string;
}

class Best implements Test{
   static test = '123';
    justString: string = 'poop';
}

const test = Best.test;
const test2 = new Best();
alert(test);
alert(test2.justString);

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');