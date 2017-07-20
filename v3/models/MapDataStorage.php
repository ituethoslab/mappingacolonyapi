<?php
/**
 * A data storage model for map data
 */

/**
 * Class for map data storage, which is a Google Sheet document
 *
 * In some ideal world, if this was a real engineering effort, this
 * would be abstract class. We are not in that world. Also, this is
 * just pulling stuff via the sharing mechanism, not Google Sheet API
 * because insufficient version of PHP on our production environemnt
 * to run the Google API PHP library, sadly.
 */
class MapDataStorage
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
     * Get an individual map item.
     *
     * @param int $n Identifier for a map data item
     *
     * @return JSON The requested map data item as JSON
     */
    function get($n)
    {
        if(key_exists($n, $this->listids())) {
            $growno = $n + 2; // yeah hardcoded...
            $grows = $this->googlecall('A' . $growno . ':' . 'Z' . $growno);
            $gjson = json_decode($grows, true);
            // $mapdata = new MapData;
            // $mapdata->fromJson($gjson["values"][0]); // always take the first one
            $mapdata = $gjson["values"][0];
            return json_encode($mapdata);
        } else {
            throw new Exception("No such item $n");
        }
    }

    /**
     * Get all map items
     *
     * @return array All the map data items as an array
     */
    function getall()
    {
        $mapdatas = array();
        $items = json_decode($this->dumpstorage(), true);
        var_dump($items);
        foreach($items["values"] as $i)
        {
            $mapdata = new MapData;
            $mapdata->fromJson($i);
            array_push($mapdatas, $mapdata);
        }
        return $mapdatas;
    }

    /** 
     * List all the items identifiers
     *
     * @return array An array of all the identifiers
     */
    function listids()
    {
        // return $this->googlecall('A2:A1000');
        $l = array();
        $items = json_decode($this->googlecall('A2:A1000'), true);
        foreach($items["values"] as $i)
        {
            array_push($l, +$i[0]);
        }
        return $l;
    }

    /**
     * Dump the whole storage as CSV.
     *
     * For clientside parsing, or whatever
     *
     * @return HttpResponse I don't even know what type this returns lol
     */
    function dumpstoragecsv()
    {
        header("Content-Type: text/csv");
        return file_get_contents($this->ini["data"]["dataSheetCSV"]);
    }

    /**
     * Dump the whole storage as JSON
     *
     * For clientside parsing, or whatever
     *
     * @param boolean true|false Whether to skip the header row
     *
     * @return JSON The whole contents of the storage, as JSON
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
                                 str_replace(' ', '+', $this->ini["data"]["dataSheetName"]),
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
