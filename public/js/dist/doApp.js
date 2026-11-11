(function() {
    'use strict';
    angular.module('bcpApp.daily_office', []);
})();
(function() {
    'use strict';
    angular.module('bcpApp.lectionary', []);
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.filters',[]);
})();

(function() {
    'use strict';
    angular.module('bcpApp.preferences', []);
})();

(function() {
    'use strict';
    angular.module('bcpApp.search', []);
})();

(function() {
	angular.module('bcpApp.anchor',[]);
})();
(function() {
  'use strict';

  angular
    .module('bcpApp.cookieService',[]);
})();

(function() {
	angular.module('bcpApp.dataService',[]);
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.exceptionOverride',[]);
})();

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

(function() {
	angular.module('bcpApp',[
	 'bcpApp.common',
	 'bcpApp.daily_office',
	 'bcpApp.preferences',
	 'bcpApp.lectionary',
	 'bcpApp.search'
	 ]);
})();

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

(function() {
  'use strict';

  angular
    .module('bcpApp.filters')
    // amenify filter, which converts the input to html
    .filter('amenify', function() {
      return function(input) {
        // the filter gets processed before the promise returns
        // so we need to make sure it gets returned unless input is defined
        if(!input) { return; }
        input = input.replace(new RegExp(/(=Amen.=)/,"g"),"<em><strong>Amen</strong></em>");
        return input;
      }
    });
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.filters')
    .filter('or2br', function() {
      return function(input) {
        if(!input) { return; }
        return input.replace(new RegExp(/\*or\*/, "g"),'<br/>*or*<br/>');
      }
    });
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.filters')
    .filter('orthis2br', function() {
      return function(input) {
        if(!input) { return; }
        return input.replace(new RegExp(/\*or this\*/, "g"),'<br/>*or*<br/>');
      }
    });
})();

(function() {
  'use strict';
//Not in use.  Delete
  angular
    .module('bcpApp.filters')
    .filter('saintify', function() {
      return function(input) {
        // the filter gets processed before the promise returns
        // so we need to make sure it gets returned unless input is defined
        if(!input) { return; }
        //var re = new RegExp(/(\w+)?/);
        input = input.replace(/\W+/,'');
        return input;
      }
    });
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.filters')
    .filter('star2br', function() {
      return function(input) {
        if(!input) { return; }
        input = input.replace('*','&nbsp;*<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        return input;
      }
    });
})();

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

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('additionalCollect', {
          controllerAs: 'ac',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/additional_collect_es' : 'api/daily_office/additional_collect';
            // $http config object
            var config = {
              method: 'post',
              url: vm.url,
              data: {
                wp: $stateParams.which_prayer,
                wr: $stateParams.which_rite,
                dow: $stateParams.dow
              }
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.additional_collect = response;
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: ['<p><strong>{{ac.additional_collect.name}}</strong><br/>',
          '<span ng-bind-html="ac.additional_collect.text | amenify"></span></p>'
        ].join("")
      });
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('antiphon', {
        controllerAs: 'a',
        controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
        var vm = this;
        vm.prefs = (cookieService.get('PREFS'));
        vm.header = (vm.prefs.language == true) ? 'Con el Salmo Invitatorio puede cantarse o decirse una de las siguientes Antífonas' : 'One of the following Antiphons may be sung or said with the Invitatory Psalm';
        vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
        vm.url = (vm.prefs.language == true) ? 'api/daily_office/antiphon_es' : 'api/daily_office/antiphon';
        // $http config object
        var config = {
          method: 'post',
          url: vm.url,
          data: {
            id: $stateParams.do_id,
            wr: $stateParams.which_rite
          }
        }

        dataService
          .getData(config)
          .then(function(response) {
            vm.antiphon = response;
          })
          .catch(function(err) {
            $log.warn(err);
          });
      },
      template: [
        '<p><em>{{a.header}}</em></p>',
        '<p><em>{{a.antiphon.timeframe}}</em><br/>{{a.antiphon.antiphon}}</p>'
      ].join("")
    });
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficeArchiveCtrl', DailyOfficeArchiveCtrl);

    DailyOfficeArchiveCtrl.$inject = ['$location','dataService','$log'];

    function DailyOfficeArchiveCtrl($location, dataService,$log) {
      var vm = this;
      vm.dateOptions = {
        dateFormat: 'mm/dd/yy'
      };
      vm.fun = function(t) {
        // vm.dow = t.getDay();
        vm.todayDate = new Date();
        // if (t < vm.todayDate) {
        //     $( function() {
        //         $( "#dialog" ).dialog();
        //     });
        //     return false
        // }
        var config = {
            method: "post",
            url: "api/daily_office",
            data: {
                date: t,
                type: 'archive'
            }
        }

        dataService
          .getData(config)
          .then(function(response) {
            var path = 'daily_office/readings/rite_i/'
              +response.DO.do_yearNo+'/'+response.DO.do_id+'/'
              // +vm.dow+'/0/'
                +response.DO.do_dow+'/0/'
              +response.DO.today_date;
            $location.path(path);
          })
          .catch(function(err) {
            $log.warn(err)
          });
      }
    }

})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficeArchiveReferenceCtrl', DailyOfficeArchiveReferenceCtrl);

    DailyOfficeArchiveReferenceCtrl.$inject = ['$scope','$http','$location','dataService','$log','$stateParams'];

    function DailyOfficeArchiveReferenceCtrl($scope, $http, $location, dataService, $log, $stateParams) {
      var vm = this;
      vm.year = $stateParams.year;
      vm.nextYear = new Date().getFullYear()+1;//vm.thisYear + 1;

      var config = {
          method: "post",
          url: "api/daily_office/references",
          data: {
              year: (vm.year) ? vm.year : null
          }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.references = response;
        })
        .catch(function(err) {
          $log.warn(err)
        });
      }
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('bibleReading', {
        bindings: {
          type: "@",
          parseType: "<",
          value: "<"
        },
        controllerAs: 'br',
        controller: function($scope, $http, $stateParams, dataService ,$log) {
          var vm = this;
          var reading_type = null;
          if(vm.type == 'old_testament') {
            vm.reading_type = 'Old Testament';
          } else if (vm.type == 'new_testament') {
            vm.reading_type = 'New Testament';
          } else if (vm.type == 'gospel') {
            vm.reading_type = 'Gospel';
          } else if (vm.type == 'first') {
            vm.reading_type = 'First';
          } else if (vm.type == 'second') {
            vm.reading_type = 'Second';
          } else if (vm.type == 'search') {
            vm.reading_type = 'Search';
          }
        },
        templateUrl: 'public/templates/daily_office/html/bible_reading.html'
    });
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('collectOfDay', {
          controllerAs: 'cod',
          controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
          var vm = this;
          var wc = ($stateParams.wc) ? $stateParams.wc : null;
          vm.prefs = (cookieService.get('PREFS'));
          vm.collect_header = (vm.prefs.language == true) ? 'Colecta del Día' : 'The Collect of the Day';
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          // $http config object
          var config = {
            method: 'post',
            url: 'api/daily_office/collect_of_day',
            data: {
              id: $stateParams.do_id,
              wr: $stateParams.which_rite,
              wc: wc,
              dow: $stateParams.dow,
              lang: vm.language
            }
          }
          dataService
            .getData(config)
            .then(function(response) {
              vm.collect_of_day = response.cod;
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: [
          '<p><strong><u>{{cod.collect_header}}</u></strong></p>',
          '<strong>{{cod.collect_of_day.name}}</strong><br/>',
          '<span ng-bind-html="cod.collect_of_day.text | amenify | or2br | orthis2br"></span></p>'
        ].join("")
      });

})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('canticle', {
        bindings: {
          wc: '@',
          season: '<'
        },
        controllerAs: 'c',
        controller: function($scope, $http, $stateParams,dataService, $log, cookieService) {
          var c = this;
          c.prefs = (cookieService.get('PREFS'));
          c.language = (c.prefs.language == true) ? 'ES' : 'EN';
          var config = {
            method: "post",
            url: "api/daily_office/canticles",
            data: {
              year: $stateParams.yearNo,
              id: $stateParams.do_id,
              dow: $stateParams.dow,
              wp: $stateParams.which_prayer,
              wr: $stateParams.which_rite,
              season: c.season,
              lang: c.language
            }
          }

          dataService
            .getData(config)
            .then(function(response) {
              c.canticle = response;
            })
            .catch(function(err) {
              $log.warn(err)
            });
        },
        template: [
          '<p><b>{{c.canticle.c_list[c.wc].cant_title}}</b>&nbsp;',
          	'<em>{{c.canticle.c_list[c.wc].cant_latin}}</em><br/>',
          	'<span ng-if="c.canticle.c_list[c.wc].cant_ref">',
            '<em>{{c.canticle.c_list[c.wc].cant_ref}}</em><br/></span>',
          	'<span ng-if="c.canticle.c_list[c.wc].cant_instr">',
            '<em>{{c.canticle.c_list[c.wc].cant_instr}}</em><br/></span>',
          '</p>',
          '<pre>{{c.canticle.c_list[c.wc].cant_cont}}</pre>'
        ].join("")
      });
})();

