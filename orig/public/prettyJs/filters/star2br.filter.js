(function() {
  'use strict';

  angular
    .module('bcpApp.filters')
    .filter('star2br', function() {
      return function(input) {
        if(!input) { return; }
        input = input.replace('*','&nbsp;*<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        return input;
      }
    });
})();
