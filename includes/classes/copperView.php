<?php

class copperView {

  private $view;

  public function __construct($view = NULL) {
    $this->view = $view;
  }

  public function render() {
    copperConfig::incTemplate($this->view);
  }

  public function __set($key, $value) {
    copperConfig::set($key, $value);
  }

}

