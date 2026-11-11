(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('comfortableWords',{
        controllerAs: 'cw',
        controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
          var vm = this;
          // check if evening or morning prayer
          vm.isEvening = ($stateParams.which_prayer == 'evening_prayer')
            ? true : false;
          vm.prefs = (cookieService.get('PREFS'));
          vm.header = (vm.prefs.language == true) ? 'El Oficio comienza con los siguientes:' : 'Officiant begins with the following:';
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          vm.url = (vm.prefs.language == true) ? 'api/daily_office/comfortable_words_es' : 'api/daily_office/comfortable_words';
          // $http config object
          var config = {
            method: 'post',
            url: vm.url,
            data: {
              year: $stateParams.yearNo,
              id: $stateParams.do_id,
              dow: $stateParams.dow,
              wp: $stateParams.which_prayer,
              wr: $stateParams.which_rite
            }
          }

          dataService
            .getData(config)
            .then(function(response) {
              vm.cw = response;
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: [
          '<p><em>{{cw.header}}</em></p>',
          '<div ng-if="!cw.isEvening">',
          '<p>{{cw.cw.cw}} -',
          '<em>{{cw.cw.reference}}</em></p>',
          '</div>',
          '<div ng-if="cw.isEvening">',
          '<p>{{cw.cw.first.cw}} -',
          '<em>{{cw.cw.first.reference}}</em></p>',
          '<p>{{cw.cw.second.cw}} -',
          '<em>{{cw.cw.second.reference}}</em><br/></p>',
          '</div>'
        ].join("")
      });




})();
