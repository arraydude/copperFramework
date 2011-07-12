var facebook  = {};
var main      = {};

facebook.data = null;

var isFBConnect   = false;
var fbUid         = null;
var fbName        = null;
var fbFriends     = null;
var fbAccessToken = null;

if(typeof(console) == "undefined") {
  var fn = function(){};
  var console = {
    log: fn,
    error: fn ,
    warn: fn,
    info: fn,
    time: fn,
    timeEnd: fn
  };
}

facebook.successfulLogin = function(){
  fbAccessToken = FB.getSession().access_token;
  isFBConnect = true;
  facebook.me();
}

var afterFbLogin = function() {
  facebook.successfulLogin();
  facebook.me();
  if (typeof(callback) != "undefined") {
    console.log('Llamando callback');
    callback();
  }
}

facebook.login = function(callback) {
  FB.getLoginStatus(function(response) {
    if (response.session) {
      afterFbLogin();
    } else {
      FB.login(function(response) {
        if(response.session) {
          if(response.perms){
            afterFbLogin();
          }
        }
      }, {
        perms: FACEBOOK_PERMS
      });
    }
  });
};

facebook.me = function() {
  FB.api('/me', function(response) {
    facebook.data = response;
    fbUid = response.id;
    fbName = response.name;
    facebook.getFriends();
  });
}

facebook.logout = function() {
  FB.logout(function() {
    location.reload();
  });

};

$(document).ready(function(){

});