<?php

/**
 * copperModel
 *
 * @author Nahuel Rosso
 * @package copperFramework
 */
class copperModel {

    /**
     *
     * @var type copperDb
     */
    public $db;

    /**
     *
     * @var type Facebook
     */
    public $fb;

    public function __construct($fb) {
        $this->fb = $fb;
        $this->fb instanceof Facebook;
        $this->db = copperConfig::get('copperDb');
        $this->db instanceof copperDb;
    }

    public static function factory($model) {
        copperConfig::inc('../models/' . $model . '.php');
        $camelized = copperStr::camelize($model);
        $model = new $camelized;
        return $model;
    }

}
