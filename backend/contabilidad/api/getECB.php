<?php
session_start();

require __DIR__ . '/../../commun/JSONObject.php';
require __DIR__ . '/../../commun/DatabaseEntity.php';
require __DIR__ . '/../../commun/ResponseStatus.php';
require __DIR__ . '/../../commun/Errore.php';
require __DIR__ . '/../models/EstadoCuenta.php';
require __DIR__ . '/../controllers/EstadoCuentaController_NEW.php';


$response = new ResponseStatus();

if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $estadoCuenta= new EstadoCuenta();
        $estadoCuentaController = new EstadoCuentaController($estadoCuenta, false);
        $response = $estadoCuentaController->getEstadoCuenta(filter_input(INPUT_GET, 'period'),filter_input(INPUT_GET, 'year'),filter_input(INPUT_GET, 'idCuenta'));


    } catch (Exception $e) {
        $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $response->setMessage('Ocurrio un error inesperado' + $e->getMessage());
    }
} else {
    $response->setStatus(Errore::ERROR_PRIVILEGIOS);
    $response->setMessage('Permiso denegado o empresa no seleccionada' );
}



echo json_encode($response);

