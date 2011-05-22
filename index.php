<?php

require_once 'config.php';

copperConfig::set('customsJs', array('tournment/main.js','personalize/main.js'));
copperConfig::set('customsCss', array('tournment.css'));

$model = copperConfig::get('model');
$model instanceof copperModel;

$requestIds = copperUtils::valid($_REQUEST['request_ids'], false);
if($requestIds){
  copperUtils::redirectJs(copperConfig::get('canvasUrl').'requests.php');
  die;
}

copperConfig::incTemplate('head.php');
copperConfig::incTemplate('bodys/land.php');
copperConfig::incTemplate('foot.php');
