(function() {
  'use strict';

  angular
    .module('bcpApp.lectionary')
    .controller('LectionaryReadingsCtrl', LectionaryReadingsCtrl);

    LectionaryReadingsCtrl.$inject = ['$http','$stateParams'
      ,'dataService','$cookies','cookieService','$anchorScroll','$location'];

    function LectionaryReadingsCtrl($http,$stateParams,dataService
        ,$cookies,cookieService,$anchorScroll,$location) {
      // /lectionary/readings/:lectionary_year/:id/:lectionary_type
      // /:prayer_type/:liturgical_color/{lectionary_date}
      var vm = this;
        vm.prefs = (cookieService.get('PREFS'));
        vm.language = (vm.prefs.language == true) ? "ES" : "EN";
      vm.lectionary_page = $stateParams.readings_page;
      vm.lectionary_year = $stateParams.lectionary_year;
      vm.id = $stateParams.id;
      vm.lectionary_type = $stateParams.lectionary_type;
      vm.prayer_type = $stateParams.prayer_type;
      vm.liturgical_color = $stateParams.liturgical_color;
      vm.lectionary_date = ($stateParams.lectionary_date) ? $stateParams.lectionary_date.replace(/\_/g,' ') : '';
      vm.include = 'public/templates/lectionary/html/lectionary_'+vm.lectionary_page+'.html';

      // set up the id to pass to the holy day checker
      // done here to so we pass a string id, and then we can use the
      // reusable holyDay component in the holyDayList component.
      var months = {"January":"1","February":"2","March":"3"
      ,"April":"4","May":"5","June":"6","July":"7"
      ,"August":"8","September":"9","October":"10"
      ,"November":"11","December":"12"}
      var splits = vm.lectionary_date.split(" ");
      var month = months[splits[1]];
      var day = splits[2].replace(",","");
      var test_date = month+"-"+day;
      vm.holy_day_test_date = test_date;
      // Cookie content is too large, so I'm forcing it to get a new CC object
      vm.CC = ($cookies.get('CC'))
        ? JSON.parse($cookies.get('CC'))
        : dataService.getData({
          method: 'post',
          data: {
              lang: vm.language
          },
          url: 'api/lectionary'})
          .then(function(response) {
            vm.CC = response.CC;
            angular.forEach(vm.CC.orderedSundays, function(value,key) {
              if(value.id == vm.id) {
                vm.CC.upcoming_sunday_name = value.name;
              }
            });
          });
      // get preferences and see if cookies are set.
      vm.prefs = (cookieService.get("PREFS"));
      vm.BCPPsalms = (vm.prefs.useBCPLect == true) ? true : false;
      // technically, the preferences were checked before the redirect, so the
      // only thing we need here will be the bible version people choose.
      // get the readings for the lectionary readings and spit them out
      var config = {
        method: 'post',
        url: 'api/lectionary/readings',
        data: {
          year: $stateParams.lectionary_year,
          id: $stateParams.id,
          wl: $stateParams.lectionary_type,
          wp: $stateParams.prayer_type,
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
    }
})();
