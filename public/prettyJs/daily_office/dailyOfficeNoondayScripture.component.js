(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('noondayScripture', {
          controllerAs: 'ns',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/noonday_scripture_es' : 'api/daily_office/noonday_scripture';
            var config = {
              method: 'post',
              url: vm.url
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.noonday_scripture = response;
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: [
            '<p>{{ns.noonday_scripture.text}}',
            '<br/><em>{{ns.noonday_scripture.reference}}</em></p>'
          ].join("")
      });
})();
