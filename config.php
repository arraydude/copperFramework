<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set("America/Buenos_Aires");


$copperConfig = array(
    'appName' => 'Pilsen Cup',
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
    'stylesVersion' => '0.02',
    'jsVersion' => '0.04',
    'imgsVersion' => '0.02',
    'swfsVersion' => '0.02',
    'lang' => 'es',
    // youtube upload settings
    'youtubeDeveloperKey' =>  'AI39si6MAsVFqtkAmh3Ny8scJpmJuQnywI76s7fkUhdj5OtZvmS6AqCCQRQUC2W7eDLdqLCJmQ86nImhBj9_Ye73EKkspx2WwQ',
    'youtubeUsername' => 'pilsencup',
    'youtubePassword' => 'pilsencup432',
);

$customConfig = realpath('.') . DIRECTORY_SEPARATOR . 'config-custom.php';
if (!file_exists($customConfig)) {
  die('Create the file `' . basename($customConfig) . '` from  the same file -template and put your custom configurations there.' . "\n");
}
require_once($customConfig);
$copperConfig = array_merge($copperConfig, $copperConfigCustom);
session_start();
require_once(str_replace(array('///', '//'), "/", $copperConfig['classes'] . '/copperConfig.php'));
copperConfig::init($copperConfig);
unset($copperConfig, $customConfig);
// warnings and error to log file.
ini_set('error_log',copperConfig::get('log'));

require_once copperConfig::get('lib') . '/phpmailer/class.phpmailer.php';

require_once copperConfig::get('lib') . '/facebook/facebook.php';
try {
  $fbInstance = copperFacebook::factory(array(), array('req_perms' => 'photo_upload,user_photos,email,friends_photos,user_photo_video_tags,friends_photo_video_tags'));
} catch(Exception $e) {
  copperConfig::doError('Error cargando facebook: ' . $e->getMessage());
  copperConfig::doError($e->getTraceAsString());
  if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(array('error' => true, 'msg'=>'Imposible cargar Session'));
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
