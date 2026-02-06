<?php
session_start();
include("../../../../service/connection.php");
include("../common.php");

$conn = getConn((object) array(
    'db' => 'incidencia_sicap'
));

$ses_key = hash('sha256', date('Y-m-d H:i:s'));

$return = createQuery(
	(object) array(
        'conn' => $conn,
        'params' => array(),
        'options' => array("Scrollable" => SQLSRV_CURSOR_KEYSET),
		'db_table' => '[nacionales].[ses_key]',
		'data' => array(
			((object) array(
				'column' => 'ses_key',
				'value' => $ses_key,
                'type_value' => 'text'
			))
		)
	)
);

$return['data'] = $return['state'] == 'success' ? array(
    'ses_key' => $ses_key
) : null;

echo json_encode($return, JSON_FORCE_OBJECT);
sqlsrv_close($conn);
?>