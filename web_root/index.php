<?php

$f3=require('../fatfree-master/lib/base.php');
$f3->config('config.ini');

$url = $f3->get("SCHEME")."://".$f3->get("HOST").$f3->get("BASE");
$f3->set("BASEURL", $url);

$f3->run();



function fillmylist($f3)
{




}



function human_inputform($f3)
{
	$f3->set('templates', array('humanform.html'));
	echo Template::instance()->render("main.html");
	exit;
}


?>
