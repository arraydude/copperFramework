<?php

/**
 * copperUtils
 *
 * some util methods
 *
 * @package copperFramework
 * @version 1.0
 */
class copperUtils {

  public static function valid(&$var, $return = FALSE) {
    return!empty($var) ? $var : $return;
  }

  public static function vd($data, $hidden = FALSE) {
    echo $hidden ? '<!--' : '';
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    echo $hidden ? '-->' : '';
  }

  public static function redirectJs($url) {
    echo "<script type='text/javascript'>";
    echo "top.location.href='$url'";
    echo "</script>";
  }

  public static function setJsVars($nameVar, array $data) {
    $val = json_encode($data);
    echo "\n<script type='text/javascript'>";
    echo "var {$nameVar}={$val}";
    echo "</script>\n";
  }

  public static function urlToCanvas($link, $params = array()) {
    $params['signed_request'] = $_REQUEST['signed_request'];
    $url = copperConfig::get('callbackUrl') . $link . "?" . http_build_query($params);
    return $url;
  }

  public static function entities($text) {
    return htmlentities($text, ENT_COMPAT, 'utf-8');
  }

}
