<?php
require_once 'config.php';

header("Content-type: text/javascript");

$jsVars = copperConfig::get('visibleVars');

foreach ($jsVars as $key => $val) {
  echo "var " . strtoupper(copperStr::revertCamelize($key)) . " = " . json_encode($val) . ";\n";
}