(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficeArchiveCtrl', DailyOfficeArchiveCtrl);

    DailyOfficeArchiveCtrl.$inject = ['$location','dataService','$log'];

    function DailyOfficeArchiveCtrl($location, dataService,$log) {
      var vm = this;
      vm.dateOptions = {
        dateFormat: 'mm/dd/yy'
      };
      vm.fun = function(t) {
        // vm.dow = t.getDay();
        vm.todayDate = new Date();
        if (t < vm.todayDate) {
            $( function() {
                $( "#dialog" ).dialog();
            });
            return false
        }
        var config = {
            method: "post",
            url: "api/daily_office",
            data: {
                date: t,
                type: 'archive'
            }
        }

        dataService
          .getData(config)
          .then(function(response) {
            var path = 'daily_office/readings/rite_i/'
              +response.DO.do_yearNo+'/'+response.DO.do_id+'/'
              // +vm.dow+'/0/'
                +response.DO.do_dow+'/0/'
              +response.DO.today_date;
            $location.path(path);
          })
          .catch(function(err) {
            $log.warn(err)
          });
      }
    }

})();
