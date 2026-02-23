<?php

$module_search_op = $_POST['module_search_op'];

$procedure_op = array(
	2 => array(
		array(
			'id' => 1,
			'name' => '1 Sistema Tradicional'
		),
		array(
			'id' => 2,
			'name' => '2 Comparecencias'
		),
		array(
			'id' => 3,
			'name' => '3 Etapa de investigación inicial'
		),
		array(
			'id' => 4,
			'name' => '4 Etapa de investigación complementaria'
		),
		array(
			'id' => 5,
			'name' => '5 Pendientes por concluir'
		),
		array(
			'id' => 6,
			'name' => '6 Medidas cautelares y de protección'
		),
		array(
			'id' => 7,
			'name' => '7 Ordenes judiciales'
		),
		array(
			'id' => 8,
			'name' => '8 Narcomenudeo'
		),
		array(
			'id' => 9,
			'name' => '9 Exploración especifica de delitos seleccionados'
		),
		array(
			'id' => 10,
			'name' => '10 Bienes robados'
		),
		array(
			'id' => 11,
			'name' => '11 Aseguramientos'
		),
		array(
			'id' => 12,
			'name' => '12 Anexos'
		)
	),
	3 => array(
		array(
			'id' => 1,
			'name' => '1 Sistema Tradicional'
		),
		array(
			'id' => 2,
			'name' => '2 Etapa de investigación inicial'
		),
		array(
			'id' => 3,
			'name' => '3 Etapa de investigación complementaria'
		),
		array(
			'id' => 4,
			'name' => '4 Pendientes por concluir'
		),
		array(
			'id' => 5,
			'name' => '5 Anexos'
		),
		array(
			'id' => 8,
			'name' => '8 Exploración específica de delitos seleccionados'
		)
	)
);

echo json_encode(
	$procedure_op[$module_search_op],
	JSON_FORCE_OBJECT
);

?>