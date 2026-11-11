(function() {
  'use strict';

  angular
    .module('bcpApp.daily_office')
    .component('preferences', {
      controllerAs: 'prefs',
      controller: function(cookieService) {
        var vm = this;
        vm.preferences = cookieService.get('PREFS');
        vm.prefsSet = false;
        var count = 1;
        vm.defaults = cookieService.getDefaults();
        angular.forEach(vm.preferences, function(value,key) {
          // if even one item doesn't match the defaults, we know they are set
          if(!vm.prefsSet) {
            if(vm.preferences[key] != vm.defaults[key]) {
              vm.prefsSet = true;
            }
          }
        });
        vm.show_message = (vm.prefsSet == true) ? false : true;
        // reset the cookie expiration date here, if they have set
        // preferences before
        if(vm.prefsSet == true) {
          vm.curdate = new Date();
          vm.curdate.setMonth(vm.curdate.getMonth() + 1);
          angular.forEach(vm.preferences, function(value,key) {
            cookieService.set(key,value,'PREFS',vm.curdate);
          });
        }
      },
      template: [
        '<div class="alert alert-warning alert-dismissible" role="alert"',
        ' ng-show="prefs.show_message">',
        '<button type="button" class="close" data-dismiss="alert" ',
        'aria-label="Close"><span aria-hidden="true">&times;</span></button>',
        '<strong>Note!</strong> Default settings for this page (All readings, ',
        'and the Default Bible Version) are in effect.  If you would like to ',
        'use personal settings,',
        'please set your <a href="./#/preferences">preferences</a>.',
        '</div>'].join("")
    });
})();
