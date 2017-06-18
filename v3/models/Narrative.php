<?php
class Narrative
{
    public $id;
    public $name;
    public $intro;
    public $pic;
    public $items;

    function __construct() {}
    // function __construct($id, $name, $intro, $pic, $items)
    function build($id, $name, $intro, $pic, $items)
    {
        $this->id = $id;
        $this->name = Array("da" => $name[0],
                            "en" => $name[1]);
        $this->intro = Array("da" => $intro[0],
                             "en" => $intro[1]);
        $this->pic = Array("url" => $pic[0],
                           "credit" => $pic[1],
                           "caption" => Array("da" => $pic[2],
                                              "en" => $pic[3]));
        $this->items = explode(',', $items);
    }

    // Erm what would be a better way to use the constructor
    // actually...? Here I am using a separate build(), after
    // preprocessing a array to an associative array
    function fromJson($j) {
        // $this->__construct(j[0], Array(j[2], j[1]), Array(j[4], j[3]), Array(j[5], j[6], j[8], j[7]), explode(',', j[9]));
        //        dump($j);
        $this->build($j[0],
                     Array($j[2], $j[1]),
                     Array($j[4], $j[3]),
                     Array($j[5], $j[6], $j[8], $j[7]),
                     $j[9]);
    }
}
?>