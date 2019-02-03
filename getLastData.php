<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../plugins/enabledPlugins.php');
header('Content-type:application/json;charset=utf-8');

function __autoload($class_name) {
  require_once "../plugins/" . $class_name . '.php';
}

$resultsArray = array();
foreach ($pluginsArray as $plugin) {
  $resultsArray = array_merge($resultsArray, $plugin->getLastData());
}
echo json_encode($resultsArray, JSON_PRETTY_PRINT);

?>
