<?php
class EuropeanaController
{
    private $europeana;

    function __construct($inifile)
    {
        $ini = parse_ini_file($inifile, true);
        $this->europeana = $ini["europeana"]["endpoint"] . "?wskey=" . $ini["europeana"]["apikey"];
    }

    function nearbyAction($req) {
        // expects to find arguments 'lat' and 'long' in the request
        $size = 0.025;
        $lat = $req["lat"];
        $long = $req["long"];
        $bb = "pl_wgs84_pos_lat:[" . ($lat - $size) . "+TO+" . ($lat + $size) . "]+AND+pl_wgs84_pos_long:[" . ($long - $size) . "+TO+" . ($long + $size) . "]";
        $europeanaRequest = $this->europeana . "&query=" . $bb;
        $response = file_get_contents($europeanaRequest);
        header("Content-type: application/json");
        print $response;
    }
}
?>
