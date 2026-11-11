(function () {
	angular
	    .module('bcpApp.lectionary')
	    .config(config);

	function config($stateProvider) {
	    $stateProvider
			.state('upcoming_lectionary', {
				url: '/lectionary/upcoming',
        controller: 'UpcomingLectionaryCtrl'
			})
			.state('lectionary_readings', {
				url: '/lectionary/:readings_page/:lectionary_year/:id/:lectionary_type/:prayer_type/:liturgical_color/{lectionary_date}',
				template: '<div ng-include src="l_readings.include"></div>',
				controller: 'LectionaryReadingsCtrl',
				controllerAs: 'l_readings'
			})
			.state('lectionary_by_date', {
				url: '/lectionary/date',
				templateUrl: 'public/templates/lectionary/html/lectionary_by_date.html',
				controller: 'LectionaryByDateCtrl',
				controllerAs: 'lect'
			})
			.state('lectionary_by_date_w_year', {
				url: '/lectionary/date/:year',
				templateUrl: 'public/templates/lectionary/html/lectionary_by_date.html',
				controller: 'LectionaryByDateCtrl',
				controllerAs: 'lect'
			})
			.state('holy_day_lectionary', {
				url: '/daily_office/:which_prayer/:which_rite/:yearNo/:do_id/:dow/{wc}/{today_date}',
        templateUrl: 'public/templates/lectionary/html/holy_day_lectionary.html',
				controller: 'LectionaryHolyDayCtrl'
			})
			.state('lectionary_holy_day_list', {
				url: '/lectionary/holy_day_list',
		    templateUrl: 'public/templates/lectionary/html/lectionary_holy_day_list.html'
			})
			.state('lectionary_holy_day_readings', {
				url: '/lectionary/holy_day/readings/:id',
				template: '<div ng-include src="lhd_readings.include"></div>',
				controller: 'LectionaryHolyDayReadingsCtrl',
				controllerAs: 'lhd_readings'
			});
	}
})();
