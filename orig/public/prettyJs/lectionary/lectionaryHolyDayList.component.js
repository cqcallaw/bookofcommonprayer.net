(function() {
  'use strict';

  angular
    .module('bcpApp.lectionary')
    .component('lectionaryHolyDayList',{
      controllerAs: 'lhdl',
      controller: function($http,$stateParams,$cookieStore) {
      var vm = this;
      var formData = {
        today: $stateParams.today_date
      }

      var url = 'api/lectionary/holy_day_list';
      $http.post(url,formData).
        success(function(data, status, headers, config) {
          vm.hdl = data;
        }).
        error(function(data, status, headers, config) {
          $scope.message = status;
        });
    },
    template: [
      	'<span ng-repeat="hd in lhdl.hdl">',
          '<lectionary-holy-day id="hd.hd_id" type="list"></lectionary-holy-day>',
      	'</span>'
    ].join("")
    });
})();
