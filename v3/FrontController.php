<?php
/**
 * FrontController
 *
 * The API endpoint, first point of contact. Having index.php symlink
 * to this is a good idea. Also .htaccess for prettier URLs.
 */
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

/**
 * Front controller class.
 *
 * This is where things start. This Front Controller receives an HTTP
 * request to an URL, extracts the requested Controller and an action
 * within it, passes the request parameters to it.
 */
class FrontController
{
    /** Data source description, an filename for an .ini file */
    private $datasourcedescription;

    /**
     * Constructor
     */
    function __construct() {
        $this->datasourcedescription = '../datasource.ini';
    }

    /**
     * Process a HTTP Request
     *
     * @param HttpRequest A HTTP request to process
     */
    function process($req)
    {
        $controllername = ucfirst(array_shift($req)) . "Controller";
        header("Access-Control-Allow-Origin: *");
        
        $controller = new $controllername($this->datasourcedescription);
        $action = array_shift($req) . "Action";
        
        $controller->$action($req);
    }
}

$fc = new FrontController;
$fc->process(filter_input_array(INPUT_GET, $_REQUEST));