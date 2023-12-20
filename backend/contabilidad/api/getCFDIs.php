<?php
session_start();

require __DIR__ . '/../../commun/JSONObject.php';
require __DIR__ . '/../../commun/DatabaseEntity.php';
require __DIR__ . '/../../commun/ResponseStatus.php';
require __DIR__ . '/../../commun/Errore.php';
require __DIR__ . '/../controllers/CfdiController.php';
require __DIR__ . '/../models/Cfdi.php';


$response = new ResponseStatus();

if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $cfdiModel= new Cfdi();
        $cfdiController = new CfdiController($cfdiModel,false);
        $periodo=filter_input(INPUT_GET, 'period');
        $year=filter_input(INPUT_GET, 'year');

        $response=$cfdiController->getCfdi($periodo,$year);


    } catch (Exception $e) {
        $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $response->setMessage('Ocurrio un error inesperado' + $e->getMessage());
    }
} else {
    $response->setStatus(Errore::ERROR_PRIVILEGIOS);
    $response->setMessage('Permiso denegado o empresa no seleccionada' );
}



echo json_encode($response);
