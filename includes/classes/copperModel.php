<?php

/**
 * copperModel
 * 
 * The child models need to have defined DATABASE COLUMNS as properties following
 * the next definition:
 * 
 * fieldNameColumn, for example usersColumn
 * 
 * @author Nahuel Rosso
 * @package copperFramework
 */
class copperModel {

    protected $db;
    protected $fb;
    protected $tableName;

    public function __construct($fb) {
        $this->fb = $fb;
        $this->db = copperConfig::get('copperDb');
        $this->getTableName();
        $this->getData();
    }

    final public static function factory($model) {
        copperConfig::inc('../models/' . $model . '.php');
        $camelized = copperStr::camelize($model);
        $model = new $camelized;
        return $model;
    }
    
    final private function getTableName() {
        $className = get_class($this);
        $className = str_replace("Model", "", $className);
        $className = copperStr::revertCamelize($className);
        
        $this->tableName = $className;
    }

    final public function getColumns() {
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        
        return $props;
    }
    
    final protected function getData() {
        $query = "SELECT * FROM {$this->tableName}";

        try {
            $st = copperDb::statement($query);
            $rs = $st->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $exc) {
            copperConfig::doError($exc->getMessage());
        }

        $props = $this->getColumns();
        
        foreach($props as $prop){
            $this->{$prop->name} = $rs[$prop->name];
        }
        
        return $this;
    }
    
    protected function generateDbFields() {
        $properties = $this->getColumns();

        $generatedFields = "";
        $firstElement = true;
        
        foreach ($properties as $property) {
            if ($firstElement) {
                $generatedFields .= $property->name . " = ?";
                $firstElement = false;
            } else {
                $generatedFields .= ", " . $property->name . " = ?";
            }
        }
        
        return $generatedFields;
    }
    
}
