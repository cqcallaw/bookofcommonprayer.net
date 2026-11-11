(function() {
  'use strict';

  angular
    .module('bcpApp.lectionary')
    .component('lectionaryHolyDay',{
      bindings: {
        id: "<",
        type: "@"
      },
      controllerAs: 'lhd',
      controller: function($http,$stateParams,$cookieStore) {
      var vm = this;
      var formData = {
        today: vm.id
      }

      var url = 'api/lectionary/holy_days';
      $http.post(url,formData).
        success(function(data, status, headers, config) {
          vm.hd_id = data;
        }).
        error(function(data, status, headers, config) {
          $scope.message = status;
        });
    },
    template: [
      	'<p ng-if="lhd.hd_id.length > 0 && lhd.type == \'lectionary\'">Today is a Holy Day and provides the following',
      		' additional readings.</p>',
        '<p ng-if="lhd.hd_id.length > 0 && lhd.type == \'list\'">',
        '<strong><a ng-href="#/lectionary/holy_day/readings/{{lhd.hd_id[0].hd_id}}">',
        '{{lhd.hd_id[0].hd_name}}: {{lhd.hd_id[0].hd_id}}</a></strong>',
          '</p>',
    ].join("")
    });
})();
