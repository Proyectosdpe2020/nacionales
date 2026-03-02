<?php
session_start();
include('connection.php');
include('validate_injection_string.php');

$conn = getConn((object) array(
    'db' => 'incidencia_sicap'
));

$data = json_decode($_POST['auth'], true );
$user = cleanTextInjec($data['username']);
$pass = hash('sha256', cleanTextInjec($data['password']));

if($conn){

    $sql = "SELECT TOP (1) [UsuarioID] AS 'id'
                ,[Nombre]
                ,[Paterno]
                ,[Materno]
                ,[Usuario]
                ,[Tipo]
            FROM [nacionales].[Usuarios]
            WHERE [Usuario] = '$user'
            AND [Contrasena] = '$pass'
            AND Estatus = 1";

    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result = sqlsrv_query( $conn, $sql , $params, $options );

    $row_count = sqlsrv_num_rows( $result );

    $json = '';
    $return = array();

    if($row_count != 0){

        while( $row = sqlsrv_fetch_array( $result) ) {
            $json = json_encode($row);
        }
        
        $json = json_decode($json, true);

        $perms = getPerms((object) array(
            'id' => $json['id'],
            'type' => $json['Tipo'],
            'conn' => $conn,
            'params' => $params,
            'options' => $options
        ));

        $sections = getSections((object) array(
            'perms' => $perms,
            'type' => $json['Tipo']
        ));
        
        if(!empty($sections)){
            $ses = array(
                'id' => $json['id'],
                'username' => $json['Usuario'],
                'name' => $json['Nombre'],
                'paternal_surname' => $json['Paterno'],
                'maternal_surname' => $json['Materno'],
                'type' => $json['Tipo'],
                'perms' => $sections
            );

            setSessVariables($ses);

            $return = array(
                'state' => 'success',
                'data' => array(
                    'reedirect' => 'src/admin/'.$sections[array_keys($sections)[0]]->url
                )
            );
        }
        else{
            $return = array(
                'state' => 'not_found',
                'data' => null
            );
        }
    }
    else{
        $return = array(
            'state' => 'not_found',
            'data' => null
        );
    }

    echo json_encode($return, JSON_FORCE_OBJECT);
    sqlsrv_close($conn);
}
else{
    $return = array(
        'state' => 'fail',
        'data' => null
    );

    echo json_encode($return, JSON_FORCE_OBJECT);

    sqlsrv_close($conn);
}

function getPerms($attr){

    if($attr->conn){

        $sql = "SELECT [seccionid] AS 'sec'
                    FROM [nacionales].[permisos]
                    WHERE usuarioid = '$attr->id'";

        $result = sqlsrv_query($attr->conn, $sql, $attr->params, $attr->options);

        $row_count = sqlsrv_num_rows($result);

        $return = array();

        if($row_count != 0){

            while($row = sqlsrv_fetch_array($result)){

                array_push($return, $row['sec']);
            }
        }
        return $return;
    }
    else{
        return array();
    }
}

function setSessVariables($attr){

    $_SESSION['user_data'] = array(
        'id' => $attr['id'],
        'username' => $attr['username'],
        'name' => $attr['name'],
        'paternal_surname' => $attr['paternal_surname'],
        'maternal_surname' => $attr['maternal_surname'],
        'type' => $attr['type'],
        'perms' => $attr['perms']
    );

    return $attr['type'] != 1 && $attr['type'] != 2 ? false : true;
}

function getSections($attr){

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
			'url'  => 'senap.php',
			'type' => 'js',
			'text' => 'Presentaciones'
		)
	);

    $sec_perm = array();

    foreach($attr->perms as $perm){
        if(isset($sections[$perm])){
            $sec_perm[$perm] = $sections[$perm];
        }
    }

    return $attr->type != 1 ? $sec_perm : $sections;
}
?>