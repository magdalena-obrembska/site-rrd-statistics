<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once('../common/log.php');
require_once('../plugins/enabledPlugins.php');

function __autoload($class_name) {
  require_once "../plugins/" . $class_name . '.php';
}

$width = isset($_GET["width"]) ? $_GET["width"] : 800;
$height = isset($_GET["height"]) ? $_GET["height"] : 140;
$format = isset($_GET["format"]) ? $_GET["format"] : "html";
$time = $_GET["time"];

if (!isset($time)) {
	die("Values not passed!");
}

if ($format != "html" && $format != "json") {
  die("format incorrect. json or html is accepted");
}

addToLog("Request Graph generation. IP: ".$_SERVER['REMOTE_ADDR']." width: ".$width." height: ".$height." time:".$time);
$generatedImagesArray = array();
$config = include('../config.php');
foreach ($pluginsArray as $plugin) {
  $pluginGraphs = $plugin->get_graph($width,$height,$time);
  foreach($pluginGraphs as $graph) {
    array_push($generatedImagesArray, $config['RRD_IMGS_URL'].basename($graph));
  }
}
switch($format) {
  case "json":
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($generatedImagesArray, JSON_PRETTY_PRINT);
    break;
  case "html":
    $configUrl = include('../config.php');
    foreach ($generatedImagesArray as $image) {
      echo "<img src=".$image.">";
    }
    break;
}

?>
