(function () {
	angular
	    .module('bcpApp')
	    .config(config);

	function config($stateProvider,$locationProvider) {
		$stateProvider
			.state('main', {
				url: '',
				templateUrl: "public/templates/main/html/welcome.html"
			})
			.state("otherwise", {
    		url: "*path",
    		templateUrl: "public/templates/main/html/error_not_found.html"
			});
	}
})();
