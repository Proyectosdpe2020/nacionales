<?php
session_start();
include("../../../../service/connection.php");
include("../common.php");

$conn = getConn((object) array(
    'db' => 'incidencia_sicap'
));

$ses_key = isset($_POST['ses_key']) ? $_POST['ses_key'] : null;

if($conn && $ses_key != null){

    $sql = "SELECT TOP (1) [UrlID]
				,[Ruta]
			FROM [EJERCICIOS2].[dbo].[ses_key_url]";

    $return = getGenericData(
        (object) array(
            'conn' => $conn,
            'sql' => $sql,
            'params' => array(),
            'options' => array("Scrollable" => SQLSRV_CURSOR_KEYSET)
        )
    );

	$return['data'] = $return['state'] == 'success' ? $return['data'][0]['Ruta']."?ses_key=".$ses_key : null;

    echo json_encode($return, JSON_FORCE_OBJECT);

    sqlsrv_close($conn);
}
else{
    $return = array(
        'state' => 'fail',
        'data' => null
    );

    echo json_encode($return, JSON_FORCE_OBJECT);
}
?>