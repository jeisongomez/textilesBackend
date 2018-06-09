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


$app->post('/productos', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	if (!isset($data['codigo_prod'])) {
		$data['codigo_prod'] = null;
	}else{
		$data['codigo_prod'] = (int)$data['codigo_prod'];
	}
	if (!isset($data['proveedores_idproveedores'])) {
		$data['proveedores_idproveedores'] = null;
	}else{
		$data['proveedores_idproveedores'] = (int)$data['proveedores_idproveedores'];
	}
	if (!isset($data['nombre'])) {
		$data['nombre'] = null;
	}
	if (!isset($data['presentacion'])) {
		$data['presentacion'] = null;
	}
	if (!isset($data['cant_dispon'])) {
		$data['cant_dispon'] = null;
	}
	if (!isset($data['precio_venta'])) {
		$data['precio_venta'] = null;
	}

	//var_dump($data);

	$query = " INSERT INTO `productos`(`codigo_prod`, `proveedores_idproveedores`, `nombre`,
									   `presentacion`, `cant_dispon`, `precio_venta`)
			   VALUES ('{$data['codigo_prod']}','{$data['proveedores_idproveedores']}',
				 	   '{$data['nombre']}','{$data['presentacion']}','{$data['cant_dispon']}',
					   '{$data['precio_venta']}') ";

	$insert = $db->query($query);

	//var_dump($query);

	$result = array(
		'status' => 'error',
		'code'	 => '404',
		'message' => 'Producto NO creado'
	);

	if ($insert) {
		$result = array(
		'status' => 'sucess',
		'code'	 => '200',
		'message' => 'Producto Creado Correctamente'
		);
	}

	echo json_encode($result);

});

$app->post('/proveedores', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	if (!isset($data['idproveedores'])) {
		$data['idproveedores'] = 0;
	}else{
		$data['idproveedores'] = (int)$data['idproveedores'];
	}
	if (!isset($data['nombre_proveedor'])) {
		$data['nombre_proveedor'] = "null";
	}
	if (!isset($data['telefono'])) {
		$data['telefono'] = "null";
	}
	if (!isset($data['correo'])) {
		$data['correo'] = "null";
	}

	//var_dump($data);

	$query = "INSERT INTO proveedores VALUES("."'{$data['idproveedores']}',"."'{$data['nombre_proveedor']}',".
		"'{$data['telefono']}',".
		"'{$data['correo']}'".
		");";

	$insert = $db->query($query);

	//var_dump($query);

	$result = array(
		'status' => 'error',
		'code'	 => '404',
		'message' => 'Proveedor NO creado'
	);

	if ($insert) {
		$result = array(
		'status' => 'sucess',
		'code'	 => '200',
		'message' => 'Proveedor Creado Correctamente'
		);
	}

	echo json_encode($result);

});

$app->run();