(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('bibleReading', {
        bindings: {
          type: "@",
          parseType: "<",
          value: "<"
        },
        controllerAs: 'br',
        controller: function($scope, $http, $stateParams, dataService ,$log) {
          var vm = this;
          var reading_type = null;
          if(vm.type == 'old_testament') {
            vm.reading_type = 'Old Testament';
          } else if (vm.type == 'new_testament') {
            vm.reading_type = 'New Testament';
          } else if (vm.type == 'gospel') {
            vm.reading_type = 'Gospel';
          } else if (vm.type == 'first') {
            vm.reading_type = 'First';
          } else if (vm.type == 'second') {
            vm.reading_type = 'Second';
          } else if (vm.type == 'search') {
            vm.reading_type = 'Search';
          }
        },
        templateUrl: 'public/templates/daily_office/html/bible_reading.html'
    });
})();
