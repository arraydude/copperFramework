<?php

/**
 * copperModel
 * 
 * @author Nahuel Rosso
 * @package copperFramework
 * @see example_user_model.php
 */
class copperModel {

    protected $db;
    protected $fb;
    protected $tableName;
    private $whereSQL;
    private $limitSQL;

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

        foreach ($props as $prop) {
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

    protected function where($sql) {
        if (is_null($this->whereSQL)) {
            $this->whereSQL = "WHERE $sql";
        } else {
            $this->whereSQL .= ", $sql";
        }
        
        return $this;
    }

    protected function limit($sql) {
        if (is_null($this->whereSQL)) {
            $this->limitSQL = "limit $sql";
        } else {
            $this->limitSQL .= ", $sql";
        }
        
        return $this;
    }

    protected function update() {
        $fields = $this->generateDbFields();
        $query = "UPDATE {$this->tableName} SET {$fields} {$this->whereSQL} {$this->limitSQL};";

        $props = $this->getColumns();

        $toBeUpdated = array();
        foreach ($props as $prop) {
            array_push($toBeUpdated, $this->{$prop->name});
        }

        try {
            $st = copperDb::statement($query, $toBeUpdated);
        } catch (Exception $exc) {
            copperConfig::doError($exc->getMessage());
        }

        return $this;
    }

    protected function insert() {
        $query = "INSERT INTO {$this->tableName} SET {$this->generateDbFields()}";
        
        $toBeInsert = array();
        foreach ($props as $prop) {
            array_push($toBeInsert, $this->{$prop->name});
        }

        try {
            $st = copperDb::statement($query, $toBeInsert);
        } catch (Exception $exc) {
            copperConfig::doError($exc->getMessage());
        }
    }

}
