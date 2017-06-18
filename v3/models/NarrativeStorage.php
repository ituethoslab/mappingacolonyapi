<?php
class NarrativeStorage
{
    private $giraffeJsonString = '{"0": 0, "1": "Hej giraf", "2": "Hi giraffe", "3": "Long text in English", "4": "Long text in Danish", "5": "http://giraffe.jpg", "6": "http://imagesourceforgiraffes", "7": "A nice image in English", "8": "A nice image in Danish", "9": [4, 5, 10, 30, 50, 31, 32]}';
    private $snakeJsonString = '{"0": 1, "1": "Hej snake", "2": "Hi snake", "3": "Long text in English", "4": "Long text in Danish", "5": "http://snake.jpg", "6": "http://imagesourceforsnakes", "7": "A nice image in English", "8": "A nice image in Danish", "9": [55, 45, 46, 47, 58, 11, 12, 4, 6, 5, 7]}'; 

    private $ini;
    private $data;
    private $demoJson;

    
    function __construct($inifile)
    {
        $this->ini = parse_ini_file($inifile, true);
        $this->data = Array(1 => "kitten",
                            2 => "octopus",
                            3 => "snake",
                            4 => "lizard",
                            5 => "elk",
                            6 => json_decode($this->giraffeJsonString, true),
                            7 => json_decode($this->snakeJsonString, true));
    }

    function get($n)
    {
        /*
        if(key_exists($n, $this->data)) {
                $narrative = new Narrative;
                $narrative->fromJson($this->data[$n]);

                return json_encode($narrative);
        } else {
            throw new Exception("No such item $n");
        }
        */
        if(key_exists($n, $this->listids())) {
            $growno = $n + 2; // yeah hardcoded...
            $grows = $this->googlecall('A' . $growno . ':' . 'Z' . $growno);
            $narrative = new Narrative;
            $gjson = json_decode($grows, true);
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
        return $response;
    }
}
?>
