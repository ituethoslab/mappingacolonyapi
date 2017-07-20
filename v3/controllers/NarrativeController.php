<?php
/**
 * A controller for narratives
 */

/**
 * An MVCish controller for narratives.
 */
class NarrativeController
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
        $this->storage = new NarrativeStorage($inifile);
    }

    /**
     * Action for getting one narrative item
     *
     * If no "n" if provided, gets all the narratives
     *
     * @param HttpRequest $req An HTTP request, with argument "n" in it
     *
     * @return The narrative as a JSON object  
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
     * Action for listing all narrative ids
     *
     * @return List of all narrative identifiers, as a list in JSON
     */
    function listidsAction()
    {
        header("Content-Type: application/json");
        echo json_encode($this->storage->listids());
    }

    /**
     * Action for dumping the whole storage contents for narratives.
     *
     * @returns The whole data storage as JSON
     */
    function dumpstorageAction()
    {
        header("Content-Type: application/json");
        echo $this->storage->dumpstorage();
    }
}
?>
