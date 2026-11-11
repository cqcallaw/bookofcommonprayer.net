(function() {
  'use strict';

  angular
    .module('bcpApp.exceptionOverride')
    .factory('$exceptionHandler', exceptionOverride);

  exceptionOverride.$inject = ['$log'];

  function exceptionOverride($log) {
    $log.warn(exception,cause);
    return function(exception, cause) {
      var message = {
        "exception": exception,
        "cause": cause
      }
      return message;
    }
  }
})();
