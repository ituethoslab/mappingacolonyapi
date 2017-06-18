<?php
class NarrativeStorage
{
    private $ini;

    function __construct($inifile)
    {
        $this->ini = parse_ini_file($inifile, true);
    }

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

    function dumpstorage($skipheader=true)
    {
        if($skipheader)
        {
            return $this->googlecall('A2:Z1000');
        } else {
            return $this->googlecall(NULL);
        }
    }

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
