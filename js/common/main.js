var facebook  = {};
var main      = {};
var flash     = {};

facebook.data = null;

var isFBConnect   = false;
var fbUid         = null;
var fbName        = null;
var fbFriends     = null;
var fbAccessToken = null;

var accent_map = {
  'á':'a',
  'é':'e',
  'í':'i',
  'ó':'o',
  'ú':'u',
  'Á':'A',
  'É':'E',
  'Í':'I',
  'Ó':'O',
  'Ú':'U'
};

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

function CufonReplaces() {
  Cufon.replace('.font_geoslab', {
    fontFamily: 'GeoSlab703 Md BT'
  });

  Cufon.replace('.font_myriadpro, h1', {
    fontFamily: 'Myriad Pro',
    hover: true
  });

  Cufon.replace('.font_helveticabold', {
    fontFamily: 'Helvetica Bold',
    hover: true
  });
}

function accent_fold (s) {
  if (!s) {
    return '';
  }
  var ret = '';
  for (var i = 0; i < s.length; i++) {
    ret += accent_map[s.charAt(i)] || s.charAt(i);
  }
  return ret;
}

var mask = {
  $element: function() {
    return $('#loadingMask');
  },
  show: function() {
    this.$element().show();
  },
  hide: function() {
    this.$element().hide();
  }
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
        perms: 'photo_upload,user_photos,email,friends_photos,user_photo_video_tags,friends_photo_video_tags'
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

facebook.getFriends = function() {
  if(typeof(myFriends) == "undefined") {
    return;
  }
  fbFriends = myFriends.data;
  facebook.chooseFriend();
};

facebook.chooseFriend = function() {};

main.copperDialogClose = function(html,callback) {
  if(typeof(callback)== "undefined") {
    callback = function() {};
  }
  var actions = {
    'cancel': {
      'name': 'Cerrar',
      'callback': callback
    },
    'accept': false
  }
  main.copperDialog(html,actions)
};

main.copperDialog = function(text, actions, closeButton){
  var template = $("#templates").clone().find(".dialog");
  template.find(".content p").html(text);

  if(closeButton == true){
    template.find(".close_button").show();
  }else{
    template.find(".close_button").hide();
  }


  var acceptButton  = template.find(".actions").find(".accept");
  var cancelButton = template.find(".actions").find(".cancel");
  var closeButtonElement = template.find(".close_button");

  closeButtonElement.click(function(){
    console.log('closeButtonElement');
    template.hide();
  });

  if(typeof(actions) !== "undefined") {
    if(typeof(actions.accept) !== "undefined") {
      if(actions.accept) {
        acceptButton.html(actions.accept.name);
        acceptButton.click(function(){
          template.hide();
          actions.accept.callback();
        });
      } else {
        acceptButton.remove();
      }
    }
    if(typeof(actions.cancel) !== "undefined") {
      if(actions.cancel) {
        cancelButton.html(actions.cancel.name);
        cancelButton.click(function(){
          template.hide();
          actions.cancel.callback();
        });
      } else {
        cancelButton.remove();
      }
    }
  }else{
    acceptButton.html('Aceptar');
    acceptButton.click(function(){
      template.hide();
    });

    cancelButton.remove();
  }

  var dialog = $("#wrap").append(template);

  dialog.show();
};

flash.open = function(teamId) {
  $.fancybox({
    "width": 670,
    "height": 516,
    "type": "iframe",
    "href": CALLBACK_URL + "flash.php?teamId=" + teamId,
    "showCloseButton": false,
    "padding": 0,
    "scrolling": 'no',
    "hideOnOverlayClick": false,
    "overlayColor": "#000",
    "overlayOpacity": "0.75"
  });
}

flash.close = function() {
  $.fancybox.close();
}

$(document).ready(function(){
  CufonReplaces();
});