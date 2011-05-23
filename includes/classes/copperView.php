<?php

class copperView {

  private $view;

  /**
   * create the instance view
   * @param * $view
   * @param bool $render
   * @return copperView 
   */
  public function __construct($view, $render = false) {
    $this->view = $view;

    if($render){
      $this->render();
    }else{
      return $this;
    }
  }

  /**
   * Render the view
   */
  public function render() {
    copperConfig::incTemplate($this->view);
  }

  /**
   * Treat the View as an Object
   */
  public function __set($key, $value) {
    copperConfig::set($key, $value);

    return $this;
  }

}

