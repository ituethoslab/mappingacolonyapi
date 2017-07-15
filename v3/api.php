<?php
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

require_once('controllers/MapDataController.php');
require_once('models/MapDataStorage.php');

require_once('controllers/PhotoGalleryController.php');
require_once('models/PhotoGalleryStorage.php');

require_once('controllers/NarrativeController.php');
require_once('models/Narrative.php');
require_once('models/NarrativeStorage.php');

require_once('controllers/EuropeanaController.php');
require_once('models/EuropeanaConnection.php');

$datasourcedescription = '../datasource.ini';

$req = filter_input_array(INPUT_GET, $_REQUEST);

$controllername = ucfirst(array_shift($req)) . "Controller";
{
    header("Access-Control-Allow-Origin: *");

    $controller = new $controllername($datasourcedescription);
    $action = array_shift($req) . "Action";

    $controller->$action($req);
}
?>
