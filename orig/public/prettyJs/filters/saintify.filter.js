(function() {
  'use strict';
//Not in use.  Delete
  angular
    .module('bcpApp.filters')
    .filter('saintify', function() {
      return function(input) {
        // the filter gets processed before the promise returns
        // so we need to make sure it gets returned unless input is defined
        if(!input) { return; }
        //var re = new RegExp(/(\w+)?/);
        input = input.replace(/\W+/,'');
        return input;
      }
    });
})();
