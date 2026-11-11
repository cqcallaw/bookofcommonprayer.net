(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('prayerForMission', {
        bindings: {
          $stateParams: '<'
        },
        controllerAs: "pm",
        controller: function($stateParams,dataService,$log,cookieService) {
          var vm = this;
          vm.prefs = (cookieService.get('PREFS'));
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          vm.instr = (vm.prefs.language == true)
              ? "Aquí se añade una de las siguientes oraciones por la misión de la\n" +
              "Iglesia a menos que siga la Eucaristía o una forma de intercesión\n" +
              "general."
              : "Then, unless the Eucharist or a form of general intercession is to follow, the following prayer for mission is added.";
          vm.header = (vm.prefs.language == true) ? "Oración por la Misión" : "Prayer for Mission";
          vm.url = (vm.prefs.language == true) ? 'api/daily_office/prayer_for_mission_es' : 'api/daily_office/prayer_for_mission';
          // $http config object
          var config = {
            method: 'post',
            url: vm.url,
            data: {
              wr: $stateParams.which_rite,
              wp: $stateParams.which_prayer
            }
          }

          dataService
            .getData(config)
            .then(function(data) {
              vm.pm_text = data[0];
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: ["<p><em>{{pm.instr}}</em></p>",
"<strong>{{pm.header}}</strong><br/>",
"<span ng-bind-html='pm.pm_text | amenify'></span></p>"].join("")
      });
})();
