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
        ,'prayerAll':false // means only readings by day
        ,'lectionaryType':false // RCL
        ,'lectionaryPrayerType':false // Contemporary
        ,'timezone':'0.0' // GMT
        ,'language':false // Language (false=English; true = Espanol)
      };
      return prefTypes;
    }
  }
})();
