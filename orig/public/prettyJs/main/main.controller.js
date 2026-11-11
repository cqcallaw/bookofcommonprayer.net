(function() {
	'use strict';

	angular
    .module('bcpApp')
    .controller('welcomeCtrl',WelcomeController);

		WelcomeController.$inject = ['$cookies','$document','$scope'];

		function WelcomeController($cookies,$document,$scope) {
			var vm = this;
			// fontsize
			vm.fontsize = ($cookies.get("FONTSIZE")) ? ($cookies.get("FONTSIZE"))+"pt" : '14pt';
			$document.ready(function () {
				angular.element('#container').css("font-size",vm.fontsize);
				vm.showImage = true;
    	});
		}
})();
