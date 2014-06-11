<?

$data = array(
'entity_type' => 'person',
'items' => array(
array(
'rendered_val' => 'Smith, Smith',
'structured_val' => array(
'given_name' => 'Smith',
'family_name' => 'Smith'
),
'id' => ';alkdfjglkjhdslfkj',
'id_src' => 'mintymint',
'hints' => array(
'email' => array(
'smith@smith.org',
'smithy@smith.org'
),
'url' => array(
'http://smith.net'
)
)
),
array(
'rendered_val' => 'Spalding, Dave',
'structured_val' => array(
'given_name' => 'Dave',
'family_name' => 'Spalding'
),
'id' => 'foosmithbar',
'id_src' => 'mintymint',
'hints' => array(
'email' => array(
'dave@spalding.com',
),
'url' => array(
'http://spalding.net',
)
)
),
array(
'rendered_val' => 'Jones, Laura',
'structured_val' => array(
'given_name' => 'Laura',
'family_name' => 'Jones'
),
'id' => 'lskdjflskdjflskdjf',
'id_src' => 'mintymint',
'hints' => array(
'email' => array(
'laura@laurajones.net',
),
'url' => array(
'http://laurajones.net',
)
)
)
)
);








function export_eprints($fmldata)
{
	#print_r($fmldata);
	echo count($fmldata["items"]);
	
	echo "\n";
	
	$ul = new SimpleXMLElement('<ul/>');
	foreach ($fmldata["items"] as $item)
	{
		$given_name = $item["structured_val"]["given_name"];
		$family_name = $item["structured_val"]["family_name"];
		$orcid = $item["id"];
		print_r($item["structured_val"]["given_name"]);
		print_r($item["structured_val"]["family_name"]);
		print_r($item["id"]);
		print ($given_name. $family_name. $orcid);

		$li = $ul -> addChild('li');	
		$li -> addChild("span",$given_name.", ".$family_name." [".$orcid."]");
		#$fillul = $li -> addChild("ul");
		#$fillul -> addChild("li");

	}	


	
#	$rtn = 0;
#	$xml = new SimpleXMLElement('<xml/>');

#	for ($i = 1; $i <= 8; ++$i) {
 #   		$track = $xml->addChild('track');
  #  		$track->addChild('path', "song$i.mp3");
   # 		$track->addChild('title', "Track $i - Track Title");
#	}

	Header('Content-type: text/xml');
	$rtn = $ul->asXML();
	print($rtn);


	send_data_eprints($rtn);


} 


export_eprints($data);


?>
