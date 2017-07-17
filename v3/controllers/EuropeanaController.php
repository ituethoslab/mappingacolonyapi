<?php
class EuropeanaController
{
    private $europeana;

    function __construct($inifile)
    {
        $this->europeana = new EuropeanaConnection($inifile);
    }

    function nearbyAction($req) {
        // expects to find arguments 'lat' and 'long' in the request
        $lat = $req["lat"];
        $long = $req["long"];
        $response = $this->europeana->getNearby($lat, $long);
        header("Content-type: application/json");
        print $response;
    }

    function subjectAction($req) {
        $subject = str_replace(' ', '+', array_shift($req));
        $response = $this->europeana->getSubject($subject);
        header("Content-type: application/json");
        print $response;
    }
}
?>
