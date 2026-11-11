(function () {
    angular
    .module('bcpApp.search')
    .controller('searchCtrl', search);

    search.$inject = ['$cookies','cookieService','$stateParams','dataService'];

    function search($cookies,cookieService,$stateParams,dataService) {
      var vm = this;

            vm.preferences = (cookieService.get("PREFS"));
            vm.bibleVersion = vm.preferences.bibleVersion;
            // search will always be based off of BCP Lectionary setting
            vm.useBCPPsalms = vm.preferences.useBCPLect;
            vm.passage = $stateParams.passage;

            if( (vm.passage.indexOf(';') !== -1)
                || (vm.passage.indexOf('\'') !== -1)
                || (vm.passage.indexOf('=') !== -1) ) {
                    vm.illegalChars = true;
            } else {
                var config = {
                    method: 'post',
                    url: 'api/search/get',
                    data: {
                        p: vm.passage,
                        v: vm.bibleVersion,
                        b: vm.useBCPPsalms
                    }
                }
                
                dataService
                    .getData(config)
                    .then(function(response) {
                        vm.references = response;
                    });
            }
    }
})();
