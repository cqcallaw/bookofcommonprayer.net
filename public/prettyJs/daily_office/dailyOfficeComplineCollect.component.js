(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('complineCollect', {
          controllerAs: 'cc',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/compline_collect_es' : 'api/daily_office/compline_collect';
            var config = {
              method: 'post',
              url: vm.url
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.compline_collect = response[0];
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: '<p><span ng-bind-html="cc.compline_collect | amenify"></span></p>'
      });
})();
