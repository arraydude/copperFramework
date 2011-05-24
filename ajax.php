<?php

require_once 'config.php';

/**
 * Ajax Service Controller
 *
 * @author Nahuel Rosso
 * @package copperFramework
 * @version 1.2
 */
class Ajax {
  private $model;

  /**
   * __construct
   *
   * The constructor camelize the method
   * @param string $method
   * @param array $params
   */
  public function __construct($method, $params) {
    $method = copperStr::camelize($method);
    $this->model = copperConfig::get("model");
    $this->$method($params);
  }

  /**
   * testAjaxService
   *
   * Ajax method for test the service
   *
   * @param array $params
   */
  public function testAjaxService(array $params) {
    echo json_encode($params);
  }

  /**
   * doAjaxError
   *
   * Response an ajax error
   *
   * @param string $msg
   * @return false
   */
  protected function doAjaxError($msg) {
    $message = array('error' => true, "msg" => $msg);
    echo json_encode($message);
    return false;
  }

  /**
   * checkNewTeamMemberProct
   *
   * An example of use
   *
   * Example: http://localhost/ajax.php?method=check_new_team_member_proct&params=<json>
   * 
   * @param <type> $uid
   * @return <type> 
   */
  protected function checkNewTeamMemberProct($uid) {
    $team = $this->model->getTeam();
    if (!$team) {
      return $this->doAjaxError('No se logro obtener equipo. No hay session');
    }
    if ($this->model->alreadyMember($uid, $team['id'])) {
      return $this->doAjaxError('El usuario ya es miembro del equipo.');
    }
    if ($this->model->isCaptain($uid)) {
      return $this->doAjaxError('El usuario es capitan de otro equipo.');
    }
    return true;
  }
  
}

try {
  $Ajax = new Ajax($_REQUEST['method'], $_REQUEST['params']);
} catch (Exception $exc) {
  $errLog = "Peticion ajax (";
  $errLog .= $_REQUEST['method'] . " con params " . json_encode($_REQUEST['params']);
  $errLog .= ") falla por excepcion: " . $exc->getMessage();
  copperConfig::doError($errLog . " File: " . __FILE__ . ":" . __LINE__);
  copperConfig::doError($exc->getTraceAsString());
  echo json_encode(array("error" => true, "msg" => $exc->getMessage()));
}
