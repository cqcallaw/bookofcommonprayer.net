(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('additionalCollect', {
          controllerAs: 'ac',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/additional_collect_es' : 'api/daily_office/additional_collect';
            // $http config object
            var config = {
              method: 'post',
              url: vm.url,
              data: {
                wp: $stateParams.which_prayer,
                wr: $stateParams.which_rite,
                dow: $stateParams.dow
              }
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.additional_collect = response;
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: ['<p><strong>{{ac.additional_collect.name}}</strong><br/>',
          '<span ng-bind-html="ac.additional_collect.text | amenify"></span></p>'
        ].join("")
      });
})();
