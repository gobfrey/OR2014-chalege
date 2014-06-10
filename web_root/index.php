<?php

require('../ORCID.php');

$f3=require('../fatfree-master/lib/base.php');
$f3->config('config.ini');

$url = $f3->get("SCHEME")."://".$f3->get("HOST").$f3->get("BASE");
$f3->set("BASEURL", $url);

$f3->run();



function fillmylist($f3)
{
	$params = $f3->get('REQUEST');

	$entity_type = $params['entity_type'];
	$output = $params['output'];

	$function_name = "get_data_$entity_type";
	$data = call_user_func($function_name, $params);

	$function_name = "send_data_$output";
	call_user_func($function_name, $f3, $data);
}




function human_inputform($f3)
{
	$f3->set('templates', array('humanform.html'));
	echo Template::instance()->render("main.html");
	exit;
}

function send_data_json($f3, $data)
{
	header('Content-Type: application/json');
	echo json_encode($data);
}

?>
