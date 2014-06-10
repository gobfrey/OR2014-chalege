<?php

class ORCID {

    function __construct() {
    }

    function perform($values) {

        /* Determine the final API url based off the provided parameters, fetch the contents
           of the API url and then process it to generate our generic "person" format. */

        $url = $this->_createORCIDURL($values);

        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept: application/orcid+json\r\n"
            )
        );

        $context = stream_context_create($opts);
        $content = file_get_contents($url,false,$context);

        $result = json_decode($content, true);
        $convertedResults = $this->_processORCIDResultSet($result);

        return $convertedResults;
    }


    function _createORCIDURL(&$values) {

        $urlBase = "http://pub.orcid.org/v1.1/search/orcid-bio";

        $givenNameQuery = null;
        $familyNameQuery = null;
        $orcIDQuery = null;
        $emailQuery = null;

        if(array_key_exists("gname", $values)) {
            $givenNameQuery = $values["gname"];
        }

        if(array_key_exists("fname", $values)) {
            $familyNameQuery = $values["fname"];
        }

        if(array_key_exists("orcid", $values)) {
            $orcIDQuery = $values["orcid"];
        }

        if(array_key_exists("email", $values)) {
            $emailQuery = $values["email"];
        }

        $finalQuery = "";

        if(!is_null($givenNameQuery)) {
            $finalQuery .= "given-names:" . urlencode($givenNameQuery);
        }

        if(!is_null($familyNameQuery)) {
            if(strlen($finalQuery) > 0) {
                $finalQuery .= "+AND+";
            }
            $finalQuery .= "family-name:" . urlencode($familyNameQuery);
        }

        if(!is_null($orcIDQuery)) {
            if(strlen($finalQuery) > 0) {
                $finalQuery .= "+AND+";
            }
            $finalQuery .= "orcid:" . urlencode($orcIDQuery);
        }

        if(!is_null($emailQuery)) {
            if(strlen($finalQuery) > 0) {
                $finalQuery .= "+AND+";
            }
            $finalQuery .= "email:" . urlencode($emailQuery);
        }

        return $urlBase . "?q=" . $finalQuery;

        //return "http://pub.orcid.org/v1.1/search/orcid-bio?q=family-name:Taylor+AND+given-names:M*";
        //return "http://pub.orcid.org/v1.1/search/orcid-bio?q=Mi%20Taylor";
    }


   function _processORCIDResultSet(&$result) {

        $finalResults = array();

        if(array_key_exists("orcid-search-results", $result) &&
                    array_key_exists("orcid-search-result", $result["orcid-search-results"])) {

            $orcidResults = $result["orcid-search-results"]["orcid-search-result"];

            array_walk($orcidResults, function($v) use (&$finalResults) {

                $item = array();

                $bio = $v["orcid-profile"]["orcid-bio"];
                if($bio && array_key_exists("personal-details", $bio)) {

                    $structuredValue = array();

                    if(array_key_exists("given-names", $bio["personal-details"])
                                && array_key_exists("family-name", $bio["personal-details"])) {

                        $structuredValue["given_name"] = $bio["personal-details"]["given-names"]["value"];
                        $structuredValue["family_name"] = $bio["personal-details"]["family-name"]["value"];

                        if(array_key_exists("credit-name", $bio["personal-details"])) {
                            $structuredValue["credit_name"] = $bio["personal-details"]["credit-name"]["value"];
                        }


                        $item["rendered_val"] = $structuredValue["family_name"] . ", " . $structuredValue["given_name"];
                        $item["structured_val"] = $structuredValue;

                        $item["id_src"] = "ORCID";
                        $item["id"] = $v["orcid-profile"]["orcid-identifier"]["uri"];

                        $hints = array();
                        $hasHints = false;

                        if(array_key_exists("researcher-urls", $bio) &&
                                    array_key_exists("researcher-url", $bio["researcher-urls"])) {

                            $urlHints = array();

                            array_walk($bio["researcher-urls"]["researcher-url"], function($u) use (&$urlHints) {
                                $urlHints[] = $u["url"]["value"];
                            });

                            if(count($urlHints) > 0) {
                                $hints["url"] = $urlHints;
                                $hasHints = true;
                            }
                        }

                        if(array_key_exists("contact-details", $bio) &&
                            array_key_exists("email", $bio["contact-details"])) {

                            $emailHints = array();

                            array_walk($bio["contact-details"]["email"], function($u) use (&$emailHints) {
                                $emailHints[] = $u["value"];
                            });

                            if(count($emailHints) > 0) {
                                $hints["email"] = $emailHints;
                                $hasHints = true;
                            }
                        }

                        if($hasHints) {
                            $item["hints"] = $hints;
                        }
                        $finalResults[] = $item;
                    }

                }

            });

        }

        return $finalResults;
   }

}


$test = new ORCID();

//$r = $test->perform(array("fname" => "Taylor", "gname" => "Martin"));
$r = $test->perform(array("email" => "*@auckland.ac.nz"));

print_r($r);