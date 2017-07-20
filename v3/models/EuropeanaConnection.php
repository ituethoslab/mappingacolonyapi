<?php
/**
 * A connection to EU cultural heritage portal Europeana
 */

/**
 * A connection to Europeana REST API
 */
class EuropeanaConnection
{
    /**
     * Configuration
     */
    private $ini;

    /**
     * Constructor
     *
     * @param string $inifile Inifile name for configuration
     */
    function __construct($inifile)
    {
        $ini = parse_ini_file($inifile, true);
        $this->europeana = $ini["europeana"]["endpoint"] . "?wskey=" . $ini["europeana"]["apikey"];
    }

    /**
     * Get geographically nearby Europeana objects
     *
     * Does no parsing, just passes back whatever Europeana returned
     *
     * @param float $lat Latitude
     * @param float $long Longitude
     * 
     * @return Europeana results as JSON object
     */
    function getNearby($lat, $long) {
        $size = 0.025;
        $bb = "pl_wgs84_pos_lat:[" . ($lat - $size) . "+TO+" . ($lat + $size) . "]+AND+pl_wgs84_pos_long:[" . ($long - $size) . "+TO+" . ($long + $size) . "]";
        $q = "&query=" . $bb;
        $response = $this->call($q);
        return $response;
    }

    /**
     * Get thematically related Europeana objects
     *
     * @param string $subject A subject to query in Europeana dcSubject
     *
     * @return A JSON response from Europeana
     */

    function getSubject($subject)
    {
        $q = "&query=proxy_dc_subject:" . $subject;
        $response = $this->call($q);
        return $response;
    }

    /**
     * Call Europeana REST API
     *
     * Simply passes whatever response Europeana gave
     *
     * @param string $args Arguments for Europeana "q"
     *
     * @return Whatever Europeana returned, this should be a JSON object
     */
    private function call($args)
    {
        $q = $args;
        $request = $this->europeana . $q;
        $response = file_get_contents($request);
        return $response;
    }
}