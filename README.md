copperFramework README
======================

An easy to use Framework oriented to facebook APPS with the great MVC pattern and PDO Extension.


Contributing
------------

Want to contribute? Great! please create a branch or contact me at arraydude@gmail.com.


Configuring
-----------

* Rename and configure the "config-custom-template.php" file to "config-custom.php".
* Configure the config.php file.
* Use it.


Example
-------

    <?php

      require_once 'config.php';

      new copperView('head.php', true);

      $body = new copperView('bodys/test.php');
      $body->welcome = 'Welcome, your copperFramework is succesful installed.';
      $body->render();

      new copperView('foot.php', true);
    ?>

See this on the main controller ( index.php ).