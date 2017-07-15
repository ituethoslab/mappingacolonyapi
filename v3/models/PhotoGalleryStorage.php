<?php
class PhotoGalleryStorage
{
    private $ini;

    function __construct($inifile)
    {
        $this->ini = parse_ini_file($inifile, true);
    }

    function dumpstoragecsv()
    {
        header("Content-Type: text/csv");
        return file_get_contents($this->ini["data"]["photoGalleryCSV"]);
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
                                 str_replace(' ', '+', $this->ini["data"]["photoGallerySheetName"]),
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
