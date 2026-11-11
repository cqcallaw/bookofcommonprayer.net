(function () {
	angular
	    .module('bcpApp.daily_office')
	    .config(config);

	function config($stateProvider) {
	    $stateProvider
			.state('todays_office', {
				url: '/daily_office/today',
				controller: "DailyOfficeTodaysOfficeCtrl"
			})
			.state('todays_office_readings', {
				url: '/daily_office/today/readings',
				controller: "DailyOfficeTodaysOfficeReadingsCtrl"
			})
			.state('daily_office_list', {
				url: '/daily_office/list',
				templateUrl: 'public/templates/daily_office/html/daily_office_options.html',
				controller: 'DailyOfficeOptionsCtrl',
				controllerAs: 'do_options'
			})
            .state('daily_office_evening_order', {
                url: '/daily_office/order_for_evening',
                templateUrl: 'public/templates/daily_office/html/order_for_evening.html',
                controller: 'DailyOfficeOptionsCtrl',
                controllerAs: 'do_options'
            })
			.state('daily_office_readings', {
				url: '/daily_office/:which_prayer/:which_rite/:yearNo/:do_id/:dow/{wc}/{today_date}',
				template: '<div ng-include src="do_readings.include"></div>',
				controller: 'DailyOfficeReadingsCtrl',
				controllerAs: 'do_readings'
			})
			.state('daily_office_archive',{
				url: '/daily_office/archive',
				templateUrl: "public/templates/daily_office/html/daily_office_archive.html",
				controllerAs: "dd",
				controller: "DailyOfficeArchiveCtrl"
			})
			.state('daily_office_reference_archive',{
				url: '/daily_office/archive/references/:year?',
				templateUrl: "public/templates/daily_office/html/daily_office_references.html",
				controllerAs: "do_refs",
				controller: "DailyOfficeArchiveReferenceCtrl",
				params: {
					year: {
						value: null,
						squash: true
					}
				}
			})
			.state('psalter', {
				url: '/daily_office/psalter',
				templateUrl: "public/templates/daily_office/html/daily_office_psalter.html",
				controllerAs: 'psalter',
				controller: "DailyOfficePsalterCtrl"
			})
			.state('holy_day', {
				url: '/daily_office/holy_day/:hd_id/:hd_time',
				templateUrl: "public/templates/daily_office/html/daily_office_holy_days.html",
				controller: "DailyOfficeHolyDayReadingCtrl",
				controllerAs: "holy_day"
			})
			.state('holy_day_list', {
				url: '/daily_office/holy_day_list',
				templateUrl: "public/templates/daily_office/html/daily_office_holy_day_list.html"
			});
	}
})();
