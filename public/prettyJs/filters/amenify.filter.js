(function() {
  'use strict';

  angular
    .module('bcpApp.filters')
    // amenify filter, which converts the input to html
    .filter('amenify', function() {
      return function(input) {
        // the filter gets processed before the promise returns
        // so we need to make sure it gets returned unless input is defined
        if(!input) { return; }
        input = input.replace(new RegExp(/(=Amen.=)/,"g"),"<em><strong>Amen</strong></em>");
        return input;
      }
    });
})();
