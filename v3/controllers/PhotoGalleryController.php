<?php
/**
 * A controller for photo gallery.
 */

/**
 * An MVCish controller for photo gallery.
 */
class PhotoGalleryController
{
    /**
     * Data storage
     */
    private $storage;

    /**
     * Constructor
     *
     * @param string $inifile An inifile name with credentials, sheet names etc.
     */
    function __construct($inifile)
    {
        $this->storage = new PhotoGalleryStorage($inifile);
    }

    /**
     * Action for dumping the whole storage content for image gallery.
     *
     * By default as a CSV, or JSON can be requested too with the
     * "format" URL argument
     *
     * @param HttpRequest $req An HTTP request, optionally with "format"
     *
     * @return All of the image gallery data, in JSON or the requested format
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
