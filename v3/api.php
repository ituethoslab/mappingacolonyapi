<?php
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

require_once('controllers/NarrativeController.php');
require_once('models/Narrative.php');
require_once('models/NarrativeStorage.php');

$datasourcedescription = '../datasource.ini';

$req = $_REQUEST;

// $controllers = array("NarrativeController"); // Not necessarily necessary check

$controllername = ucfirst(array_shift($req)) . "Controller";
//if(key_exists($controllername, $controllers))
{
    header("Access-Control-Allow-Origin: *");
    
    $controller = new $controllername($datasourcedescription);
    $action = array_shift($req) . "Action";

    $controller->$action($req);
}
?>
