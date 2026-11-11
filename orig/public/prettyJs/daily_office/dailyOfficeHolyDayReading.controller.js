(function() {
  'use strict';

  angular
    .module('bcpApp')
    .controller('DailyOfficeHolyDayReadingCtrl', DailyOfficeHolyDayReadingController);

    DailyOfficeHolyDayReadingController.$inject = ['$http','$stateParams','$cookies','dataService','$log'];

    function DailyOfficeHolyDayReadingController($http,$stateParams,$cookies,dataService,$log) {
      var vm = this;

      var config = {
        method: 'post',
        url: 'api/daily_office/holy_day_readings',
        data: {
          hd_id: $stateParams.hd_id,
          hd_time: $stateParams.hd_time
        }
      }

      dataService.getData(config)
        .then(function(response) {
          vm.hdr = response;
        })
        .catch(function(err) {
          $log.warn(err);
        });
    }
})();
