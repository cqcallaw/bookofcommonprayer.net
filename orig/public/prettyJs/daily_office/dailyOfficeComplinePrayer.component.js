(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('complinePrayer', {
          controllerAs: 'cp',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/compline_prayer_es' : 'api/daily_office/compline_prayer';
            var config = {
              method: 'post',
              url: vm.url
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.compline_prayer = response[0];
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: '<p><span ng-bind-html="cp.compline_prayer | amenify"></span></p>'
      });
})();
