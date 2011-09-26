<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"> 
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo copperConfig::get('appName'); ?></title>
    <?php copperConfig::incCss('main.css'); ?>
    <?php copperConfig::incJs('../jsVars.php'); ?>
    <?php
        $customsCss = copperUtils::valid(copperConfig::get('customsCss'), array());
        foreach($customsCss as $css){
          copperConfig::incCss($css);
        }

        if(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 7.0") !== false) {
          copperConfig::incCss('ie7.css');
        }
        if(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 8.0") !== false) {
          copperConfig::incCss('ie8.css');
        }

        $customsJs = copperUtils::valid(copperConfig::get('customsJs'), array());
        foreach($customsJs as $js):
          copperConfig::incJs($js);
        endforeach;
    ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <?php copperConfig::incJs('common/main.js'); ?>
  </head>
  <body>
    <div id="fb-root"></div>
    <script type="text/javascript">
      var CALLBACK_URL  = '<?php echo copperConfig::get('callbackUrl')?>';
      var CANVAS_URL  = '<?php echo copperConfig::get('canvasUrl')?>';
      <?php if(copperConfig::get('facebookActivate')):?>
        var SIGNED_REQUEST  = '<?php echo htmlentities($_REQUEST['signed_request']); ?>';
      <?php endif;?>
    </script>
    <?php
      $fbInstance = copperConfig::get('fbInstance');
      if(!empty($fbInstance)) {
        try {
          $friends  = $fbInstance->facebook->api('/me/friends');
        } catch (Exception $exc) {
          copperConfig::doError("Error al intentrar inicializar el /me/ : ". $exc->getMessage());
          copperConfig::doError("Error al intentrar inicializar el /me/ con trace: ". $exc->getTraceAsString());
        }
        if(!empty($friends)) {
          copperUtils::setJsVars('myFriends', $friends);
        }
      }
    ?>
