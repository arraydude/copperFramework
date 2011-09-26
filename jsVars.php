<?php
/**
 * Please dont touch this. This is the JavaScript Variable Generator.
 * 
 * @author Nahuel Rosso
 * @version 1.0
 */

require_once 'config.php';

header("Content-type: text/javascript");

$jsVars = copperConfig::get('visibleVars');

foreach ($jsVars as $key => $val) {
  echo "var " . strtoupper(copperStr::revertCamelize($key)) . " = " . json_encode($val) . ";\n";
}