<?php
$params = array();
$options = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

function createQuery($attr){

	$sections = array(
		1 => (object) array(
			'url'  => 'senap.php',
			'type' => 'url',
			'text' => 'SENAP'
		),
		2 => (object) array(
			'url'  => 'microdato.php',
			'type' => 'url',
			'text' => 'Microdato'
		),
		3 => (object) array(
			'url'  => 'avp.php',
			'type' => 'url',
			'text' => 'Exportar base de datos histórica'
		),
		4 => (object) array(
			'url'  => 'norma_tecnica.php',
			'type' => 'url',
			'text' => 'Norma Técnica 38-15'
		),
		5 => (object) array(
			'url'  => 'censo_procu.php',
			'type' => 'url',
			'text' => 'Censo procuración de justicia'
		),
		6 => (object) array(
			'url'  => 'incidencia_sesesp.php',
			'type' => 'url',
			'text' => 'Incidencia delictiva SESESP'
		),
		7 => (object) array(
			'url'  => 'validacion_victimas.php',
			'type' => 'url',
			'text' => 'Validación de víctimas'
		),
		8 => (object) array(
			'url'  => 'producto_estadistico.php',
			'type' => 'url',
			'text' => 'Producto estadístico'
		),
		9 => (object) array(
			'url'  => 'sedena.php',
			'type' => 'url',
			'text' => 'Consulta SEDENA'
		),
		10 => (object) array(
			'url'  => 'sesnsp.php',
			'type' => 'url',
			'text' => 'SIIID'
		),
		11 => (object) array(
			'url'  => 'javascript:goToSlideGenerator()',
			'type' => 'js',
			'text' => 'Presentaciones'
		)
	);


}