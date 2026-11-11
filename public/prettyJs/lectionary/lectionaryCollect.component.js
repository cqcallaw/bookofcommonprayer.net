(function () {
    'use strict';

    angular
      .module('bcpApp.lectionary')
      .component('lectionaryCollect', {
          controllerAs: 'cod',
          controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
          var vm = this;
          var wc = ($stateParams.wc) ? $stateParams.wc : null;
          var wr = ($stateParams.prayer_type == 'C') ? 'rite_ii':'rite_i';
          if( ($stateParams.id == 'CMII' || $stateParams.id == 'CMIII') ) {
            $stateParams.id = 'CM';
          }
          vm.prefs = (cookieService.get('PREFS'));
          vm.collect_header = (vm.prefs.language == true) ? 'Colecta del Día' : 'The Collect of the Day';
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          // $http config object
          var config = {
            method: 'post',
            url: 'api/daily_office/collect_of_day',
            data: {
              id: $stateParams.id,
              wr: wr,
              dow: 0,
              wc: wc,
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
          '<span ng-bind-html="cod.collect_of_day.text | amenify | orthis2br | or2br"></span></p>'
        ].join("")
      });

})();
