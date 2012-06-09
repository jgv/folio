var assetPath = '../assets/javascripts/';

module.exports = function(grunt) {


  // Project configuration.
  grunt.initConfig({
    pkg: '<json:../package.json>',
    meta: {
      banner: "/*! <%= pkg.name %> - v<%= pkg.version %> -" +
        "Built <%= grunt.template.today('mm-dd-yyyy') %> */"
    },
    lint: {
      all: assetPath + 'folio.js',
    },
    min: {
      dist: {
        src: [
          assetPath + 'json2.js',
          assetPath + 'underscore.js',
          assetPath + 'backbone.js',
          assetPath + 'backbone-localstorage.js',
          assetPath + 'backbone-support/support.js',
          assetPath + 'backbone-support/composite_view.js',
          assetPath + 'backbone-support/swapping_router.js',
          assetPath + 'icanhaz.js',
          assetPath + 'jquery.cycle.lite.js',
          '<banner>',
          assetPath + 'folio.js'
        ],
        dest: '../assets/javascripts/production.js'
      }
    },
    jshint: {
      options: {
        boss:true,
        browser: true,
        eqeqeq: false        
      },
      globals: {
        jQuery: true
      }
    }
  });

  grunt.registerTask('default', 'lint min');

};
