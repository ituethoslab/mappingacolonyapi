<?php
class NarrativeController
{
    private $storage;

    function __construct($inifile)
    {
        $this->storage = new NarrativeStorage($inifile);
    }

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

    function listidsAction()
    {
        header("Content-Type: application/json");
        echo json_encode($this->storage->listids());
    }

    function dumpstorageAction()
    {
        header("Content-Type: application/json");
        echo $this->storage->dumpstorage();
    }
}
?>
