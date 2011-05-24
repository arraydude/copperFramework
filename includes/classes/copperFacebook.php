<?php
/**
 * copperFacebook
 *
 * Factory the facebook api
 *
 * @package    copperFramework
 * @author     Nahuel Rosso
 */
class copperFacebook {

  private static function instance($facebook = null, $signedRequest = null, $session = null, $me = null) {
    $instance = new stdClass();
    $instance->facebook = $facebook;
    $instance->signedRequest = $signedRequest;
    $instance->session = $session;
    $instance->me = $me;

    return $instance;
  }

  /**
   * Factory the API
   *
   * @param <type> $params
   * @param <type> $loginParams
   * @return <type>
   */
  public static function factory($params = array(), $loginParams = array()) {
    $instance = self::instance();
    $init = array(
        'appId' => copperConfig::get('appId'),
        'secret' => copperConfig::get('appSecret'),
        'cookie' => true,
        'domain' => copperConfig::get('canvasUrl')
    );


    $init = array_merge($init, $params);

    $instance->facebook = new Facebook($init);
    $instance->signedRequest = $instance->facebook->getSignedRequest();
    $instance->fbSession = $instance->facebook->getSession();

    $initLogin = array(
        "canvas" => 1,
        "fbconnect" => 0,
        "display" => "page"
    );
	
    $initLogin = array_merge($initLogin, $loginParams);
    
    $fbSession = $instance->facebook->getSession();
    if (!$fbSession) {
      copperUtils::redirectJs($instance->facebook->getLoginUrl($initLogin));
      die();
    }

    $sessionIdx = '__FB1212_' . $fbSession['uid'];
    if(isset($_SESSION[$sessionIdx])) {
      $me = $_SESSION[$sessionIdx];
    } else {
      $me = $_SESSION[$sessionIdx] = $instance->facebook->api("/me");
    }
    $instance->me = $me;
    return $instance;
  }

  /**
   * Delete an appRequest
   *
   * @param <type> $requestId
   * @param <type> $appToken
   * @return <type>
   */
  public static function deleteRequest($requestId, $appToken){
    $deleted = file_get_contents("https://graph.facebook.com/$requestId?access_token=$appToken&method=delete"); // Should return true on success

    return $deleted;
  }
  

}
