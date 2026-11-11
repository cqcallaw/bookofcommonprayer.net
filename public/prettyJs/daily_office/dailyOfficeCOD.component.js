(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('collectOfDay', {
          controllerAs: 'cod',
          controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
          var vm = this;
          var wc = ($stateParams.wc) ? $stateParams.wc : null;
          vm.prefs = (cookieService.get('PREFS'));
          vm.collect_header = (vm.prefs.language == true) ? 'Colecta del Día' : 'The Collect of the Day';
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          // $http config object
          var config = {
            method: 'post',
            url: 'api/daily_office/collect_of_day',
            data: {
              id: $stateParams.do_id,
              wr: $stateParams.which_rite,
              wc: wc,
              dow: $stateParams.dow,
              lang: vm.language
            }
          }
          dataService
            .getData(config)
            .then(function(response) {
              vm.collect_of_day = response.cod;
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: [
          '<p><strong><u>{{cod.collect_header}}</u></strong></p>',
          '<strong>{{cod.collect_of_day.name}}</strong><br/>',
          '<span ng-bind-html="cod.collect_of_day.text | amenify | or2br | orthis2br"></span></p>'
        ].join("")
      });

})();
