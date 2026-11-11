(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficeOptionsCtrl', DailyOfficeOptionsCtrl);

    DailyOfficeOptionsCtrl.$inject = ['$scope','$http','dataService','$cookies'
      ,'$log','cookieService'];

    function DailyOfficeOptionsCtrl($scope,$http,dataService,$cookies,$log,cookieService) {
      var vm = this;

      vm.prefs = (cookieService.get('PREFS'));
      vm.timezone = (vm.prefs.timezone) ? vm.prefs.timezone : null;
      vm.language = (vm.prefs.language == true) ? "ES" : "EN";

      var config = {
        method: 'post',
        url: 'api/daily_office',
        data: {
          tz: vm.timezone
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.url_date = response.CC.today.replace(/\s/g,"_");
          vm.daily_office = response;
          //$cookies.put('CC',JSON.stringify(response.CC));
        })
        .catch(function(err) {
          $log.warn(err);
        });
    };

})();
