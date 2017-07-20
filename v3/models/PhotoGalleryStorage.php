<?php
/**
 * A data storage model for photo gallery
 */

/**
 * Photo gallery storage, which is a Google Sheet document
 *
 * Abstraction would be nice, and also using the Google API client
 * library would be nice but not available in our production
 * environment.
 */
class PhotoGalleryStorage
{
    /**
     * Configuration
     */
    private $ini;

    /**
     * Constructor
     *
     * @param string $inifile Inifile name for configuration, secrets etc
     */
    function __construct($inifile)
    {
        $this->ini = parse_ini_file($inifile, true);
    }

    /**
     * Dump the whole storage as CSV
     *
     * For clientside parsing, or whatever
     *
     * @return string LOL I don't even know what format this returns. A string?
     */
    function dumpstoragecsv()
    {
        header("Content-Type: text/csv");
        return file_get_contents($this->ini["data"]["photoGalleryCSV"]);
    }

    /**
     * Dump the whole storage as JSON
     *
     * For clientside parsing, or whatever
     *
     * @param boolean true|false Whether to skip the header row
     *
     * @return JSON The whole contents of the narrative storage, as JSON
     */
    function dumpstorage($skipheader=true)
    {
        header("Content-Type: application/json");
        if($skipheader)
        {
            return $this->googlecall('A2:Z1000');
        } else {
            return $this->googlecall(NULL);
        }
    }

    /**
     * Call the storage solution, which is a Google Sheet
     *
     * @param string $range What data to retrieve, in A1 notation
     *
     * @return string Whatever data Google Sheet returned. Typing is crappy here
     */
    private function googlecall($range) {
        if($range) {
            $range = '!' . $range;
        }
        $request = implode(array($this->ini["api"]["endpoint"],
                                 $this->ini["api"]["context"],
                                 "/",
                                 $this->ini["data"]["spreadsheetId"],
                                 "/values/",
                                 str_replace(' ', '+', $this->ini["data"]["photoGallerySheetName"]),
                                 $range,
                                 "?key=",
                                 $this->ini["auth"]["apikey"]));
        $response = file_get_contents($request);
        // Should return as proper PHP data structure and not leave
        // the parsing to consumers. But this allows raw dumping
        return $response;
    }
}
?>
