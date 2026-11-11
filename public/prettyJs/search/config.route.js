(function () {
	angular
	    .module('bcpApp.search')
	    .config(config);

	function config($stateProvider) {
	    $stateProvider
			.state('search', {
				url: '/search/display/:passage',
				templateUrl: "public/templates/search/html/search.html",
        controllerAs: "search",
				controller: "searchCtrl"
			});
	}
})();
