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


//guardar un cliente
$app->post('/add-cliente', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	if(!isset($data['segNombre'])){
		$data['segNombre']=null;
	}

	if(!isset($data['segApellido'])){
		$data['segApellido']=null;
	}
	
	$query = "INSERT INTO clientes VALUES(  NULL,
											'{$data["priNombre"]}', 
											'{$data["segNombre"]}', 
										    '{$data["priApellido"]}', 
											'{$data["segApellido"]}', 
											'{$data["docIdentidad"]}', 
											'{$data["telefono"]}', 
											'{$data["direccion"]}', 
											'{$data["email"]}')";
    
	//var_dump($query);

	$insert = $db->query($query);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
		'message' => 'El cliente NO se ha creado'
	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
			'message' => 'Cliente creado correctamente'
		);
	}

	echo json_encode($result);
});

$app->post('/clienteExist', function() use($db, $app){
	$json = $app->request->post('json');
    $data = json_decode($json, true);
    
    $sql = "SELECT * FROM clientes WHERE num_identidad = '{$data["docIdentidad"]}'";

	$query = $db->query($sql);

    $result = array(
			'status' 	=> 'success',
			'code'		=> 200,
			'message' 	=> 'el cliente no existe'
		);
    
    //var_dump($sql);
    //var_dump($query);

	if($query->num_rows == 1){
		$usuario = $query->fetch_assoc();

        $result = array(
			'status' 	=> 'error',
			'code'		=> 400,
			'message'  => 'el cliente ya existe',
			'data' 	=> $usuario
		);
	}

	echo json_encode($result);

});

$app->run();
