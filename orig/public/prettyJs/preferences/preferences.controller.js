(function () {
	angular
    .module('bcpApp.preferences')
    .controller('preferencesCtrl', preferences);

    preferences.$inject = ['$cookies','cookieService'];

    function preferences($cookies,cookieService) {
      var vm = this;
			// cookie expiration
			vm.curdate = new Date();
			vm.curdate.setMonth(vm.curdate.getMonth() + 1);

			vm.setPrefs = setPrefs;
			vm.getPrefs = getPrefs;

			vm.preferences = getPrefs();
			vm.fontSize = $cookies.get("FONTSIZE") ? $cookies.get("FONTSIZE") : "14";
			vm.tzSelected = vm.preferences.timezone;
			vm.bibleVersion = vm.preferences.bibleVersion;

			function getPrefs() {
				return cookieService.get("PREFS");
			}

			function setPrefs(name,value) {
				cookieService.set(name,value,"PREFS",vm.curdate);
			}
    }
})();
