<?





function send_data_eprints($f3,$fmldata)
{
	#print_r($fmldata);
#	echo count($fmldata["items"]);
	
#	echo "\n";
	
	$ul = new SimpleXMLElement('<ul/>');
	foreach ($fmldata["items"] as $item)
	{
		$given_name = $item["structured_val"]["given_name"];
		$family_name = $item["structured_val"]["family_name"];
		$orcid = $item["id"];
#		print_r($item["structured_val"]["given_name"]);
#		print_r($item["structured_val"]["family_name"]);
#		print_r($item["id"]);
#		print ($given_name. $family_name. $orcid);

		$li = $ul->addChild('li');	
		$li->addChild("span",$given_name.", ".$family_name." [".$orcid."]");
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

	header('Content-type: text/xml');
	echo $ul->asXML();
} 



?>
