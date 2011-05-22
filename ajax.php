<?php

require_once 'config.php';

class Ajax {
  /**
   *
   * @var copperModel
   */
  private $model;

  public function __construct($method, $params) {
    $method = copperStr::camelize($method);
    $this->model = copperConfig::get("model");
    $this->$method($params);
  }

  public function testAjaxService(array $params) {
    echo json_encode($params);
  }

  protected function doAjaxError($msg) {
    $message = array('error' => true, "msg" => $msg);
    echo json_encode($message);
    return false;
  }

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
