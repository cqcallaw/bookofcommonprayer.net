(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficePsalterCtrl', DailyOfficePsalterCtrl);

    DailyOfficePsalterCtrl.$inject = ['$scope','$http','$log','dataService'];

    function DailyOfficePsalterCtrl($scope, $http, $log, dataService) {
      var vm = this;

      vm.getPsalms = getPsalms;
      vm.getPsalmByChapter = getPsalmByChapter;
      vm.psalmChapters = [];
      for (var i = 1; i <= 150; i++) {
        vm.psalmChapters.push(i.toString());
      }

      function getPsalms(whichDayTime,$log) {
        var split = whichDayTime.split("|");
        var day = split[0];
        var time = split[1];
        var config = {
          method: "post",
          url: "api/daily_office/psalms",
          data: {
            day: day,
            time: time,
            type: 'daytime'
          }
        }

        dataCall(config,$log);
      }

      function getPsalmByChapter(chapter,$log) {
        var config = {
          method: "post",
          url: "api/daily_office/psalms",
          data: {
            chapter: chapter,
            type: 'chapter'
          }
        }

        dataCall(config,$log);
      }

      function dataCall(config,$log) {
        dataService
          .getData(config)
          .then(function(response) {
            vm.psalmList = response;
          })
          .catch(function(err) {
            $log.warn(err)
          });
      }

    }

})();
