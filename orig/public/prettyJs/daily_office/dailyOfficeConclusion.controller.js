(function () {
    'use strict';

    angular.module('bcpApp.daily_office').controller('DailyOfficeConclusionCtrl'
    , DailyOfficeConclusionCtrl);

    DailyOfficeConclusionCtrl.$inject = ['$scope','$http','$stateParams'
    ,'dataService','$log'];

    function DailyOfficeConclusionCtrl($scope, $http, $stateParams,dataService
      ,$log) {
      var vm = this;
      // $http config object
      var config = {
        method: 'post',
        url: 'api/daily_office/conclusion',
        data: {
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.conclusion = response;
        })
        .catch(function(err) {
          $log.warn(err);
        });
    }

})();
