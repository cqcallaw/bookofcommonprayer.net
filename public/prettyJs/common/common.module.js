(function() {
    'use strict';

    angular.module('bcpApp.common', [
        /*
         * Angular modules
         */
         'ngAnimate', 'ui.router', 'ngSanitize','ngResource','ngCookies',
        /*
         * Our reusable cross app code modules
         */
         'bcpApp.anchor','bcpApp.dataService','bcpApp.filters',
         'bcpApp.cookieService','bcpApp.slideSwitch','bcpApp.textSizeSlider',
        /*
         * 3rd Party modules
         */
         'angular-jquery-datepicker','ui.date'
    ]);
})();
