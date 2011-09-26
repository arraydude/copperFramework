<?php
/**
 * copperView
 *
 * Class to handle views
 *
 * @package    copperFramework
 * @author     Nahuel Rosso
 * @version    1.0
 */
class copperView {

  private $view;

  /**
   * __construct
   *
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
   * render()
   *
   * Render the view
   */
  public function render() {
    copperConfig::incTemplate($this->view);
  }

  /**
   * __set
   *
   * Treat the View as an Object
   */
  public function __set($key, $value) {
    copperConfig::set($key, $value);

    return $this;
  }

}

