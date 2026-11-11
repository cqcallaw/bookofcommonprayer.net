(function () {
    'use strict';

    angular.module('bcpApp.daily_office').controller('DailyOfficeCanticleCtrl', DailyOfficeCanticleCtrl);

    DailyOfficeCanticleCtrl.$inject = ['$scope','$http','$stateParams','dataService'];

    function DailyOfficeCanticleCtrl($scope, $http, $stateParams,dataService) {
      // Object {yearNo: "2", do_id: "7R", dow: "0", wc: "0", today_date: "Sunday,_May_08,_2016"}
      var vm = this;

      var config = {
        method: "post",
        url: "api/daily_office/canticles",
        data: {
          year: $stateParams.yearNo,
          id: $stateParams.do_id,
          dow: $stateParams.dow,
          wp: $stateParams.which_prayer,
          wr: $stateParams.which_rite
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.canticles = response;
        })
        .catch(function(err) {
          $log.warn(err)
        });
    }

})();
