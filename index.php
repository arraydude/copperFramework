<?php

require_once 'config.php';

/* copperConfig::set('customsJs', array('tournment/main.js','personalize/main.js'));
  copperConfig::set('customsCss', array('tournment.css')); */

$head = new copperView('head.php', true);

$body = new copperView('bodys/test.php');
$body->welcome = 'Welcome, your copperFramework is succesful installed.';
$body->render();

$foot = new copperView('foot.php', true);