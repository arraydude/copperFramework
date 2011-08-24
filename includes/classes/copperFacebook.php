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

  private static function instance($facebook = null, $signedRequest = null, $user = null, $me = null) {
    $instance = new stdClass();
    $instance->sdk = $facebook;
    $instance->signedRequest = $signedRequest;
    $instance->user = $user;
    $instance->me = $me;

    return $instance;
  }

  /**
   * factory
   *
   * Factory the API
   *
   * @param <type> $params
   * @param <type> $loginParams
   * @return <type>
   */
  public static function factory($params = array(), $needLogin = FALSE, $loginParams = array()) {
    $instance = self::instance();
    $init = array(
        'appId' => copperConfig::get('appId'),
        'secret' => copperConfig::get('appSecret')
    );


    $init = array_merge($init, $params);

    $instance->sdk = new Facebook($init);
    $instance->signedRequest = $instance->sdk->getSignedRequest();
    $instance->user = $instance->sdk->getUser();


    if ($needLogin) {
      if (!$instance->user) {
        copperUtils::redirectJs($instance->sdk->getLoginUrl($loginParams));
        die();
      }
    }

    if ($instance->user) {
      $sessionIdx = '__FB1212_' . $instance->user;
      if (isset($_SESSION[$sessionIdx])) {
        $me = $_SESSION[$sessionIdx];
      } else {
        $me = $_SESSION[$sessionIdx] = $instance->sdk->api("/me");
      }
      $instance->me = $me;
    }

    return $instance;
  }

  /**
   * deleteRequest
   *
   * Delete an appRequest
   *
   * @param <type> $requestId
   * @param <type> $appToken
   * @return <type>
   */
  public static function deleteRequest($requestId, $appToken) {
    $deleted = file_get_contents("https://graph.facebook.com/$requestId?access_token=$appToken&method=delete"); // Should return true on success

    return $deleted;
  }

  /**
   * getUidsFromRequestIds
   * 
   * Obtain the uids from a gived requestids.
   * @param array $requestIds
   * @return array $uids 
   */
  public static function getUidsFromRequestIds($requestIds) {
    $appid = copperConfig::get('appId');
    $secret = copperConfig::get('appSecret');
    $uids = array();

    if (copperUtils::valid($requestIds, false)) {

      $app_token = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id=' . $appid . '&client_secret=' . $secret . '&grant_type=client_credentials'); //Get application token

      foreach ($requestIds as $key => $sent) {

        $request = file_get_contents('https://graph.facebook.com/' . $sent . '?' . $app_token);

        $request = json_decode($request);

        $uids[] = $request->to->id;
      }
    }

    return $uids;
  }

}
