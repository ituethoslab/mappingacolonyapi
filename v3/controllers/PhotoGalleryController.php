<?php
class PhotoGalleryController
{
    private $storage;

    function __construct($inifile)
    {
        $this->storage = new PhotoGalleryStorage($inifile);
    }

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
