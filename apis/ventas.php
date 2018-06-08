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


//guardar una venta
$app->post('/add-venta', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$query = "INSERT INTO `ventas` (`usuarios_idusuarios`, `clientes_idclientes`, `cantidad_productos`, `total_toda_venta`, `tipoPago`, `dias`)
              VALUES('{$data["idusuarios"]}', '{$data["idclientes"]}', '{$data["cantidad_productos"]}', '{$data["total_toda_venta"]}', '{$data["tipoPago"]}', '{$data["dias"]}')";
    
    $insert = $db->query($query);
    //var_dump($query);
    //var_dump($insert);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
        'message' => 'La venta NO se ha creado'
 	);

	if($insert){

        $query2 = " SELECT `idventas` FROM ventas WHERE `idventas` = ( SELECT MAX( `idventas` ) FROM ventas) ";
        $insert2 = $db->query($query2);

        if($insert2->num_rows == 1){
            $idventa = $insert2->fetch_assoc();
        }

		$result = array(
			'status' => 'success',
			'code'	 => 200,
            'message' => 'Venta creada correctamente',
            'data' => $idventa
		);
	}

	echo json_encode($result);
});

//guardar un detalle de venta
$app->post('/add-Detalle-venta', function() use($app, $db){
	$json = $app->request->post('json');
	$data = json_decode($json, true);

	$query = " INSERT INTO `productos_has_ventas`(`productos_codigo_prod`, `productos_idproductos`, `ventas_idventas`, `total_productos`, `total_venta`) 
			   VALUES ({$data["productos_codigo_prod"]},{$data["productos_idproductos"]},{$data["ventas_idventas"]},'{$data["total_productos"]}','{$data["total_venta"]}') ";

    
    $insert = $db->query($query);
    var_dump($query);
    //var_dump($insert);

	$result = array(
		'status' => 'error',
		'code'	 => 404,
        'message' => 'La venta NO se ha creado'
 	);

	if($insert){
		$result = array(
			'status' => 'success',
			'code'	 => 200,
            'message' => 'Venta creada correctamente',
		);
	}

	echo json_encode($result);
});


$app->run();
