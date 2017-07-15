<?php
class EuropeanaConnection
{
    private $ini;

    function __construct($inifile)
    {
        $ini = parse_ini_file($inifile, true);
        $this->europeana = $ini["europeana"]["endpoint"] . "?wskey=" . $ini["europeana"]["apikey"];
    }

    function getNearby($lat, $long) {
        $size = 0.025;
        $bb = "pl_wgs84_pos_lat:[" . ($lat - $size) . "+TO+" . ($lat + $size) . "]+AND+pl_wgs84_pos_long:[" . ($long - $size) . "+TO+" . ($long + $size) . "]";
        $q = "&query=" . $bb;
        $response = $this->call($q);
        return $response;
    }

    function getSubject($subject)
    {
        $q = "&query=proxy_dc_subject:" . $subject;
        $response = $this->call($q);
        return $response;
    }

    private function call($args)
    {
        $q = $args;
        $request = $this->europeana . $q;
        $response = file_get_contents($request);
        return $response;
    }
}