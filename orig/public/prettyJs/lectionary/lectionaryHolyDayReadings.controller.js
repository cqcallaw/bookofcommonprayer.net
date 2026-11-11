(function() {
  'use strict';

  angular
    .module('bcpApp.lectionary')
    .controller('LectionaryHolyDayReadingsCtrl', LectionaryHolyDayReadingsCtrl);

    LectionaryHolyDayReadingsCtrl.$inject = ['$http','$stateParams'
      ,'dataService','$cookies','cookieService'];

    function LectionaryHolyDayReadingsCtrl($http,$stateParams,dataService
        ,$cookies,cookieService) {
      // /lectionary/readings/:lectionary_year/:id/:lectionary_type
      // /:prayer_type/:liturgical_color/{lectionary_date}
      var vm = this;
      vm.id = $stateParams.id;

      vm.include = 'public/templates/lectionary/html/lectionary_holy_day_readings.html';

      // get the readings for the lectionary readings and spit them out
      var config = {
        method: 'post',
        url: 'api/lectionary/holy_day_readings',
        data: {
          id: $stateParams.id
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.references = response;
        });
    }
})();
