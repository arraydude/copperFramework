<?php
/**
 * exampleUserModel
 * 
 * this is an example model for your comprension
 * 
 * @author Nahuel Rosso
 * @package copperFramework
 */
final class exampleUserModel extends copperModel {

    public $id;
    public $name;
    public $birthday;
    public $password;
    
    /**
     * This constructor is completly necessary
     */
    final public function __construct() {
        parent::__construct(copperConfig::get('fbInstance'));
    }

    
    final public function updateUser(){
        $this->where("id = {$this->id}")->limit("1")->update();
    }
}