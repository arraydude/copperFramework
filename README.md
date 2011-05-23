copperFramework README
======================

An easy to use Framework oriented to facebook APPS.


Contributing
------------

Want to contribute? Great! please create a branch or contact me at arraydude@gmail.com.


Example
-------

### Controller, View , how to MVC...

    <?php

    require_once 'config.php';

    //CUSTOM JS AND CSS to automatically include
    /* copperConfig::set('customsJs', array('tournment/main.js','personalize/main.js'));
      copperConfig::set('customsCss', array('tournment.css')); */


    $view = new copperView('bodys/test.php');
    $view->welcome = 'Welcome, your copperFramework is succesful installed.';
    $view->render();
    ?>
