<?php

require_once 'config.php';

/* copperConfig::set('customsJs', array('tournment/main.js','personalize/main.js'));
  copperConfig::set('customsCss', array('tournment.css')); */


$view = new copperView('bodys/test.php');
$view->welcome = 'Welcome, your copperFramework is succesful installed.';
$view->render();