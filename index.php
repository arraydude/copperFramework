<?php
/**
 * This is the main controller of the app
 */

require_once 'config.php';

$head = new copperView('head.php', true);

$body = new copperView('bodys/test.php');
$body->welcome = 'Welcome, your copperFramework is succesful installed.';
$body->render();

$foot = new copperView('foot.php', true);