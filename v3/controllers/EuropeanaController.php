<?php
/**
 * Controller for Europeana, to be used as a middlware
 */

/** 
 * An MVCish class for Europeana, using their REST API
 */
class EuropeanaController
{
    /**
     * An Europeana connection model
     */
    private $europeana;

    /**
     * Constructor
     *
     * @param string $inifile An inifile name with credentials, sheet names etc.
     */
    function __construct($inifile)
    {
        $this->europeana = new EuropeanaConnection($inifile);
    }

    /**
     * Action for getting geographically nearby items from Europeana.
     *
     * An action for handling an HTTP API call for requesting nearby
     * items. Latitude and longitude are expected to be found in the
     * URL parameters.
     * 
     * @param HttpRequest $req HTTP request to be passed on.
     *
     * @return A HTTP response with JSON data
     */
    function nearbyAction($req) {
        // expects to find arguments 'lat' and 'long' in the request
        $lat = $req["lat"];
        $long = $req["long"];
        $response = $this->europeana->getNearby($lat, $long);
        header("Content-type: application/json");
        print $response;
    }

    /**
     * Action for getting thematically related items from Europeana.
     *
     * An action for handling an HTTP API call for requesting
     * Europeana items about a theme.
     * 
     * @param HttpRequest $req HTTP request to be passed on.
     *
     * @return A HTTP response with JSON data
     */

    function subjectAction($req) {
        $subject = str_replace(' ', '+', array_shift($req));
        $response = $this->europeana->getSubject($subject);
        header("Content-type: application/json");
        print $response;
    }
}
?>
