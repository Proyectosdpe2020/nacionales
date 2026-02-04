<?php

$init_year = 2018;
$finish_year = date('Y');
$return = array();

for($i = $init_year ; $i < $finish_year ; $i++){

	array_push($return, array(
		array(
			'id' => $i,
			'name' => $i
		)
	));
}

echo json_encode(
	$return,
	JSON_FORCE_OBJECT
);

?>