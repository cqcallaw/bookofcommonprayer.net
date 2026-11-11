'use strict';
angular
  .module('angular-jquery-datepicker', [])
  .factory('datepicker', [function () {
    return $.datepicker;
  }])
  .provider('$datepicker', function () {
    return {
        setDefaults: function (language) {
            //$.datepicker.setDefaults($.datepicker.regional[language]);
        	$.datepicker.setDefaults( $.datepicker.regional[ "en" ] = {
		    monthNames: ['January','February','March','April','May','June',
		        'July','August','September','October','November','December'],
		    monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // For formatting
		    dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], // For formatting
		    dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], // For formatting
		    dayNamesMin: ['Su','M','T','W','Th','F','Sa'], // Column headings for days starting at Sunday
		    dateFormat: 'yy-mm-dd', // Swedish date format
		    showMonthAfterYear: true,
		    yearSuffix: '',
		    firstDay: 0
        	});
        },
        $get: function () {
            return {

            }
        }
    }
  }).directive('datepicker', function () {
    return {
        restrict: 'A',
        link: function (scope, elem, attr, ctrl) {
            elem.datepicker(
                {
                    buttonImage: "public/views/partials/forms/img/Calendar.png",
                    buttonImageOnly: true
                });
        }
    };
});
