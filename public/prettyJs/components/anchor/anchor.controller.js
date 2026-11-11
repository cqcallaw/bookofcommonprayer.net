(function() {
	'use strict';
	
	angular.module('bcpApp.anchor')
	.controller('AnchorScrollCtrl', AnchorScroll);
	
	AnchorScroll.$inject = ['$scope','$location','$anchorScroll'];
	
	function AnchorScroll($scope, $location, $anchorScroll) {
		$scope.goToTop = function() {
			$location.hash('theTop');
			$anchorScroll();
			$location.hash('');
	    }
	}
})();