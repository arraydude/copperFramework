<?php
/**
 * Dont delete this file
 */
$copperConfigVisible = array(
    'facebookActivate' => TRUE,
    'facebookJsActivate' => TRUE,
    'facebookPerms' => 'email, publish_stream, publish_checkins, offline_access, manage_pages, user_checkins',
    'appId' => '203602673035922',
    'fanPageId' => '266631146680871',
    'fanPageAccessToken' => 'AAAC5LPEAapIBAOkSwpZCZB2abGrdQAZC5oOXlL3mDxuA62xROdO1hhnZAGSaNzWc7aA7RRqERssbVi3dbmo4CnHs0PJM1yWcL4M2a07ggZCuMv3fNutga',
    'canvasUrl' => 'http://apps.facebook.com/nike_pulseras/',
    'callbackUrl' => 'http://socialabel.com/nike_pulseras/trunk/',
    'tabUrl' => 'http://www.facebook.com/pages/Dodici-Test/266631146680871'
);

$copperConfigPrivate = array(
    'appSecret' => '94a43a7ca883f3509fa7e3213603b140',
    'dbUser' => 'socialab_nike',
    'dbPass' => 'n1k3',
    'dbName' => 'socialab_nike',
    'dbHost' => 'localhost'
);

$copperConfigCustom = array_merge($copperConfigVisible, $copperConfigPrivate);
