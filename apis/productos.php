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
	echo "Hola mundo desde Slim PHP";
});

//mostrar todos los productos
$app->get('/productos', function() use($db, $app){
	$sql = "SELECT * FROM `productos`";
	$query = $db->query($sql);

	//var_dump($sql);
	//var_dump($query);

	$productos = array();
	while ($producto = $query->fetch_assoc()) {
		$productos[] = $producto;
	}

	//var_dump($productos);

	$result = array(
			'status' => 'success',
			'code'	 => 200,
			'data' => $productos
		);

	echo json_encode($result);
});

$app->post('/update-producto', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$query = " UPDATE `productos` SET `codigo_prod` = {$data["codigo_prod"]},
										`idproductos`={$data["idproductos"]},
										`proveedores_idproveedores`={$data["proveedores_idproveedores"]},
										`nombre`='{$data["nombre"]}',
										`presentacion`='{$data["presentacion"]}',
										`cant_dispon`='{$data["cant_dispon"]}',
										`precio_venta`='{$data["precio_venta"]}'
										 WHERE `idproductos` = {$data["idproductos"]} ";

    $insert = $db->query($query);
    var_dump($query);
    //var_dump($insert);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
        'message' => 'el producto no se ha actualizado'
 	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
            'message' => 'producto actualizado correctamente',
		);
	}

	echo json_encode($result);
});


$app->run();
