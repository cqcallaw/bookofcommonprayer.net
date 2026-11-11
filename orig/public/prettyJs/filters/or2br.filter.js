(function() {
  'use strict';

  angular
    .module('bcpApp.filters')
    .filter('or2br', function() {
      return function(input) {
        if(!input) { return; }
        return input.replace(new RegExp(/\*or\*/, "g"),'<br/>*or*<br/>');
      }
    });
})();
