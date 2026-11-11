(function () {
    'use strict';

    angular
      .module('bcpApp.lectionary')
      .controller('LectionaryByDateCtrl', LectionaryByDateCtrl);

    LectionaryByDateCtrl.$inject = ['$scope','$http','dataService'
      ,'$cookies','$log','$location','cookieService','$stateParams'];

    function LectionaryByDateCtrl($scope,$http,dataService,$cookies
        ,$log,$location,cookieService,$stateParams) {
      var vm = this;
      // 1) cookieService returns a merged object of default values or the preferences
      // set on the preferences page
      vm.prefs = (cookieService.get('PREFS'));
      vm.readings_page = (vm.prefs.language == true) ? "readings_es" : "readings";
      vm.language = (vm.prefs.language == true) ? "ES" : "EN";
      // the slideSwitch in the preferences page requires true/false settings, so we
      // need to look for the two items we need here and set it accordingly
      vm.lpt = (vm.prefs.lectionaryPrayerType == true) ? 'T':'C';
      vm.prefs.lectionaryPrayerType = (vm.prefs.lectionaryPrayerType == true) ? 'Traditional' : 'Contemporary';
      vm.lt = (vm.prefs.lectionaryType == true) ? 'BCP':'RCL';
      if (vm.language == 'EN') {
          vm.prefs.lectionaryType = (vm.prefs.lectionaryType == true) ? 'BCP (1979)' : 'Revised Common Lectionary';
      } else {
          vm.prefs.lectionaryType = (vm.prefs.lectionaryType == true) ? '1979 Libro de Oración' : 'Leccionario Común Revisado';
      }
      // 2) get the CC, DO, and DOR data with one call
      var year = $stateParams.year ? $stateParams.year : null;
      var config = {
        method: 'post',
        url: 'api/lectionary',
        data: {
          year: year,
          lang: vm.language
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
            vm.l = response;
        })
        .catch(function(err) {
            $log.warn(err);
        });
    };

})();
