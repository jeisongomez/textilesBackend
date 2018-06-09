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

$app->post('/ganado', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);


	if (!isset($data['fechaInicio'])) {
		$data['fechaInicio'] = null;
	}
	if (!isset($data['fechaFin'])) {
		$data['fechaFin'] = null;
	}

	//var_dump($data);

	$query = "SELECT SUM(total_toda_venta) FROM ventas WHERE fecha_venta >="."'{$data['fechaInicio']}' AND fecha_venta <="."'{$data['fechaFin']}';";

	$insert = $db->query($query);

	//var_dump($query);

	$result = array(
		'status' => 'error',
		'code'	 => '404',
		'message' => 'NO hay ventas en el rango indicado'
		);

	if ($insert->num_rows == 1) {
		$valor = $insert->fetch_assoc();

		$result = array(
			'status' => 'sucess',
			'code'	 => '200',
			'data' => $valor
			);
	}

	echo json_encode($result);

});

$app->run();