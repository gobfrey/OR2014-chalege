<?php

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

print_r($data);


?>
