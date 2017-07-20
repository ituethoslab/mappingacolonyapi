<?php
/**
 * A controller for map data.
 */

/**
 * An MVCish controller for map data.
 */
class MapDataController
{
    /**
     * Data storage
     */
    private $storage;

    /**
     * Constructor
     *
     * @param string $inifile An inifile name with credentials etc.
     */
    function __construct($inifile)
    {
        $this->storage = new MapDataStorage($inifile);
    }

    /**
     * Action for getting one map data item
     *
     * If not "n" if provided, gets all the items
     * 
     * @param HttpRequest $req An HTTP request, with argument "n" in it
     *
     * @return The item as a JSON object
     */
    function getAction($req)
    {
        header("Content-type: application/json");
        // $n = array_shift($req);
        // if($n)
        if(key_exists("n", $req))
        {
            $nid = array_shift($req);
            try {
                $narrative = $this->storage->get($nid);
                echo ($narrative);
            } catch (Exception $e) {
                echo json_encode(Array("error" => $e->getMessage()));
            }

        }
        else
        {
            $list = $this->storage->getall();
            echo json_encode($list);
        }
    }

    /**
     * Action for listing all map data item ids
     *
     * @return List of all map data identifiers, as a list in JSON
     */
    function listidsAction()
    {
        header("Content-Type: application/json");
        echo json_encode($this->storage->listids());
    }

    /**
     * Action for dumping the whole storage contents for map data.
     *
     * By default as CSV, or as JSON if requested.
     *
     * @param HttpRequest $req A HTTP request with optional "format" for JSON or CSV
     * @return All of the map data, in the requested format
     */
    function dumpstorageAction($req)
    {
        if(key_exists("format", $req))
        {
            $format = $req["format"];
            if($format == "csv")
            {
                echo $this->storage->dumpstoragecsv();
            }
            else
            {
                print("No such format defined " . $format);
            }
        }
        else
        {
            echo $this->storage->dumpstorage();
        }
    }
}
?>
