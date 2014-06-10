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

function get_data_equipment($params)
{
	$json = json_decode(file_get_contents('../eqipment.data/uniquip.json'), true);

	#give this its own parameter
	$search_string = strtolower($params['equipment']);
	$institution = strtolower($params['institution']);

	$data = array('entity_type' => 'equipment', 'items' => array());

	if (!$search_string)
	{
		return $data;
	}

	$found = 0;
	foreach ($json['records'] as $item)
	{
		if (
			(strpos(strtolower($item['Name']), $search_string) !== false)  
			|| (strpos(strtolower($item['Description']), $search_string) !== false)  
		)
		{
			if (
				$institution
				&& (strpos(strtolower($item['Institution Name']), $institution) === false)
			)
			{
				continue;
			}

			$match = array();
			$match['rendered_val'] = $item['Name'];
			$match['structured_cal'] = array('name' => $item['Name']);
			$match['id'] = $item['__URI'];
			$match['id_source'] = 'http://equipment.data.ac.uk';

			$match['hints'] = array();
			if ($item['Institution Name'])
			{
				$match['hints']['institution'] = array($item['Institution Name']);
			}
			if ($item['Web Address'])
			{
				$match['hints']['url'] = array($item['Web Address']);
			}
			if ($item['Description'])
			{
				$match['hints']['description'] = array(strip_tags($item['Description']));
			}

			$data['items'][] = $match;
			$found++;
			if ($found > 50)
			{
				break;
			}
		}
	}
	return $data;
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

function send_data_html($f3, $data)
{
	echo '<ul>' . recurseTree($data) . '</ul>';
}


function recurseTree($var){

	$out = '';
	if (!is_array($var))
	{
		return $var;
	}
	else
	{
		$out .= '<ul>';
		foreach ($var as $k => $v)
		{
			$out .= "<li><strong>$k</strong> = " . recurseTree($v) . '</li>';

		}
		$out .= '</ul>';
	}

	return $out;
}


?>
