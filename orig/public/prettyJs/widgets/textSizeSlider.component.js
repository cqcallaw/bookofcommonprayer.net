(function() {
  'use strict';

  angular
    .module('bcpApp.textSizeSlider', [])
    .component('textSizeSlider', {
        bindings: {
          min: '@',
          max: '@',
          unit: '@',
          value: '@',
          step: '@'
        },
        controllerAs: 'ts',
        controller: function($scope,$cookies) {
          var vm = this;
          vm.textSize = vm.value;
          vm.setFontSize = function(textSize) {
            // cookie expiration
            var curdate = new Date();
            curdate.setMonth(curdate.getMonth() + 1);
            $cookies.put("FONTSIZE",textSize, {
              expires: curdate
            });
          }

          $scope.$watch('ts.textSize', function (size) {
            document.querySelector("#sample").style.fontSize = size + vm.unit;
          });
      },
      template: ['<div class="text-size-slider">',
      '<span class="font-sizer sm">A</span>',
      '<span class="font-sizer md">A</span>',
      '<span class="font-sizer lg">A</span><br/>',
      '<input type="range" min="{{ ts.min }}" max="{{ ts.max }}" step="{{ ts.step || 0 }}"',
      ' ng-model="ts.textSize" class="slider" value="{{ ts.value }}" ',
      ' ng-change="ts.setFontSize(ts.textSize);" /></div>',
      '<div id="sample"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div>'
      ].join("")
    });
})();
