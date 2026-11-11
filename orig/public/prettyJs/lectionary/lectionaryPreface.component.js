(function () {
    'use strict';

    angular
      .module('bcpApp.lectionary')
      .component('lectionaryPreface', {
          controllerAs: 'preface',
          controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
          var vm = this;
          var wp = ($stateParams.prayer_type == 'C') ? 'rite_ii':'rite_i';
          vm.prefs = (cookieService.get('PREFS'));
          vm.preface_header = (vm.prefs.language == true) ? 'Prefacio' : 'The Preface';
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          // $http config object
          var config = {
            method: 'post',
            url: 'api/lectionary/preface',
            data: {
              id: $stateParams.id,
              wp: wp,
              lang: vm.language
            }
          }
          dataService
            .getData(config)
            .then(function(response) {
              vm.preface = response;
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: [
          '<p><strong><u>{{preface.preface_header}}:</u></strong></p>',
          '<span ng-repeat="pref in preface.preface.p track by $index">',
          '<strong>{{pref.pref_name}}</strong><br/>',
          '<span ng-if="pref.pref_instr"><em>',
          '{{pref.pref_instr}}</em><br/></span>',
          '<span ng-bind-html="pref.pref_text | amenify | or2br"></span></p>',
          '<span ng-if="!$last">&mdash;OR&mdash;<br/></span>',
          '</span>'
        ].join("")
      });

})();
