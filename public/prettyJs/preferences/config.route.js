(function () {
	angular
	    .module('bcpApp.preferences')
	    .config(config);

	function config($stateProvider) {
	    $stateProvider
			.state('preferences', {
				url: '/preferences',
				templateUrl: "public/templates/preferences/html/preferences.html",
        controllerAs: "prefs",
				controller: "preferencesCtrl"
			});
	}
})();
