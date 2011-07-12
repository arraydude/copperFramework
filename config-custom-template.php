<?php

/**
 * Configuration Template
 *
 * @author Emilio Astarita && Nahuel Rosso
 * @version 2.0
 * @package copperFramework
 */


$copperConfigVisible = array(
    'facebookActivate' => false,
    'facebookJsActivate' => false,
    'facebookPerms' => 'photo_upload,user_photos,email,friends_photos,user_photo_video_tags,friends_photo_video_tags',
    'appId' => '151220508282096',
    'fanPageId' => '',
    'canvasUrl' => 'http://apps.facebook.com/copperframework/',
    'callbackUrl' => 'http://localhost/copperFramework/',
    'tabUrl' => ''
);

$copperConfigPrivate = array(
    'appSecret' => 'e5706001a9bae30fa28d5bde42c90e11',
    'dbUser' => 'root',
    'dbPass' => 'notoques',
    'dbName' => 'mysql',
    'dbHost' => 'localhost'
);

$copperConfigCustom = array_merge($copperConfigVisible, $copperConfigPrivate);
