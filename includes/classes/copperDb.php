<?php

/**
 * copperDbPDO
 *
 * PDO renamed
 *
 * @package copperFramework
 * @author Emilio Astarita
 */
class copperDbPDO extends PDO {
  
}

/**
 * copperDb
 *
 * A layer to handle SQL connections with the great PDO php extension
 *
 * @package    copperFramework
 * @author     Emilio Astarita
 * @version    1.0
 */
class copperDb {

  static private $instance = NULL;

  private function __construct() {
    
  }

  /**
   * configure
   *
   * Configure the instance
   * @param <type> $dsn
   * @param <type> $user
   * @param <type> $pass
   * @return db
   */
  static public function configure($dsn, $user, $pass) {
    if (self::$instance !== NULL) {
      throw new Exception('copperDb already configured.');
    }
    try {
      self::$instance = new copperDbPDO($dsn, $user, $pass);
      self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      self::$instance->prepare("SET NAMES UTF8;")->execute();
    } catch (Exception $e) {
      copperConfig::doError(" Configure: " . $e->getMessage() . " File: " . __FILE__ . ":" . __LINE__);
      echo "Hubo un error con la base de datos.\n";
      die;
    }
    return self::$instance;
  }

  static public function get() {
    if (self::$instance == NULL) {
      throw new Exception('copperDb is not configured call configure static method first.');
    }
    return self::$instance;
  }

  /**
   * Genera una cadena sql para las keys de un array
   *
   *  Ejemplo: field1=?, field2=?
   * @param array $array
   * @return string
   */
  static public function generateFieldsWithKeys(array $array) {
    $sqlFields = implode("=?,", array_keys($array));

    return $sqlFields . "=?";
  }

  static public function generateValues(array &$array, $values) {
    foreach ($values as $value) {
      array_push($array, $value);
    }
  }

  public function statement($query, array $params = array()) {
    $st = self::$instance->prepare($query);
    $st->execute($params);
    
    return $st;
  }

}
