<?php
/**
 * A narrative, for parsing stuff hosted on Google Sheets
 */

/**
 * A Narrative object
 */ 
class Narrative
{
    /** Identifier */
    public $id;

    /** Published */
    public $published;

    /** Name, as an array (dictionary) of language versions */
    public $name;

    /** Introduction, as an array (dictionary) of language versions */
    public $intro;

    /** Cover picture. As an array (dictionary) of language versions, URL and source=credit */
    public $pic;

    /** Array of map data items, an ordered list of ids */
    public $items;

    /**
     * A contructor
     *
     * Yeah, should accept parameters for initializing the object,
     * currently relies on the build() method for populating.
     */
    function __construct() {}

    /**
     * Populate the object from an array
     *
     * Erm what would be a better way to use the constructor
     * actually...? Here I am using a separate build(), after
     * preprocessing a array to an associative array. The current
     * storage solution, Google Sheets over the API, server sheet rows
     * as integer-indexed JSON objects, without column names. The
     * first item contains would contain the column names.
     *
     * @param integer $id Identifier
     * @param boolean $published Is this published?
     * @param array $name Tuple of (danish, english) language name
     * @param array $intro Tuple of (danish, english) introduction text
     * @param array $pic Triple of URL, link and tuple (danish, english) caption
     * @param string $items A comma-separated string of items
     */
    function build($id, $published, $name, $intro, $pic, $items)
    {
        $this->id = +$id;
        $this->published = strtolower($published) == "yes";
        $this->name = Array("da" => $name[0],
                            "en" => $name[1]);
        $this->intro = Array("da" => $intro[0],
                             "en" => $intro[1]);
        $this->pic = Array("url" => $pic[0],
                           "credit" => $pic[1],
                           "caption" => Array("da" => $pic[2],
                                              "en" => $pic[3]));
        $this->items = array_map('intval', explode(',', $items));
    }

    /**
     * Populate the object properties from an JSON object
     *
     * Fragile, as this relies on the schema (read: Google Sheet
     * columns) not changing, like, ever. Also no data validity
     * checking
     *
     * @param array $j 
     */
    function fromJson($j) {
        // $this->__construct(j[0], Array(j[2], j[1]), Array(j[4], j[3]), Array(j[5], j[6], j[8], j[7]), explode(',', j[9]));
        //        dump($j);
        $this->build($j[0],
                     $j[1],
                     Array($j[3], $j[2]),
                     Array($j[5], $j[4]),
                     Array($j[6], $j[7], $j[9], $j[8]),
                     $j[10]);
    }
}
?>