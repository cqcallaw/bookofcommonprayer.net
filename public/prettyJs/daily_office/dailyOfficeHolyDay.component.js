(function() {
  'use strict';

  angular
    .module('bcpApp.daily_office')
    .component('holyDay',{
      bindings: {
        id: "<",
        type: "@"
      },
      controllerAs: 'hd',
      controller: function($http,$stateParams,$cookieStore) {
      var vm = this;
      var formData = {
        today: vm.id
      }

      var url = 'api/daily_office/holy_days';
      $http.post(url,formData).
        success(function(data, status, headers, config) {
          vm.hd_id = data;
        }).
        error(function(data, status, headers, config) {
          $scope.message = status;
        });
    },
    template: [
      	'<p ng-if="hd.hd_id.length > 0 && hd.type == \'prayer\'">Today is a Holy Day and provides the following',
      		' additional readings.</p>',
        '<p ng-if="hd.hd_id.length > 0 && hd.type == \'list\'">',
        '<strong>{{hd.hd_id[0].hd_do_name}}: {{hd.hd_id[0].hd_do_id}}</strong></p>',
      	'<span ng-repeat="hdr in hd.hd_id">',
      		'<span ng-if="hdr.hd_do_time == \'M\'">',
      			'Morning Readings: ',
      			'<a ng-href="#/daily_office/holy_day/{{hdr.hd_do_id}}/{{hdr.hd_do_time}}">',
      			'{{hdr.hd_do_name}}</a><br/>',
      		'</span>',
      		'<span ng-if="hdr.hd_do_time == \'E\'">',
      			'Evening Readings: ',
      			'<a ng-href="#/daily_office/holy_day/{{hdr.hd_do_id}}/{{hdr.hd_do_time}}">',
      			'{{hdr.hd_do_name}}</a><br/>',
      		'</span>',
      	'</span>',
      	'<br/>'
    ].join("")
    });
})();
