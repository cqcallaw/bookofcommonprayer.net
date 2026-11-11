(function() {
  'use strict';

  angular
    .module('bcpApp.daily_office')
    .component('holyDayList',{
      controllerAs: 'hdl',
      controller: function($http,$stateParams,$cookieStore) {
      var vm = this;
      var formData = {
        today: $stateParams.today_date
      }

      var url = 'api/daily_office/holy_day_list';
      $http.post(url,formData).
        success(function(data, status, headers, config) {
          vm.hdl = data;
        }).
        error(function(data, status, headers, config) {
          $scope.message = status;
        });
    },
    template: [
      	'<span ng-repeat="hd in hdl.hdl">',
          '<holy_day id="hd.hd_do_id" type="list"></holy_day>',
      	'</span>'
    ].join("")
    });
})();
