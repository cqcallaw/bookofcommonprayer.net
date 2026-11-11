(function() {
  'use strict';

  angular
    .module('bcpApp.dataService')
    .factory('dataService', dataService);

    dataService.$inject = ['$http','$q'];

    function dataService($http,$q) {
      return {
        getData: getData
      }

      function getData(config) {
        return $http(config)
          .then(returnResponseData)
          .catch(returnErrorStatus);
    }

    function returnResponseData(response) {
      return response.data;
    }

    function returnErrorStatus(response) {
      var message = "Data call to "+response.config.url+" failed";
      if(response.status && response.statusText) {
        message = message + '\n' + response.status + ': ' + response.statusText;
      }
      response = message;
      return $q.reject(response);
    }
  }
})();
