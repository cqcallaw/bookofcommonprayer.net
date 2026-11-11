(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('psalmReading', {
        bindings: {
          type: "@",
          parseType: '<',
          value: "<"
        },
        controllerAs: 'br',
        controller: function($scope, $http, $stateParams, dataService ,$log) {
          var vm = this;
          if (vm.type == 'm_psalms') {
            vm.reading_type = 'Morning Psalms';
          } else if (vm.type == 'e_psalms') {
            vm.reading_type = 'Evening Psalms';
          } else if (vm.type == 'hd_psalm') {
            vm.reading_type = 'Psalm Reading(s)';
          } else if (vm.type == 'psalm') {
            vm.reading_type = 'Psalm(s)';
          } else if (vm.type == 'search') {
            vm.reading_type = 'Search';
          }
        },
        templateUrl: 'public/templates/daily_office/html/psalm_reading.html'
    });
})();
