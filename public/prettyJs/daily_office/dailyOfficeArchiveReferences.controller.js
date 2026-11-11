(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficeArchiveReferenceCtrl', DailyOfficeArchiveReferenceCtrl);

    DailyOfficeArchiveReferenceCtrl.$inject = ['$scope','$http','$location','dataService','$log','$stateParams'];

    function DailyOfficeArchiveReferenceCtrl($scope, $http, $location, dataService, $log, $stateParams) {
      var vm = this;
      vm.year = $stateParams.year;
      vm.nextYear = new Date().getFullYear()+1;//vm.thisYear + 1;

      var config = {
          method: "post",
          url: "api/daily_office/references",
          data: {
              year: (vm.year) ? vm.year : null
          }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.references = response;
        })
        .catch(function(err) {
          $log.warn(err)
        });
      }
})();
