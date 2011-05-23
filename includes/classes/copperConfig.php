<?php

function __autoLoad($className) {
  $classFile = copperConfig::libPath($className . '.php');
  if (preg_match("/^copper/", $className) &&
          file_exists($classFile)) {
    require_once($classFile);
  } else {
    throw new Exception("Class not found: `$className`.");
  }
}

class copperConfig {

  private static $conf;

  public static function init($values) {
    self::$conf = $values;
    self::initDb();
  }

  protected static function initDb() {
    global $copperDb;
    $user = copperConfig::get('dbUser');
    $pass = copperConfig::get('dbPass');
    $host = copperConfig::get('dbHost');
    $name = copperConfig::get('dbName');
    $dsn = "mysql:host=$host;dbname=$name";
    $copperDb = copperDb::configure($dsn, $user, $pass);
    self::set('copperDb', $copperDb);
  }

  public static function doLog($msg, $append = true) {
    $logFile = self::$conf['log'];
    if (!is_writable($logFile) && file_put_contents($logFile,"\n") === false) {
      echo 'WARNING: Imposible escribir en ' . $logFile . " configurar permisos.\n";
      return false;
    }
    $msg = date("d-m-Y H:i:s | ") . $msg . "\n";
    if ($append)
      file_put_contents($logFile, $msg, FILE_APPEND);
    else
      file_put_contents($logFile, $msg);
    return true;
  }
  public static function doError($msg, $append = true) {
    return self::doLog('* ERROR * - ' . $msg,$append);
  }

  public static function set($key, $value, $overwrite = false) {
    if (isset(self::$conf[$key]) && !$overwrite)
      throw new Exception("Key: `$key` already exists. Use `overwrite` param in true if you want to modify the existing key.");
    return self::$conf[$key] = $value;
  }

  public static function get($key, $default = NULL) {
    if (isset(self::$conf[$key]))
      return self::$conf[$key];
    return $default;
  }

  public static function libPath($fileName = NULL) {
    $sep = DIRECTORY_SEPARATOR;
    if ($fileName) {
      return str_replace(array($sep . $sep . $sep, $sep), $sep, self::$conf['classes'] . $sep . $fileName);
    }
    return self::$conf['classes'];
  }

  public static function templatesPath($fileName = NULL) {
    $sep = DIRECTORY_SEPARATOR;
    if ($fileName) {
      return str_replace(array($sep . $sep . $sep, $sep), $sep, self::$conf['templates'] . $sep . $fileName);
    }
    return self::$conf['includes'];
  }

  public static function publicPath($fileName = NULL) {
    if ($fileName) {
      return str_replace(array('///', '//'), "/", self::$conf['public'] . '/' . $fileName);
    }
    return self::$conf['public'];
  }

  public static function link($l = NULL) {
    $tmp = explode('http://', self::$conf['canvasUrl']);
    if ($l) {
      return 'http://' . str_replace(array('///', '//'), "/", $tmp[1] . '/' . $l);
    }
    return self::$conf['canvasUrl'];
  }

  public static function inc($fileName) {
    require_once(self::libPath($fileName));
  }

  public static function incTemplate($fileName) {
    require_once(self::templatesPath($fileName));
  }

  public static function incCss($fileName, $external = true) {
    if (!$external) {
      echo '<style type="text/css">';
      $filePath = self::$conf['path'] . DIRECTORY_SEPARATOR . self::$conf['stylesDir'] . DIRECTORY_SEPARATOR . $fileName;
      $fileData = file_get_contents($filePath);
      $vars = array("##ROOT##", "##IMGS_VERSION##");
      $rep = array(copperConfig::get('canvasUrl'), self::$conf['imgsVersion']);
      $replaced = str_replace($vars, $rep, $fileData);
      echo $replaced;
      echo '</style>';
    } else {
      echo '<link rel="stylesheet" type="text/css" media="screen" href="';
      /**
       * @todo Check this out
       */
      //echo self::publicPath(self::$conf['stylesDir'] . '/' . $fileName) . '?vs=' . self::$conf['stylesVersion'] . '" />' . "\n";
      echo copperConfig::get('callbackUrl') . self::$conf['stylesDir'] . '/' . $fileName . '?vs=' . self::$conf['stylesVersion'] . '" />' . "\n";
    }
    return true;
  }

  public static function incJs($fileName, $external = true) {
    if (!$external) {
      echo '<script type="text/javascript">' . "\n";
      echo '<!--' . "\n";
      require_once(self::$conf['path'] . DIRECTORY_SEPARATOR . self::$conf['jsDir'] . DIRECTORY_SEPARATOR . $fileName);
      echo '//-->' . "\n";
      echo '</script>' . "\n";
    } else {
      echo '<script type="text/javascript"  src="';
      /**
       * @todo Check this out
       */
      //echo self::publicPath(self::$conf['jsDir'] . '/' . $fileName) . '?vs=' . self::$conf['jsVersion'] . '"></script>' . "\n";
      echo copperConfig::get('callbackUrl') . self::$conf['jsDir'] . '/' . $fileName . '?vs=' . self::$conf['jsVersion'] . '"></script>' . "\n";
    }
    return true;
  }

  public static function pub($fileName) {
    return self::publicPath($fileName);
  }

  public static function pubUpload($fileName) {
    return self::publicPath(copperConfig::get('uploadsPublic') . '/' . $fileName);
  }

}