(function () {
    'use strict';

    angular.module('bcpApp.daily_office').controller('DailyOfficeCanticleCtrl', DailyOfficeCanticleCtrl);

    DailyOfficeCanticleCtrl.$inject = ['$scope','$http','$stateParams','dataService'];

    function DailyOfficeCanticleCtrl($scope, $http, $stateParams,dataService) {
      // Object {yearNo: "2", do_id: "7R", dow: "0", wc: "0", today_date: "Sunday,_May_08,_2016"}
      var vm = this;

      var config = {
        method: "post",
        url: "api/daily_office/canticles",
        data: {
          year: $stateParams.yearNo,
          id: $stateParams.do_id,
          dow: $stateParams.dow,
          wp: $stateParams.which_prayer,
          wr: $stateParams.which_rite
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.canticles = response;
        })
        .catch(function(err) {
          $log.warn(err)
        });
    }

})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('comfortableWords',{
        controllerAs: 'cw',
        controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
          var vm = this;
          // check if evening or morning prayer
          vm.isEvening = ($stateParams.which_prayer == 'evening_prayer')
            ? true : false;
          vm.prefs = (cookieService.get('PREFS'));
          vm.header = (vm.prefs.language == true) ? 'El Oficio comienza con los siguientes:' : 'Officiant begins with the following:';
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          vm.url = (vm.prefs.language == true) ? 'api/daily_office/comfortable_words_es' : 'api/daily_office/comfortable_words';
          // $http config object
          var config = {
            method: 'post',
            url: vm.url,
            data: {
              year: $stateParams.yearNo,
              id: $stateParams.do_id,
              dow: $stateParams.dow,
              wp: $stateParams.which_prayer,
              wr: $stateParams.which_rite
            }
          }

          dataService
            .getData(config)
            .then(function(response) {
              vm.cw = response;
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: [
          '<p><em>{{cw.header}}</em></p>',
          '<div ng-if="!cw.isEvening">',
          '<p>{{cw.cw.cw}} -',
          '<em>{{cw.cw.reference}}</em></p>',
          '</div>',
          '<div ng-if="cw.isEvening">',
          '<p>{{cw.cw.first.cw}} -',
          '<em>{{cw.cw.first.reference}}</em></p>',
          '<p>{{cw.cw.second.cw}} -',
          '<em>{{cw.cw.second.reference}}</em><br/></p>',
          '</div>'
        ].join("")
      });




})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('complineCollect', {
          controllerAs: 'cc',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/compline_collect_es' : 'api/daily_office/compline_collect';
            var config = {
              method: 'post',
              url: vm.url
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.compline_collect = response[0];
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: '<p><span ng-bind-html="cc.compline_collect | amenify"></span></p>'
      });
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('complinePrayer', {
          controllerAs: 'cp',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/compline_prayer_es' : 'api/daily_office/compline_prayer';
            var config = {
              method: 'post',
              url: vm.url
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.compline_prayer = response[0];
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: '<p><span ng-bind-html="cp.compline_prayer | amenify"></span></p>'
      });
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('complineScripture', {
          controllerAs: 'cs',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/compline_scripture_es' : 'api/daily_office/compline_scripture';
            var config = {
              method: 'post',
              url: vm.url
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.compline_scripture = response;
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: [
            '<p>{{cs.compline_scripture.text}}',
            '<br/><em>{{cs.compline_scripture.reference}}</em></p>'
          ].join("")
      });
})();

(function () {
    'use strict';

    angular.module('bcpApp.daily_office')
    .component('conclusion', {
        controllerAs: "cc",
        controller: function($http,dataService,$log,cookieService) {
          var vm = this;
          vm.prefs = (cookieService.get('PREFS'));
          vm.header = (vm.prefs.language == true) ? 'El Oficiante puede entonces concluir con uno de los siguientes versículos:' : 'Officiant concludes with:';
          vm.url = (vm.prefs.language == true) ? 'api/daily_office/conclusion_es' : 'api/daily_office/conclusion';
          // $http config object
          var config = {
            method: 'post',
            url: vm.url
          }

          dataService
            .getData(config)
            .then(function(response) {
              vm.conclusion = response;
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: [
          "<p><em>{{cc.header}}</em></p>",
          "<p><span ng-bind-html='cc.conclusion.text | amenify'></span><br/>",
          "<em>{{cc.conclusion.reference}}</em></p>"
        ].join("")
    });

})();

(function () {
    'use strict';

    angular.module('bcpApp.daily_office').controller('DailyOfficeConclusionCtrl'
    , DailyOfficeConclusionCtrl);

    DailyOfficeConclusionCtrl.$inject = ['$scope','$http','$stateParams'
    ,'dataService','$log'];

    function DailyOfficeConclusionCtrl($scope, $http, $stateParams,dataService
      ,$log) {
      var vm = this;
      // $http config object
      var config = {
        method: 'post',
        url: 'api/daily_office/conclusion',
        data: {
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.conclusion = response;
        })
        .catch(function(err) {
          $log.warn(err);
        });
    }

})();

(function() {
  'use strict';

  angular
    .module('bcpApp.daily_office')
    .component('holyDay',{
      bindings: {
        id: "<",
        type: "@"
      },
      controllerAs: 'hd',
      controller: function($http,$stateParams,$cookieStore) {
      var vm = this;
      var formData = {
        today: vm.id
      }

      var url = 'api/daily_office/holy_days';
      $http.post(url,formData).
        success(function(data, status, headers, config) {
          vm.hd_id = data;
        }).
        error(function(data, status, headers, config) {
          $scope.message = status;
        });
    },
    template: [
      	'<p ng-if="hd.hd_id.length > 0 && hd.type == \'prayer\'">Today is a Holy Day and provides the following',
      		' additional readings.</p>',
        '<p ng-if="hd.hd_id.length > 0 && hd.type == \'list\'">',
        '<strong>{{hd.hd_id[0].hd_do_name}}: {{hd.hd_id[0].hd_do_id}}</strong></p>',
      	'<span ng-repeat="hdr in hd.hd_id">',
      		'<span ng-if="hdr.hd_do_time == \'M\'">',
      			'Morning Readings: ',
      			'<a ng-href="#/daily_office/holy_day/{{hdr.hd_do_id}}/{{hdr.hd_do_time}}">',
      			'{{hdr.hd_do_name}}</a><br/>',
      		'</span>',
      		'<span ng-if="hdr.hd_do_time == \'E\'">',
      			'Evening Readings: ',
      			'<a ng-href="#/daily_office/holy_day/{{hdr.hd_do_id}}/{{hdr.hd_do_time}}">',
      			'{{hdr.hd_do_name}}</a><br/>',
      		'</span>',
      	'</span>',
      	'<br/>'
    ].join("")
    });
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.daily_office')
    .component('holyDayList',{
      controllerAs: 'hdl',
      controller: function($http,$stateParams,$cookieStore) {
      var vm = this;
      var formData = {
        today: $stateParams.today_date
      }

      var url = 'api/daily_office/holy_day_list';
      $http.post(url,formData).
        success(function(data, status, headers, config) {
          vm.hdl = data;
        }).
        error(function(data, status, headers, config) {
          $scope.message = status;
        });
    },
    template: [
      	'<span ng-repeat="hd in hdl.hdl">',
          '<holy_day id="hd.hd_do_id" type="list"></holy_day>',
      	'</span>'
    ].join("")
    });
})();

(function() {
  'use strict';

  angular
    .module('bcpApp')
    .controller('DailyOfficeHolyDayReadingCtrl', DailyOfficeHolyDayReadingController);

    DailyOfficeHolyDayReadingController.$inject = ['$http','$stateParams','$cookies','dataService','$log'];

    function DailyOfficeHolyDayReadingController($http,$stateParams,$cookies,dataService,$log) {
      var vm = this;

      var config = {
        method: 'post',
        url: 'api/daily_office/holy_day_readings',
        data: {
          hd_id: $stateParams.hd_id,
          hd_time: $stateParams.hd_time
        }
      }

      dataService.getData(config)
        .then(function(response) {
          vm.hdr = response;
        })
        .catch(function(err) {
          $log.warn(err);
        });
    }
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('noondayCollect', {
          controllerAs: 'nc',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/noonday_collect_es' : 'api/daily_office/noonday_collect';
            var config = {
              method: 'post',
              url: vm.url,
              data: {
                wr: $stateParams.which_rite
              }
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.noonday_collect = response[0];
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: '<span ng-bind-html="nc.noonday_collect | amenify"></span></p>'
      });
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('noondayScripture', {
          controllerAs: 'ns',
          controller: function($scope, $http, $stateParams, dataService ,$log, cookieService) {
            var vm = this;
            // $http config object
            vm.prefs = (cookieService.get('PREFS'));
            vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
            vm.url = (vm.prefs.language == true) ? 'api/daily_office/noonday_scripture_es' : 'api/daily_office/noonday_scripture';
            var config = {
              method: 'post',
              url: vm.url
            }
            dataService
              .getData(config)
              .then(function(response) {
                vm.noonday_scripture = response;
              })
              .catch(function(err) {
                $log.warn(err);
              });
          },
          template: [
            '<p>{{ns.noonday_scripture.text}}',
            '<br/><em>{{ns.noonday_scripture.reference}}</em></p>'
          ].join("")
      });
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficeOptionsCtrl', DailyOfficeOptionsCtrl);

    DailyOfficeOptionsCtrl.$inject = ['$scope','$http','dataService','$cookies'
      ,'$log','cookieService'];

    function DailyOfficeOptionsCtrl($scope,$http,dataService,$cookies,$log,cookieService) {
      var vm = this;

      vm.prefs = (cookieService.get('PREFS'));
      vm.timezone = (vm.prefs.timezone) ? vm.prefs.timezone : null;
      vm.language = (vm.prefs.language == true) ? "ES" : "EN";

      var config = {
        method: 'post',
        url: 'api/daily_office',
        data: {
          tz: vm.timezone
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.url_date = response.CC.today.replace(/\s/g,"_");
          vm.daily_office = response;
          //$cookies.put('CC',JSON.stringify(response.CC));
        })
        .catch(function(err) {
          $log.warn(err);
        });
    };

})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('prayerForMission', {
        bindings: {
          $stateParams: '<'
        },
        controllerAs: "pm",
        controller: function($stateParams,dataService,$log,cookieService) {
          var vm = this;
          vm.prefs = (cookieService.get('PREFS'));
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          vm.instr = (vm.prefs.language == true)
              ? "Aquí se añade una de las siguientes oraciones por la misión de la\n" +
              "Iglesia a menos que siga la Eucaristía o una forma de intercesión\n" +
              "general."
              : "Then, unless the Eucharist or a form of general intercession is to follow, the following prayer for mission is added.";
          vm.header = (vm.prefs.language == true) ? "Oración por la Misión" : "Prayer for Mission";
          vm.url = (vm.prefs.language == true) ? 'api/daily_office/prayer_for_mission_es' : 'api/daily_office/prayer_for_mission';
          // $http config object
          var config = {
            method: 'post',
            url: vm.url,
            data: {
              wr: $stateParams.which_rite,
              wp: $stateParams.which_prayer
            }
          }

          dataService
            .getData(config)
            .then(function(data) {
              vm.pm_text = data[0];
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: ["<p><em>{{pm.instr}}</em></p>",
"<strong>{{pm.header}}</strong><br/>",
"<span ng-bind-html='pm.pm_text | amenify'></span></p>"].join("")
      });
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.daily_office')
    .component('preferences', {
      controllerAs: 'prefs',
      controller: function(cookieService) {
        var vm = this;
        vm.preferences = cookieService.get('PREFS');
        vm.prefsSet = false;
        var count = 1;
        vm.defaults = cookieService.getDefaults();
        angular.forEach(vm.preferences, function(value,key) {
          // if even one item doesn't match the defaults, we know they are set
          if(!vm.prefsSet) {
            if(vm.preferences[key] != vm.defaults[key]) {
              vm.prefsSet = true;
            }
          }
        });
        vm.show_message = (vm.prefsSet == true) ? false : true;
        // reset the cookie expiration date here, if they have set
        // preferences before
        if(vm.prefsSet == true) {
          vm.curdate = new Date();
          vm.curdate.setMonth(vm.curdate.getMonth() + 1);
          angular.forEach(vm.preferences, function(value,key) {
            cookieService.set(key,value,'PREFS',vm.curdate);
          });
        }
      },
      template: [
        '<div class="alert alert-warning alert-dismissible" role="alert"',
        ' ng-show="prefs.show_message">',
        '<button type="button" class="close" data-dismiss="alert" ',
        'aria-label="Close"><span aria-hidden="true">&times;</span></button>',
        '<strong>Note!</strong> Default settings for this page (Readings by time of day, ',
        'and the Default Bible Version) are in effect.  If you would like to ',
        'use personal settings,',
        'please set your <a href="./#/preferences">preferences</a>.',
        '</div>'].join("")
    });
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .component('psalmReading', {
        bindings: {
          type: "@",
          parseType: '<',
          value: "<"
        },
        controllerAs: 'br',
        controller: function($scope, $http, $stateParams, dataService ,$log) {
          var vm = this;
          if (vm.type == 'm_psalms') {
            vm.reading_type = 'Morning Psalms';
          } else if (vm.type == 'e_psalms') {
            vm.reading_type = 'Evening Psalms';
          } else if (vm.type == 'hd_psalm') {
            vm.reading_type = 'Psalm Reading(s)';
          } else if (vm.type == 'psalm') {
            vm.reading_type = 'Psalm(s)';
          } else if (vm.type == 'search') {
            vm.reading_type = 'Search';
          }
        },
        templateUrl: 'public/templates/daily_office/html/psalm_reading.html'
    });
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficePsalterCtrl', DailyOfficePsalterCtrl);

    DailyOfficePsalterCtrl.$inject = ['$scope','$http','$log','dataService'];

    function DailyOfficePsalterCtrl($scope, $http, $log, dataService) {
      var vm = this;

      vm.getPsalms = getPsalms;
      vm.getPsalmByChapter = getPsalmByChapter;
      vm.psalmChapters = [];
      for (var i = 1; i <= 150; i++) {
        vm.psalmChapters.push(i.toString());
      }

      function getPsalms(whichDayTime,$log) {
        var split = whichDayTime.split("|");
        var day = split[0];
        var time = split[1];
        var config = {
          method: "post",
          url: "api/daily_office/psalms",
          data: {
            day: day,
            time: time,
            type: 'daytime'
          }
        }

        dataCall(config,$log);
      }

      function getPsalmByChapter(chapter,$log) {
        var config = {
          method: "post",
          url: "api/daily_office/psalms",
          data: {
            chapter: chapter,
            type: 'chapter'
          }
        }

        dataCall(config,$log);
      }

      function dataCall(config,$log) {
        dataService
          .getData(config)
          .then(function(response) {
            vm.psalmList = response;
          })
          .catch(function(err) {
            $log.warn(err)
          });
      }

    }

})();

(function() {
  'use strict';

  angular
    .module('bcpApp.daily_office')
    .controller('DailyOfficeReadingsCtrl', DailyOfficeReadingsController);

    DailyOfficeReadingsController.$inject = ['$http','$stateParams'
      ,'dataService','$cookies','cookieService','$anchorScroll','$location'];

    function DailyOfficeReadingsController($http,$stateParams,dataService
        ,$cookies,cookieService,$anchorScroll,$location) {
      // Object {yearNo: "2", do_id: "7R", dow: "0", wc: "0", today_date: "Sunday,_May_08,_2016"}
      var vm = this;
      vm.include = 'public/templates/daily_office/html/'+$stateParams.which_prayer
        +"_"+$stateParams.which_rite+'.html';
      vm.yearNo = $stateParams.yearNo;
      vm.do_id = $stateParams.do_id;
      vm.dow = $stateParams.dow;
      vm.which_prayer = $stateParams.which_prayer,
      vm.which_rite = $stateParams.which_rite,
      vm.wc = $stateParams.wc;
      vm.today_date = $stateParams.today_date.replace(/\_/g,' ');
      // set up the id to pass to the holy day checker
      // done here to so we pass a string id, and then we can use the
      // reusable holyDay component in the holyDayList component.
      var months = {"January":"1","February":"2","March":"3"
      ,"April":"4","May":"5","June":"6","July":"7"
      ,"August":"8","September":"9","October":"10"
      ,"November":"11","December":"12"}
      var splits = vm.today_date.split(" ");
      var month = months[splits[1]];
      var day = splits[2].replace(",","");
      var test_date = month+"-"+day;
      vm.holy_day_test_date = test_date;
      // get preferences and see if cookies are set.
      vm.prefs = (cookieService.get("PREFS"));
      vm.timezone = (vm.prefs.timezone) ? vm.prefs.timezone : null;
      vm.prayerAll = vm.prefs.prayerAll;
      if(vm.prefs.language == true) {
          vm.prefs.prayerAll = (vm.prefs.prayerAll == true) ? "Todas Las Lecturas" : "Lecturas por hora del día";
      } else {
          vm.prefs.prayerAll = (vm.prefs.prayerAll == true) ? "All Readings Selected" : "Readings by time of day";
      }

      vm.BCPPsalms = (vm.prefs.useBCPDO == true) ? true : false;
      // check if cookie data is set, and if not, get it from the data service
      vm.CC = ($cookies.get('CC'))
        ? JSON.parse($cookies.get('CC'))
        : dataService.getData({
          method: 'post',
          url: 'api/daily_office',
          data: {
            tz: vm.timezone,
            lang: (vm.prefs.language == true) ? "ES" : "EN"
          }})
          .then(function(response) {
            vm.CC = response.CC;
          });
      // get the readings for the prayer pages
      var config = {
        method: 'post',
        url: 'api/daily_office/readings',
        data: {
          year: $stateParams.yearNo,
          id: $stateParams.do_id,
          dow: $stateParams.dow,
          wp: $stateParams.which_prayer,
          wr: $stateParams.which_rite,
          v: vm.prefs.bibleVersion,
          BCPPsalms: vm.BCPPsalms
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.references = response;
          $location.hash('theTop');
          $anchorScroll();
        });

      vm.goToHash = function(id) {
        $location.hash(id);
        $anchorScroll();
      }
    }
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficeTodaysOfficeCtrl', DailyOfficeTodaysOfficeCtrl);

    DailyOfficeTodaysOfficeCtrl.$inject = ['$scope','$http','dataService'
      ,'$cookies','$log','$location','cookieService'];

    function DailyOfficeTodaysOfficeCtrl($scope,$http,dataService,$cookies
        ,$log,$location,cookieService) {
      var vm = this;
      // 1) cookieService returns a merged object of default values or the preferences
      // set on the preferences page
      vm.prefs = (cookieService.get('PREFS'));
      // the slideSwitch in the preferences page requires true/false settings, so we
      // need to look for the two items we need here and set it accordingly
      vm.timezone_offset = vm.prefs.timezone;
      var date = new Date();
      vm.current_time = date.getHours();
      // Prayers by time of day is the default, so switch to the prayer selected
      // otherwise, if they haven't selected it, then the default is morning prayer
      if(vm.prefs.prayerAll == false) {
        // prayerAll indicates "All Readings" so a false means they want two prayers
        vm.prefs.prayerDayTime = (vm.current_time < 12) ? 'morning_prayer' : 'evening_prayer';
      } else if(vm.prefs.prayerAll == true) {
        // prayerAll indicates "All readings" which means only one prayer per day
        vm.prefs.prayerDayTime = (vm.prefs.prayerDayTime == true) ? 'morning_prayer' : 'evening_prayer';
      }
      vm.prefs.prayerRite = (vm.prefs.prayerRite == true) ? 'rite_i' : 'rite_ii';
      // Need to check if language is set, and if so, overwrite the Rite
      if(vm.prefs.language == true) {
          vm.prefs.prayerRite = 'es';
      }
      vm.timezone = (vm.prefs.timezone) ? vm.prefs.timezone : null;
      // 2) get the CC, DO, and DOR data with one call
      var config = {
        method: 'post',
        url: 'api/daily_office',
        data: {
          tz: vm.timezone
        }
      }
      dataService
        .getData(config)
        .then(function(response) {
          vm.url_date = response.CC.today.replace(/\s/g,"_");
          // this response will contain all three
          // CC, DO and DOR objects
          vm.references = response;
          //$cookies.put('CC',JSON.stringify(response.CC));
          // now that we have the data we need, we can redirect to the regular
          // readings pages so that all stateParams are set
          var path = 'daily_office/'+vm.prefs.prayerDayTime+'/'+vm.prefs.prayerRite+'/'
            +response.DO.do_yearNo+'/'+response.DO.do_id+'/'
            +response.DO.do_dow+
            '/'+((response.DO.winter_collect) ? response.DO.winter_collect : 0)+'/'
            +vm.url_date;
          $location.path(path);
        })
        .catch(function(err) {
          $log.warn(err);
        });
    };

})();

(function () {
    'use strict';

    angular
      .module('bcpApp.daily_office')
      .controller('DailyOfficeTodaysOfficeReadingsCtrl', DailyOfficeTodaysOfficeReadingsCtrl);

    DailyOfficeTodaysOfficeReadingsCtrl.$inject = ['$scope','$http','dataService'
      ,'$cookies','$log','$location','cookieService'];

    function DailyOfficeTodaysOfficeReadingsCtrl($scope,$http,dataService,$cookies
        ,$log,$location,cookieService) {
      var vm = this;
      // 1) cookieService returns a merged object of default values or the preferences
      // set on the preferences page
      vm.prefs = (cookieService.get('PREFS'));
      // the slideSwitch in the preferences page requires true/false settings, so we
      // need to look for the two items we need here and set it accordingly
      vm.prefs.prayerDayTime = (vm.prefs.prayerDayTime == true) ? 'morning_prayer' : 'evening_prayer';
      vm.prefs.prayerRite = (vm.prefs.prayerRite == true) ? 'rite_i' : 'rite_ii';
      vm.timezone = (vm.prefs.timezone) ? vm.prefs.timezone : null;
      // 2) get the CC, DO, and DOR data with one call
      var config = {
        method: 'post',
        url: 'api/daily_office',
        data: {
          tz: vm.timezone
        }
      }
      dataService
        .getData(config)
        .then(function(response) {
          vm.url_date = response.CC.today.replace(/\s/g,"_");
          // this response will contain all three
          // CC, DO and DOR objects
          vm.references = response;
          //$cookies.put('CC',JSON.stringify(response.CC));
          // now that we have the data we need, we can redirect to the regular
          // readings pages so that all stateParams are set
          var path = 'daily_office/readings/rite_i/'
            +response.DO.do_yearNo+'/'+response.DO.do_id+'/'
            +response.DO.do_dow+
            '/'+((response.DO.winter_collect) ? response.DO.winter_collect : 0)+'/'
            +vm.url_date;
          $location.path(path);
        })
        .catch(function(err) {
          $log.warn(err);
        });
    };

})();

(function () {
    'use strict';

    angular
      .module('bcpApp.lectionary')
      .controller('LectionaryByDateCtrl', LectionaryByDateCtrl);

    LectionaryByDateCtrl.$inject = ['$scope','$http','dataService'
      ,'$cookies','$log','$location','cookieService','$stateParams'];

    function LectionaryByDateCtrl($scope,$http,dataService,$cookies
        ,$log,$location,cookieService,$stateParams) {
      var vm = this;
      // 1) cookieService returns a merged object of default values or the preferences
      // set on the preferences page
      vm.prefs = (cookieService.get('PREFS'));
      vm.readings_page = (vm.prefs.language == true) ? "readings_es" : "readings";
      vm.language = (vm.prefs.language == true) ? "ES" : "EN";
      // the slideSwitch in the preferences page requires true/false settings, so we
      // need to look for the two items we need here and set it accordingly
      vm.lpt = (vm.prefs.lectionaryPrayerType == true) ? 'T':'C';
      vm.prefs.lectionaryPrayerType = (vm.prefs.lectionaryPrayerType == true) ? 'Traditional' : 'Contemporary';
      vm.lt = (vm.prefs.lectionaryType == true) ? 'BCP':'RCL';
      if (vm.language == 'EN') {
          vm.prefs.lectionaryType = (vm.prefs.lectionaryType == true) ? 'BCP (1979)' : 'Revised Common Lectionary';
      } else {
          vm.prefs.lectionaryType = (vm.prefs.lectionaryType == true) ? '1979 Libro de Oración' : 'Leccionario Común Revisado';
      }
      // 2) get the CC, DO, and DOR data with one call
      var year = $stateParams.year ? $stateParams.year : null;
      var config = {
        method: 'post',
        url: 'api/lectionary',
        data: {
          year: year,
          lang: vm.language
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
            vm.l = response;
        })
        .catch(function(err) {
            $log.warn(err);
        });
    };

})();

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
				url: '/lectionary/holy_day/readings/:id/:prayer_type',
				template: '<div ng-include src="lhd_readings.include"></div>',
				controller: 'LectionaryHolyDayReadingsCtrl',
				controllerAs: 'lhd_readings'
			});
	}
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.lectionary')
      .component('lectionaryCollect', {
          controllerAs: 'cod',
          controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
          var vm = this;
          var wc = ($stateParams.wc) ? $stateParams.wc : null;
          var wr = ($stateParams.prayer_type == 'C') ? 'rite_ii':'rite_i';
          if( ($stateParams.id == 'CMII' || $stateParams.id == 'CMIII') ) {
            $stateParams.id = 'CM';
          }
          vm.prefs = (cookieService.get('PREFS'));
          vm.collect_header = (vm.prefs.language == true) ? 'Colecta del Día' : 'The Collect of the Day';
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          // $http config object
          var config = {
            method: 'post',
            url: 'api/daily_office/collect_of_day',
            data: {
              id: $stateParams.id,
              wr: wr,
              dow: 0,
              wc: wc,
              lang: vm.language
            }
          }
          dataService
            .getData(config)
            .then(function(response) {
              vm.collect_of_day = response.cod;
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: [
          '<p><strong><u>{{cod.collect_header}}</u></strong></p>',
          '<strong>{{cod.collect_of_day.name}}</strong><br/>',
          '<span ng-bind-html="cod.collect_of_day.text | amenify | orthis2br | or2br"></span></p>'
        ].join("")
      });

})();

(function() {
  'use strict';

  angular
    .module('bcpApp.lectionary')
    .component('lectionaryHolyDay',{
      bindings: {
        id: "<",
        type: "@"
      },
      controllerAs: 'lhd',
      controller: function($http,$stateParams,$cookieStore, cookieService) {
      var vm = this;
      vm.prefs = (cookieService.get('PREFS'));
      vm.lpt = (vm.prefs.lectionaryPrayerType == true) ? 'T':'C';
      var formData = {
        today: vm.id
      }

      var url = 'api/lectionary/holy_days';
      $http.post(url,formData).
        success(function(data, status, headers, config) {
          vm.hd_id = data;
        }).
        error(function(data, status, headers, config) {
          $scope.message = status;
        });
    },
    template: [
      	'<p ng-if="lhd.hd_id.length > 0 && lhd.type == \'lectionary\'">Today is a Holy Day and provides the following',
      		' additional readings.</p>',
        '<p ng-if="lhd.hd_id.length > 0 && lhd.type == \'list\'">',
        '<strong><a ng-href="#/lectionary/holy_day/readings/{{lhd.hd_id[0].hd_id}}/{{lhd.lpt}}">',
        '{{lhd.hd_id[0].hd_name}}: {{lhd.hd_id[0].hd_id}}</a></strong>',
          '</p>',
    ].join("")
    });
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.lectionary')
    .component('lectionaryHolyDayList',{
      controllerAs: 'lhdl',
      controller: function($http,$stateParams,$cookieStore) {
      var vm = this;
      var formData = {
        today: $stateParams.today_date
      }

      var url = 'api/lectionary/holy_day_list';
      $http.post(url,formData).
        success(function(data, status, headers, config) {
          vm.hdl = data;
        }).
        error(function(data, status, headers, config) {
          $scope.message = status;
        });
    },
    template: [
      	'<span ng-repeat="hd in lhdl.hdl">',
          '<lectionary-holy-day id="hd.hd_id" type="list"></lectionary-holy-day>',
      	'</span>'
    ].join("")
    });
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.lectionary')
    .controller('LectionaryHolyDayReadingsCtrl', LectionaryHolyDayReadingsCtrl);

    LectionaryHolyDayReadingsCtrl.$inject = ['$http','$stateParams'
      ,'dataService','$cookies','cookieService'];

    function LectionaryHolyDayReadingsCtrl($http,$stateParams,dataService
        ,$cookies,cookieService) {
      // /lectionary/readings/:lectionary_year/:id/:lectionary_type
      // /:prayer_type/:liturgical_color/{lectionary_date}
      var vm = this;
      // 1) cookieService returns a merged object of default values or the preferences
      // set on the preferences page
      vm.prefs = (cookieService.get('PREFS'));
      vm.readings_page = (vm.prefs.language == true) ? "readings_es" : "readings";
      vm.language = (vm.prefs.language == true) ? "ES" : "EN";
      // the slideSwitch in the preferences page requires true/false settings, so we
      // need to look for the two items we need here and set it accordingly
      vm.lt = (vm.prefs.lectionaryType == true) ? 'BCP':'RCL';
      vm.prayer_type = $stateParams.prayer_type;
      vm.BCPPsalms = (vm.prefs.useBCPLect == true) ? true : false;
      if (vm.language == 'EN') {
        vm.prefs.lectionaryType = (vm.prefs.lectionaryType == true) ? 'BCP (1979)' : 'Revised Common Lectionary';
      } else {
        vm.prefs.lectionaryType = (vm.prefs.lectionaryType == true) ? '1979 Libro de Oración' : 'Leccionario Común Revisado';
      }

      vm.id = $stateParams.id;
      vm.include = 'public/templates/lectionary/html/lectionary_holy_day_readings.html';

      // get the readings for the lectionary readings and spit them out
      var config = {
        method: 'post',
        url: 'api/lectionary/holy_day_readings',
        data: {
          id: $stateParams.id,
          wl: vm.lt,
          version: vm.prefs.bibleVersion,
          BCPPsalms: vm.BCPPsalms
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.references = response;
        });
    }
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.lectionary')
      .component('lectionaryPreface', {
          controllerAs: 'preface',
          controller: function($scope, $http, $stateParams,dataService,$log,cookieService) {
          var vm = this;
          var wp = ($stateParams.prayer_type == 'C') ? 'rite_ii':'rite_i';
          vm.prefs = (cookieService.get('PREFS'));
          vm.preface_header = (vm.prefs.language == true) ? 'Prefacio' : 'The Preface';
          vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
          // $http config object
          var config = {
            method: 'post',
            url: 'api/lectionary/preface',
            data: {
              id: $stateParams.id,
              wp: wp,
              lang: vm.language
            }
          }
          dataService
            .getData(config)
            .then(function(response) {
              vm.preface = response;
            })
            .catch(function(err) {
              $log.warn(err);
            });
        },
        template: [
          '<p><strong><u>{{preface.preface_header}}:</u></strong></p>',
          '<span ng-repeat="pref in preface.preface.p track by $index">',
          '<strong>{{pref.pref_name}}</strong><br/>',
          '<span ng-if="pref.pref_instr"><em>',
          '{{pref.pref_instr}}</em><br/></span>',
          '<span ng-bind-html="pref.pref_text | amenify | or2br"></span></p>',
          '<span ng-if="!$last">&mdash;OR&mdash;<br/></span>',
          '</span>'
        ].join("")
      });

})();

(function() {
  'use strict';

  angular
    .module('bcpApp.lectionary')
    .controller('LectionaryReadingsCtrl', LectionaryReadingsCtrl);

    LectionaryReadingsCtrl.$inject = ['$http','$stateParams'
      ,'dataService','$cookies','cookieService','$anchorScroll','$location'];

    function LectionaryReadingsCtrl($http,$stateParams,dataService
        ,$cookies,cookieService,$anchorScroll,$location) {
      // /lectionary/readings/:lectionary_year/:id/:lectionary_type
      // /:prayer_type/:liturgical_color/{lectionary_date}
      var vm = this;
        vm.prefs = (cookieService.get('PREFS'));
        vm.language = (vm.prefs.language == true) ? "ES" : "EN";
      vm.lectionary_page = $stateParams.readings_page;
      vm.lectionary_year = $stateParams.lectionary_year;
      vm.id = $stateParams.id;
      vm.lectionary_type = $stateParams.lectionary_type;
      vm.prayer_type = $stateParams.prayer_type;
      vm.liturgical_color = $stateParams.liturgical_color;
      vm.lectionary_date = ($stateParams.lectionary_date) ? $stateParams.lectionary_date.replace(/\_/g,' ') : '';
      vm.include = 'public/templates/lectionary/html/lectionary_'+vm.lectionary_page+'.html';

      // set up the id to pass to the holy day checker
      // done here to so we pass a string id, and then we can use the
      // reusable holyDay component in the holyDayList component.
      var months = {"January":"1","February":"2","March":"3"
      ,"April":"4","May":"5","June":"6","July":"7"
      ,"August":"8","September":"9","October":"10"
      ,"November":"11","December":"12"}
      var splits = vm.lectionary_date.split(" ");
      var month = months[splits[1]];
      var day = splits[2].replace(",","");
      var test_date = month+"-"+day;
      vm.holy_day_test_date = test_date;
      // Cookie content is too large, so I'm forcing it to get a new CC object
      vm.CC = ($cookies.get('CC'))
        ? JSON.parse($cookies.get('CC'))
        : dataService.getData({
          method: 'post',
          data: {
              lang: vm.language
          },
          url: 'api/lectionary'})
          .then(function(response) {
            vm.CC = response.CC;
            angular.forEach(vm.CC.orderedSundays, function(value,key) {
              if(value.id == vm.id) {
                vm.CC.upcoming_sunday_name = value.name;
              }
            });
          });
      // get preferences and see if cookies are set.
      vm.prefs = (cookieService.get("PREFS"));
      vm.BCPPsalms = (vm.prefs.useBCPLect == true) ? true : false;
      // technically, the preferences were checked before the redirect, so the
      // only thing we need here will be the bible version people choose.
      // get the readings for the lectionary readings and spit them out
      var config = {
        method: 'post',
        url: 'api/lectionary/readings',
        data: {
          year: $stateParams.lectionary_year,
          id: $stateParams.id,
          wl: $stateParams.lectionary_type,
          wp: $stateParams.prayer_type,
          v: vm.prefs.bibleVersion,
          BCPPsalms: vm.BCPPsalms
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.references = response;
          $location.hash('theTop');
          $anchorScroll();
        });
    }
})();

(function () {
    'use strict';

    angular
      .module('bcpApp.lectionary')
      .controller('UpcomingLectionaryCtrl', UpcomingLectionaryCtrl);

    UpcomingLectionaryCtrl.$inject = ['$scope','$http','dataService'
      ,'$cookies','$log','$location','cookieService'];

    function UpcomingLectionaryCtrl($scope,$http,dataService,$cookies
        ,$log,$location,cookieService) {
      var vm = this;
      // 1) cookieService returns a merged object of default values or the preferences
      // set on the preferences page
      vm.prefs = (cookieService.get('PREFS'));
      // the slideSwitch in the preferences page requires true/false settings, so we
      // need to look for the two items we need here and set it accordingly
      vm.prefs.lectionaryPrayerType = (vm.prefs.lectionaryPrayerType == true) ? 'T' : 'C';
      vm.prefs.lectionaryType = (vm.prefs.lectionaryType == true) ? 'BCP' : 'RCL';
      vm.timezone = (vm.prefs.timezone) ? vm.prefs.timezone : null;
      vm.language = (vm.prefs.language == true) ? 'ES' : 'EN';
      // 2) get the CC, DO, and DOR data with one call
      var config = {
        method: 'post',
        url: 'api/lectionary',
        data: {
          tz: vm.timezone
        }
      }

      dataService
        .getData(config)
        .then(function(response) {
          vm.url_date = response.CC.this_coming_sunday.replace(/\s/g,"_");
          var readings_page = (vm.language == 'EN') ? "readings":"readings_es";
          // this response will contain the CC object
          //$cookies.put('CC',JSON.stringify(response.CC));
          // now that we have the data we need, we can redirect to the regular
          // readings pages so that all stateParams are set
          var path = 'lectionary/'+readings_page+'/'+response.CC.lectionary_year
          +'/'+response.CC.upcoming_sunday_id
          +'/'+vm.prefs.lectionaryType+'/'+vm.prefs.lectionaryPrayerType
          +'/'+response.CC.upcoming_sunday_color
          +'/'+vm.url_date
          $location.path(path);
          // console.log(response);
        })
        .catch(function(err) {
          $log.warn(err);
        });
    };

})();

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

'use strict';
angular
  .module('angular-jquery-datepicker', [])
  .factory('datepicker', [function () {
    return $.datepicker;
  }])
  .provider('$datepicker', function () {
    return {
        setDefaults: function (language) {
            //$.datepicker.setDefaults($.datepicker.regional[language]);
        	$.datepicker.setDefaults( $.datepicker.regional[ "en" ] = {
		    monthNames: ['January','February','March','April','May','June',
		        'July','August','September','October','November','December'],
		    monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // For formatting
		    dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], // For formatting
		    dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], // For formatting
		    dayNamesMin: ['Su','M','T','W','Th','F','Sa'], // Column headings for days starting at Sunday
		    dateFormat: 'yy-mm-dd', // Swedish date format
		    showMonthAfterYear: true,
		    yearSuffix: '',
		    firstDay: 0
        	});
        },
        $get: function () {
            return {

            }
        }
    }
  }).directive('datepicker', function () {
    return {
        restrict: 'A',
        link: function (scope, elem, attr, ctrl) {
            elem.datepicker(
                {
                    buttonImage: "public/views/partials/forms/img/Calendar.png",
                    buttonImageOnly: true
                });
        }
    };
});

(function () {
  'use strict';

  angular
    .module('bcpApp.slideSwitch',[])
    .component('slideSwitch',{
      bindings: {
        which: "@",
        onLabel: '@',
        offLabel: '@',
        knobLabel: '@',
        initial: '<'
      },
      controllerAs: 'sw',
      controller: function($scope) {
        var vm = this;
        vm.toggle = function() {
          $scope.$parent.prefs.setPrefs(vm.which,!vm.switcher);
          vm.switcher = !vm.switcher;
      };
    },
    template: ['<div class="switch" ng-click="sw.toggle()" data-size="small">',
    '<div class="switch-animate" ng-model="sw.switcher" ng-init="sw.switcher=sw.initial"',
    'ng-class="{\'switch-on\': sw.switcher, \'switch-off\': !sw.switcher}">',
    '<span class="switch-left" ng-bind="sw.onLabel"></span><span class="knob" ng-bind="sw.knobLabel">',
    '</span><span class="switch-right" ng-bind="sw.offLabel"></span></div></div>'
    ].join("")
  });
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.textSizeSlider', [])
    .component('textSizeSlider', {
        bindings: {
          min: '@',
          max: '@',
          unit: '@',
          value: '@',
          step: '@'
        },
        controllerAs: 'ts',
        controller: function($scope,$cookies) {
          var vm = this;
          vm.textSize = vm.value;
          vm.setFontSize = function(textSize) {
            // cookie expiration
            var curdate = new Date();
            curdate.setMonth(curdate.getMonth() + 1);
            $cookies.put("FONTSIZE",textSize, {
              expires: curdate
            });
          }

          $scope.$watch('ts.textSize', function (size) {
            document.querySelector("#sample").style.fontSize = size + vm.unit;
          });
      },
      template: ['<div class="text-size-slider">',
      '<span class="font-sizer sm">A</span>',
      '<span class="font-sizer md">A</span>',
      '<span class="font-sizer lg">A</span><br/>',
      '<input type="range" min="{{ ts.min }}" max="{{ ts.max }}" step="{{ ts.step || 0 }}"',
      ' ng-model="ts.textSize" class="slider" value="{{ ts.value }}" ',
      ' ng-change="ts.setFontSize(ts.textSize);" /></div>',
      '<div id="sample"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div>'
      ].join("")
    });
})();

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
(function() {
  angular
  .module('bcpApp.cookieService')
  .factory('cookieService', cookieService);

  cookieService.$inject = ['$cookies'];

  function cookieService($cookies) {
    return {
      get: get,
      set: set,
      getDefaults: getDefaults
    }

    function get(cookieName) {
      var prefs = $cookies.getObject(cookieName);
      var defaults = getDefaults();
      var cookieObj = Object.assign(defaults,prefs);
      if(cookieObj) {
        return cookieObj;
      } else {
        return {};
      }
    }

    function set(name,value,cookieName,expiration) {
      var currentPrefs = get(cookieName);
      var newPref = {};
      newPref[name] = value;
      var newObj = Object.assign(currentPrefs,newPref);
      $cookies.put(cookieName,JSON.stringify(newObj), {
          expires:expiration
      });
    }

    function getDefaults() {
      var prefTypes = {
        'bibleVersion':'NRSV'
        ,'prayerRite':false// rite ii
        ,'prayerDayTime':true // morning
        ,'prayerAll':false // means time of day readings
        ,'lectionaryType':false // RCL
        ,'lectionaryPrayerType':false // Contemporary
        ,'timezone':'0.0' // GMT
        ,'language':false // Language (false=English; true = Espanol)
      };
      return prefTypes;
    }
  }
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.dataService')
    .factory('dataService', dataService);

    dataService.$inject = ['$http','$q'];

    function dataService($http,$q) {
      return {
        getData: getData
      }

      function getData(config) {
        return $http(config)
          .then(returnResponseData)
          .catch(returnErrorStatus);
    }

    function returnResponseData(response) {
      return response.data;
    }

    function returnErrorStatus(response) {
      var message = "Data call to "+response.config.url+" failed";
      if(response.status && response.statusText) {
        message = message + '\n' + response.status + ': ' + response.statusText;
      }
      response = message;
      return $q.reject(response);
    }
  }
})();

(function() {
  'use strict';

  angular
    .module('bcpApp.exceptionOverride')
    .factory('$exceptionHandler', exceptionOverride);

  exceptionOverride.$inject = ['$log'];

  function exceptionOverride($log) {
    $log.warn(exception,cause);
    return function(exception, cause) {
      var message = {
        "exception": exception,
        "cause": cause
      }
      return message;
    }
  }
})();
