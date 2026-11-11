(function() {
  'use strict';

  angular
    .module('bcpApp.daily_office')
    .controller('DailyOfficeReadingsCtrl', DailyOfficeReadingsController);

    DailyOfficeReadingsController.$inject = ['$http','$stateParams'
      ,'dataService','$cookies','cookieService','$anchorScroll','$location'];

    function DailyOfficeReadingsController($http,$stateParams,dataService
        ,$cookies,cookieService,$anchorScroll,$location) {
      // Object {yearNo: "2", do_id: "7R", dow: "0", wc: "0", today_date: "Sunday,_May_08,_2016"}
      var vm = this;
      vm.include = 'public/templates/daily_office/html/'+$stateParams.which_prayer
        +"_"+$stateParams.which_rite+'.html';
      vm.yearNo = $stateParams.yearNo;
      vm.do_id = $stateParams.do_id;
      vm.dow = $stateParams.dow;
      vm.which_prayer = $stateParams.which_prayer,
      vm.which_rite = $stateParams.which_rite,
      vm.wc = $stateParams.wc;
      vm.today_date = $stateParams.today_date.replace(/\_/g,' ');
      // set up the id to pass to the holy day checker
      // done here to so we pass a string id, and then we can use the
      // reusable holyDay component in the holyDayList component.
      var months = {"January":"1","February":"2","March":"3"
      ,"April":"4","May":"5","June":"6","July":"7"
      ,"August":"8","September":"9","October":"10"
      ,"November":"11","December":"12"}
      var splits = vm.today_date.split(" ");
      var month = months[splits[1]];
      var day = splits[2].replace(",","");
      var test_date = month+"-"+day;
      vm.holy_day_test_date = test_date;
      // get preferences and see if cookies are set.
      vm.prefs = (cookieService.get("PREFS"));
      vm.timezone = (vm.prefs.timezone) ? vm.prefs.timezone : null;
      vm.prayerAll = vm.prefs.prayerAll;
      if(vm.prefs.language == true) {
          vm.prefs.prayerAll = (vm.prefs.prayerAll == true) ? "Todas Las Lecturas" : "Lecturas por hora del día";
      } else {
          vm.prefs.prayerAll = (vm.prefs.prayerAll == true) ? "All Readings Selected" : "Readings by time of day";
      }

      vm.BCPPsalms = (vm.prefs.useBCPDO == true) ? true : false;
      // check if cookie data is set, and if not, get it from the data service
      vm.CC = ($cookies.get('CC'))
        ? JSON.parse($cookies.get('CC'))
        : dataService.getData({
          method: 'post',
          url: 'api/daily_office',
          data: {
            tz: vm.timezone,
            lang: (vm.prefs.language == true) ? "ES" : "EN"
          }})
          .then(function(response) {
            vm.CC = response.CC;
          });
      // get the readings for the prayer pages
      var config = {
        method: 'post',
        url: 'api/daily_office/readings',
        data: {
          year: $stateParams.yearNo,
          id: $stateParams.do_id,
          dow: $stateParams.dow,
          wp: $stateParams.which_prayer,
          wr: $stateParams.which_rite,
          v: vm.prefs.bibleVersion,
          BCPPsalms: vm.BCPPsalms
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.references = response;
          $location.hash('theTop');
          $anchorScroll();
        });

      vm.goToHash = function(id) {
        $location.hash(id);
        $anchorScroll();
      }
    }
})();
