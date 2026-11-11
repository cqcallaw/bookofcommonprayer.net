(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('noondayCollect', {
          controllerAs: 'nc',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/noonday_collect_es' : 'api/daily_office/noonday_collect';
            var config = {
              method: 'post',
              url: vm.url,
              data: {
                wr: $stateParams.which_rite
              }
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.noonday_collect = response[0];
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: '<span ng-bind-html="nc.noonday_collect | amenify"></span></p>'
      });
})();
