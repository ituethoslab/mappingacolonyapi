<?php
class MapDataStorage
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
            $mapdata = new MapData;
            $mapdata->fromJson($gjson["values"][0]); // always take the first one
            return json_encode($mapdata);
        } else {
            throw new Exception("No such item $n");
        }
   }

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

    function dumpstoragecsv()
    {
        header("Content-Type: text/csv");
        return file_get_contents($this->ini["data"]["dataSheetCSV"]);
    }

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
