(function () {
    'use strict';

    angular
      .module('bcpApp.lectionary')
      .controller('UpcomingLectionaryCtrl', UpcomingLectionaryCtrl);

    UpcomingLectionaryCtrl.$inject = ['$scope','$http','dataService'
      ,'$cookies','$log','$location','cookieService'];

    function UpcomingLectionaryCtrl($scope,$http,dataService,$cookies
        ,$log,$location,cookieService) {
      var vm = this;
      // 1) cookieService returns a merged object of default values or the preferences
      // set on the preferences page
      vm.prefs = (cookieService.get('PREFS'));
      // the slideSwitch in the preferences page requires true/false settings, so we
      // need to look for the two items we need here and set it accordingly
      vm.prefs.lectionaryPrayerType = (vm.prefs.lectionaryPrayerType == true) ? 'T' : 'C';
      vm.prefs.lectionaryType = (vm.prefs.lectionaryType == true) ? 'BCP' : 'RCL';
      vm.timezone = (vm.prefs.timezone) ? vm.prefs.timezone : null;
      vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
      // 2) get the CC, DO, and DOR data with one call
      var config = {
        method: 'post',
        url: 'api/lectionary',
        data: {
          tz: vm.timezone
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.url_date = response.CC.this_coming_sunday.replace(/\s/g,"_");
          var readings_page = (vm.language == 'EN') ? "readings":"readings_es";
          // this response will contain the CC object
          //$cookies.put('CC',JSON.stringify(response.CC));
          // now that we have the data we need, we can redirect to the regular
          // readings pages so that all stateParams are set
          var path = 'lectionary/'+readings_page+'/'+response.CC.lectionary_year
          +'/'+response.CC.upcoming_sunday_id
          +'/'+vm.prefs.lectionaryType+'/'+vm.prefs.lectionaryPrayerType
          +'/'+response.CC.upcoming_sunday_color
          +'/'+vm.url_date
          $location.path(path);
          // console.log(response);
        })
        .catch(function(err) {
          $log.warn(err);
        });
    };

})();
