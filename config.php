<?php

/**
 * Kernel configuration file
 * 
 * please dont touch this if you are not sure what you doing
 *
 * @author Emilio Astarita
 * @package copperFramework
 * @version 1.0
 * @todo This need a cleaning
 */
ini_set('display_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set("America/Buenos_Aires");

session_start();

$copperConfig = array(
    'appName' => 'copperFramework',
    'includes' => 'includes',
    'lib' => 'includes/lib',
    'classes' => 'includes/classes',
    'templates' => 'templates',
    'public' => '/',
    'path' => realpath('.'),
    'uploadPath' => realpath('.') . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR,
    'uploadPublic' => 'upload',
    'log' => 'log/log.txt',
    'stylesDir' => 'css',
    'jsDir' => 'js',
    'stylesVersion' => '0.01',
    'jsVersion' => '0.01',
    'imgsVersion' => '0.01',
    'swfsVersion' => '0.01',
    'lang' => 'es',
    // youtube upload settings
    'youtubeDeveloperKey' => '',
    'youtubeUsername' => '',
    'youtubePassword' => '',
);

$customConfig = realpath('.') . DIRECTORY_SEPARATOR . 'config-custom.php';

if (!file_exists($customConfig)) {
  die('Create the file `' . basename($customConfig) . '` from  the same file -template and put your custom configurations there.' . "\n");
}

require_once($customConfig);

$copperConfig = array_merge($copperConfig, $copperConfigCustom);

require_once(str_replace(array('///', '//'), "/", $copperConfig['classes'] . '/copperConfig.php'));
copperConfig::init($copperConfig);
unset($copperConfig, $customConfig);

// warnings and error to log file.
ini_set('error_log', copperConfig::get('log'));

require_once copperConfig::get('lib') . '/phpmailer/class.phpmailer.php';

require_once copperConfig::get('lib') . '/facebook/facebook.php';

if (copperConfig::get('facebookActivate')) {
  try {
    $fbInstance = copperFacebook::factory(array(), array('req_perms' => copperConfig::get('facebookPerms')));
  } catch (Exception $e) {
    copperConfig::doError('Error cargando facebook: ' . $e->getMessage());
    copperConfig::doError($e->getTraceAsString());
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      echo json_encode(array('error' => true, 'msg' => 'Imposible cargar Session'));
    } else {
      copperConfig::incTemplate('head.php');
      copperConfig::incTemplate('bodys/error500.php');
      copperConfig::incTemplate('foot.php');
    }
    die;
  }

  copperConfig::set('fbInstance', $fbInstance);
  $model = new copperModel($fbInstance);
  copperConfig::set('model', $model);
}

copperConfig::set('visibleVars', $copperConfigVisible);