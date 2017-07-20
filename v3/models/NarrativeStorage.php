<?php
/**
 * A data storage model for narratives
 */

/**
 * Narrative storage, which is a Google Sheet document
 *
 * Yeah abstraction would be nice, also using the actual Google API
 * library for PHP, but it isn't available for our production
 * environment.
 */
class NarrativeStorage
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
     * Get an individual narrative
     *
     * @param int $n Identifier for a narrative
     *
     * @return JSON The requested narrative as a JSON object
     */
    function get($n)
    {
        if(key_exists($n, $this->listids())) {
            $growno = $n + 2; // yeah hardcoded...
            $grows = $this->googlecall('A' . $growno . ':' . 'Z' . $growno);
            $gjson = json_decode($grows, true);
            $narrative = new Narrative;
            $narrative->fromJson($gjson["values"][0]); // always take the first one
            return json_encode($narrative);
        } else {
            throw new Exception("No such item $n");
        }
    }

    /**
     * Get all narratives
     *
     * @return array All the narratives as an array
     */
    function getall()
    {
        $narratives = array();
        $items = json_decode($this->dumpstorage(true), true);
        foreach($items["values"] as $i)
        {
            $narrative = new Narrative;
            $narrative->fromJson($i);
            array_push($narratives, $narrative);
        }
        return $narratives;
    }

    /** 
     * List all the narrative identifiers
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
                                 $this->ini["data"]["narrativeSheetName"],
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
