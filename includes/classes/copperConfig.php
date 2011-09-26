<?php

/**
 *
 * __autoLoad
 *
 * magic auto load method
 * 
 * All files will be included should be prefixed with copper and be in CamelCase
 *
 * @author Emilio Astarita
 * @package copperFramework
 */
function __autoLoad($className) {
    $classFile = copperConfig::libPath($className . '.php');
    if (preg_match("/^copper/", $className) &&
            file_exists($classFile)) {
        require_once($classFile);
    } else {
        throw new Exception("Class not found: `$className`.");
    }
}

/**
 * copperConfig
 *
 * @package    copperFramework
 * @author     Emilio Astarita
 */
class copperConfig {

    private static $conf;

    public static function init($values) {
        self::$conf = $values;
        self::loadConfigs();
        self::initDb();
    }

    protected static function loadConfigs() {
        $dir = self::get('configsDir');

        try {
            $files = scandir($dir);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && $file !== 'system.php') {
                include_once $dir . $file;
                $configName = explode(".", $file);
                $configName = $configName[0];
                $configArray = ${$configName . 'Config'};
                self::set($configName . 'Config', $configArray);
            }
        }
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

    /**
     * doLog
     *
     * log an message into log file
     *
     * @param string $msg
     * @param bool $append
     * @return bool
     */
    public static function doLog($msg, $append = true) {
        $logFile = self::$conf['log'];
        if (!is_writable($logFile) && file_put_contents($logFile, "\n") === false) {
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

    /**
     *  doError
     *
     *  Log an error
     *
     * @param string $msg
     * @param bool $append
     * @return bool
     */
    public static function doError($msg, $append = true) {
        return self::doLog('* ERROR * - ' . $msg, $append);
    }

    /**
     * set
     *
     * Set an global var
     *
     * @param string $key
     * @param mix $value
     * @param bool $overwrite
     * @return mix
     */
    public static function set($key, $value, $overwrite = false) {
        if (isset(self::$conf[$key]) && !$overwrite)
            throw new Exception("Key: `$key` already exists. Use `overwrite` param in true if you want to modify the existing key.");
        return self::$conf[$key] = $value;
    }

    /**
     * get
     *
     *  Get an global var
     *
     * @param string $key
     * @param <type> $default
     * @return mix
     */
    public static function get($key, $default = NULL) {
        if (isset(self::$conf[$key]))
            return self::$conf[$key];
        return $default;
    }

    /**
     * libPath
     *
     * Get the libraries path
     *
     * @param <type> $fileName
     * @return <type>
     */
    public static function libPath($fileName = NULL) {
        $sep = DIRECTORY_SEPARATOR;
        if ($fileName) {
            return str_replace(array($sep . $sep . $sep, $sep), $sep, self::$conf['classes'] . $sep . $fileName);
        }
        return self::$conf['classes'];
    }

    /**
     * templatesPath
     *
     * Get the templates path
     * @param <type> $fileName
     * @return <type>
     */
    public static function templatesPath($fileName = NULL) {
        $sep = DIRECTORY_SEPARATOR;
        if ($fileName) {
            return str_replace(array($sep . $sep . $sep, $sep), $sep, self::$conf['templates'] . $sep . $fileName);
        }
        return self::$conf['includes'];
    }

    /**
     * publicPath
     *
     * Get the public path
     *
     * @param <type> $fileName
     * @return <type>
     */
    public static function publicPath($fileName = NULL) {
        if ($fileName) {
            return str_replace(array('///', '//'), "/", self::$conf['public'] . '/' . $fileName);
        }
        return self::$conf['public'];
    }

    /**
     * link
     *
     * Generate a clean link
     *
     * @param string $l
     * @return string
     */
    public static function link($l = NULL) {
        $tmp = explode('http://', self::$conf['canvasUrl']);
        if ($l) {
            return 'http://' . str_replace(array('///', '//'), "/", $tmp[1] . '/' . $l);
        }
        return self::$conf['canvasUrl'];
    }

    /**
     * inc
     *
     * Include a lib file
     * @param string $fileName
     */
    public static function inc($fileName) {
        require_once(self::libPath($fileName));
    }

    /**
     * incTemplate
     *
     * Include a template
     * @param string $fileName
     */
    public static function incTemplate($fileName) {
        require_once(self::templatesPath($fileName));
    }

    /**
     * incCss
     *
     * Include a CSS file
     * @param string $fileName
     * @param bool $external
     * @return true
     */
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

    /**
     * incJs
     *
     * Include a JS file
     * @param string $fileName
     * @param bool $external
     * @return true
     */
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

    /**
     * pub
     *
     * Include a public file
     *
     * @param string $fileName
     * @return string
     */
    public static function pub($fileName) {
        return self::publicPath($fileName);
    }

    /**
     * pubUpload
     *
     * Return the path of an public upload
     *
     * @param string $fileName
     * @return string
     */
    public static function pubUpload($fileName) {
        return self::publicPath(copperConfig::get('uploadsPublic') . '/' . $fileName);
    }

}
