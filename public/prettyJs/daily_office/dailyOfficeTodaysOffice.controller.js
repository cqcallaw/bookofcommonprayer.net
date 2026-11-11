(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficeTodaysOfficeCtrl', DailyOfficeTodaysOfficeCtrl);

    DailyOfficeTodaysOfficeCtrl.$inject = ['$scope','$http','dataService'
      ,'$cookies','$log','$location','cookieService'];

    function DailyOfficeTodaysOfficeCtrl($scope,$http,dataService,$cookies
        ,$log,$location,cookieService) {
      var vm = this;
      // 1) cookieService returns a merged object of default values or the preferences
      // set on the preferences page
      vm.prefs = (cookieService.get('PREFS'));
      // the slideSwitch in the preferences page requires true/false settings, so we
      // need to look for the two items we need here and set it accordingly
      vm.timezone_offset = vm.prefs.timezone;
      var date = new Date();
      vm.current_time = date.getHours();
      // Prayers by time of day is the default, so switch to the prayer selected
      // otherwise, if they haven't selected it, then the default is morning prayer
      if(vm.prefs.prayerAll == false) {
        // prayerAll indicates "All Readings" so a false means they want two prayers
        vm.prefs.prayerDayTime = (vm.current_time < 12) ? 'morning_prayer' : 'evening_prayer';
      } else if(vm.prefs.prayerAll == true) {
        // prayerAll indicates "All readings" which means only one prayer per day
        vm.prefs.prayerDayTime = (vm.prefs.prayerDayTime == true) ? 'morning_prayer' : 'evening_prayer';
      }
      vm.prefs.prayerRite = (vm.prefs.prayerRite == true) ? 'rite_i' : 'rite_ii';
      // Need to check if language is set, and if so, overwrite the Rite
      if(vm.prefs.language == true) {
          vm.prefs.prayerRite = 'es';
      }
      vm.timezone = (vm.prefs.timezone) ? vm.prefs.timezone : null;
      // 2) get the CC, DO, and DOR data with one call
      var config = {
        method: 'post',
        url: 'api/daily_office',
        data: {
          tz: vm.timezone
        }
      }
      dataService
        .getData(config)
        .then(function(response) {
          vm.url_date = response.CC.today.replace(/\s/g,"_");
          // this response will contain all three
          // CC, DO and DOR objects
          vm.references = response;
          //$cookies.put('CC',JSON.stringify(response.CC));
          // now that we have the data we need, we can redirect to the regular
          // readings pages so that all stateParams are set
          var path = 'daily_office/'+vm.prefs.prayerDayTime+'/'+vm.prefs.prayerRite+'/'
            +response.DO.do_yearNo+'/'+response.DO.do_id+'/'
            +response.DO.do_dow+
            '/'+((response.DO.winter_collect) ? response.DO.winter_collect : 0)+'/'
            +vm.url_date;
          $location.path(path);
        })
        .catch(function(err) {
          $log.warn(err);
        });
    };

})();
