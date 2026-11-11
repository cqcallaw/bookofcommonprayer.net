(function() {
  'use strict';

  angular
    .module('bcpApp.filters')
    .filter('orthis2br', function() {
      return function(input) {
        if(!input) { return; }
        return input.replace(new RegExp(/\*or this\*/, "g"),'<br/>*or*<br/>');
      }
    });
})();
