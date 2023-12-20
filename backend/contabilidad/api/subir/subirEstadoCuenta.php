<?php
session_start();
require __DIR__ . '/../../../commun/Logger.php';
require __DIR__ . '/../../../commun/JSONObject.php';
require __DIR__ . '/../../../commun/DatabaseEntity.php';
require __DIR__ . '/../../../commun/ResponseStatus.php';
require __DIR__ . '/../../../commun/Errore.php';
require __DIR__ . '/../../controllers/EstadoCuentaController.php';
require __DIR__ . '/../../models/EstadoCuenta.php';


$response = new ResponseStatus();

if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {


        $estadoCuenta= new EstadoCuenta();



        $data=json_decode(filter_input(INPUT_POST, 'data'));

        $estadoCuenta->parseValues($data);
        $estadoCuentaController = new EstadoCuentaController($estadoCuenta, false);
        $response = $estadoCuentaController->guardarEstadoCuenta();





    } catch (Exception $e) {
        $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $response->setMessage('Ocurrio un error inesperado' + $e->getMessage());
    }
} else {
    $response->setStatus(Errore::ERROR_PRIVILEGIOS);
    $response->setMessage('Permiso denegado o empresa no seleccionada' );
}



echo json_encode($response);

