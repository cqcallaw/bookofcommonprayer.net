(function () {
    'use strict';

    angular.module('bcpApp.daily_office')
    .component('conclusion', {
        controllerAs: "cc",
        controller: function($http,dataService,$log,cookieService) {
          var vm = this;
          vm.prefs = (cookieService.get('PREFS'));
          vm.header = (vm.prefs.language == true) ? 'El Oficiante puede entonces concluir con uno de los siguientes versículos:' : 'Officiant concludes with:';
          vm.url = (vm.prefs.language == true) ? 'api/daily_office/conclusion_es' : 'api/daily_office/conclusion';
          // $http config object
          var config = {
            method: 'post',
            url: vm.url
          }

          dataService
            .getData(config)
            .then(function(response) {
              vm.conclusion = response;
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: [
          "<p><em>{{cc.header}}</em></p>",
          "<p><span ng-bind-html='cc.conclusion.text | amenify'></span><br/>",
          "<em>{{cc.conclusion.reference}}</em></p>"
        ].join("")
    });

})();
