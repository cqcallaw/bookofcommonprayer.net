(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('complineScripture', {
          controllerAs: 'cs',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/compline_scripture_es' : 'api/daily_office/compline_scripture';
            var config = {
              method: 'post',
              url: vm.url
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.compline_scripture = response;
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: [
            '<p>{{cs.compline_scripture.text}}',
            '<br/><em>{{cs.compline_scripture.reference}}</em></p>'
          ].join("")
      });
})();
