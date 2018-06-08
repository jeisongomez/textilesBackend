<?php

require_once '../vendor/autoload.php';

$app = new \Slim\Slim();

$db = new mysqli('localhost', 'root', '', 'textilesexe');

// Configuración de cabeceras
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

//prueba para el API
$app->get("/pruebas", function() use($app, $db){
	echo "Hola mundo desde Slim PHP para clientes";
});


//guardar un usuario
$app->post('/add-usuario', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$query = " INSERT INTO `usuarios`(`tipo_usuario`, `nombre_pri`, `nombre_seg`, `apellido_pri`,
						   `apellido_seg`, `num_identidad`, `telefono`, `direccion`, `correo`,
						   `ventas`, `compras`, `reportes`, `usuario`, `contrasena`) 
	    	   VALUES ('{$data["tipo_usuario"]}','{$data["nombre_pri"]}','{$data["nombre_seg"]}',
			           '{$data["apellido_pri"]}','{$data["apellido_seg"]}','{$data["num_identidad"]}',
					   '{$data["telefono"]}','{$data["direccion"]}','{$data["correo"]}',
					   '{$data["ventas"]}','{$data["compras"]}','{$data["reportes"]}',
					   '{$data["usuario"]}','{$data["contrasena"]}') ";
 
	//var_dump($query);

	$insert = $db->query($query);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
		'message' => 'El usuario NO se ha creado'
	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
			'message' => 'usuario creado correctamente'
		);
	}

	echo json_encode($result);
});

$app->run();
