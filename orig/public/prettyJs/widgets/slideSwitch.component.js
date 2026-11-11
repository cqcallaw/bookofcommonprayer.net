(function () {
  'use strict';

  angular
    .module('bcpApp.slideSwitch',[])
    .component('slideSwitch',{
      bindings: {
        which: "@",
        onLabel: '@',
        offLabel: '@',
        knobLabel: '@',
        initial: '<'
      },
      controllerAs: 'sw',
      controller: function($scope) {
        var vm = this;
        vm.toggle = function() {
          $scope.$parent.prefs.setPrefs(vm.which,!vm.switcher);
          vm.switcher = !vm.switcher;
      };
    },
    template: ['<div class="switch" ng-click="sw.toggle()" data-size="small">',
    '<div class="switch-animate" ng-model="sw.switcher" ng-init="sw.switcher=sw.initial"',
    'ng-class="{\'switch-on\': sw.switcher, \'switch-off\': !sw.switcher}">',
    '<span class="switch-left" ng-bind="sw.onLabel"></span><span class="knob" ng-bind="sw.knobLabel">',
    '</span><span class="switch-right" ng-bind="sw.offLabel"></span></div></div>'
    ].join("")
  });
})();
