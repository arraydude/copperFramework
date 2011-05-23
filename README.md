copperFramework README
======================

An easy to use Framework oriented to facebook APPS with the great MVC pattern and PDO Extension.


Contributing
------------

Want to contribute? Great! please create a branch or contact me at arraydude@gmail.com.


Configuring
-----------

* Rename and configure the "config-custom-template.php" file to "config-custom.php"
* Configure the config.php file
* Test the framework is working going to "callbackUrl"


Example
-------

### Controller, View , how to MVC...
As the standard , the main controller is index.php

    <?php

    require_once 'config.php';

    $view = new copperView('bodys/test.php');
    $view->welcome = 'Welcome, your copperFramework is succesful installed.';
    $view->render();
    ?>
