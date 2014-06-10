<?php

class VIAF {

    function __construct() {
    }

    function perform($values) {

        /* Determine the final API url based off the provided parameters, fetch the contents
           of the API url and then process it to generate our generic "person" format. */

        $url = $this->_createVIAFURL($values);
        print $url . "***";

        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept: application/json\r\n"
            )
        );

        $context = stream_context_create($opts);
        $content = file_get_contents($url,false,$context);
        $result = json_decode($content, true);
        $convertedResults = $this->_processResultSet($result);

        return $convertedResults;
    }


    function _createVIAFURL(&$values) {

        $urlBase = "http://viaf.org/viaf/AutoSuggest";

        $query = null;

        if(array_key_exists("fname", $values) && array_key_exists("gname", $values)) {
            $query .=  $values["gname"] . ' ' . $values["fname"];;
        }
        elseif(array_key_exists("gname", $values)) {
            $query .= $values["gname"];
        }

        elseif(array_key_exists("fname", $values)) {
            $query .=  $values["fname"];
        }

        $finalQuery = "";

        if(!is_null($query)) {
            $finalQuery .= urlencode($query);
        }

        return $urlBase . "?query=" . $finalQuery;
   }


   function _processResultSet(&$result) {


       $finalResults = array();

       $item = array();

       foreach($result as $terms)
       {
           foreach($terms as $term)
           {

                $hints = array();

                if(array_key_exists("term", $term)) {
                    $item["rendered_val"] = $term["term"];
                    $item["structured_val"] = $term["term"];
                }
                if(array_key_exists("nametype", $term)) {
                    $hints["type"] = $term["nametype"];
                }
                if(array_key_exists("dnb", $term)) {
                    $hints["dnb"] = $term["dnb"];
                }
                if(array_key_exists("bnf", $term)) {
                    $hints["bnf"] = $term["bnf"];
                }
                if(array_key_exists("nla", $term)) {
                    $hints["nla"] = $term["nla"];
                }
                if(array_key_exists("lc", $term)) {
                    $hints["lc"] = $term["lc"];
                }
                if(array_key_exists("viafid", $term)) {
                   $item["id_src"] = "VIAF";
                   $item["id"] = 'http://www.viaf.org/viaf/' .$term["viafid"];
                }

               if(sizeof($hints) > 0) {
                   $item["hints"] = $hints;
               }
               $finalResults[] = $item;

           }

       }
       return $finalResults;
   }

}


$test = new VIAF();

$r = $test->perform(array("fname" => "Taylor", "gname" => "Martin"));
//$r = $test->perform(array("email" => "*@auckland.ac.nz"));

print_r($r);