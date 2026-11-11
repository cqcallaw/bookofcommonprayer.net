(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('canticle', {
        bindings: {
          wc: '@',
          season: '<'
        },
        controllerAs: 'c',
        controller: function($scope, $http, $stateParams,dataService, $log, cookieService) {
          var c = this;
          c.prefs = (cookieService.get('PREFS'));
          c.language = (c.prefs.language == true) ? 'ES' : 'EN';
          var config = {
            method: "post",
            url: "api/daily_office/canticles",
            data: {
              year: $stateParams.yearNo,
              id: $stateParams.do_id,
              dow: $stateParams.dow,
              wp: $stateParams.which_prayer,
              wr: $stateParams.which_rite,
              season: c.season,
              lang: c.language
            }
          }

          dataService
            .getData(config)
            .then(function(response) {
              c.canticle = response;
            })
            .catch(function(err) {
              $log.warn(err)
            });
        },
        template: [
          '<p><b>{{c.canticle.c_list[c.wc].cant_title}}</b>&nbsp;',
          	'<em>{{c.canticle.c_list[c.wc].cant_latin}}</em><br/>',
          	'<span ng-if="c.canticle.c_list[c.wc].cant_ref">',
            '<em>{{c.canticle.c_list[c.wc].cant_ref}}</em><br/></span>',
          	'<span ng-if="c.canticle.c_list[c.wc].cant_instr">',
            '<em>{{c.canticle.c_list[c.wc].cant_instr}}</em><br/></span>',
          '</p>',
          '<pre>{{c.canticle.c_list[c.wc].cant_cont}}</pre>'
        ].join("")
      });
})();
