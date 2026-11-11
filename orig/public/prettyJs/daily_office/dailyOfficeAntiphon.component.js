(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('antiphon', {
        controllerAs: 'a',
        controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
        var vm = this;
        vm.prefs = (cookieService.get('PREFS'));
        vm.header = (vm.prefs.language == true) ? 'Con el Salmo Invitatorio puede cantarse o decirse una de las siguientes Antífonas' : 'One of the following Antiphons may be sung or said with the Invitatory Psalm';
        vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
        vm.url = (vm.prefs.language == true) ? 'api/daily_office/antiphon_es' : 'api/daily_office/antiphon';
        // $http config object
        var config = {
          method: 'post',
          url: vm.url,
          data: {
            id: $stateParams.do_id,
            wr: $stateParams.which_rite
          }
        }

        dataService
          .getData(config)
          .then(function(response) {
            vm.antiphon = response;
          })
          .catch(function(err) {
            $log.warn(err);
          });
      },
      template: [
        '<p><em>{{a.header}}</em></p>',
        '<p><em>{{a.antiphon.timeframe}}</em><br/>{{a.antiphon.antiphon}}</p>'
      ].join("")
    });
})();
