<?php
session_start();

require __DIR__ . '/../../commun/JSONObject.php';
require __DIR__ . '/../../commun/DatabaseEntity.php';
require __DIR__ . '/../../commun/ResponseStatus.php';
require __DIR__ . '/../../commun/Errore.php';

require __DIR__ . '/../models/cuentasCobrarFactura.php';
require __DIR__ . '/../controllers/CuentasCobrarController.php';

$response = new ResponseStatus();

if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $cuentasCobrar= new cuentasCobrarFactura();    //CREAR MODELO
        $cuentasCobrar->UUID=filter_input(INPUT_GET, 'uuid');
        $cuentasCobrar->number_folio=filter_input(INPUT_GET, 'folio');

        $cuentasCobrarController = new CuentasCobrarController($cuentasCobrar);   //CREAR CONTROLADOR
        $response=$cuentasCobrarController->getCFDI_iva_total(filter_input(INPUT_GET, 'index'));

    } catch (Exception $e) {
        $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $response->setMessage('Ocurrio un error inesperado' + $e->getMessage());
    }
} else {
    $response->setStatus(Errore::ERROR_PRIVILEGIOS);
    $response->setMessage('Permiso denegado o empresa no seleccionada' );
}

echo json_encode($response);
