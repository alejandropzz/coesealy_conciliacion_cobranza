<?php
session_start();
//USADO
require __DIR__ . '/../../../commun/Logger.php';
require __DIR__ . '/../../../commun/JSONObject.php';
require __DIR__ . '/../../../commun/DatabaseEntity.php';
require __DIR__ . '/../../../commun/ResponseStatus.php';
require __DIR__ . '/../../../commun/Errore.php';
require __DIR__ . '/../../controllers/ReportePagoController.php';
require __DIR__ . '/../../models/ReportePago.php';


$response = new ResponseStatus();

if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $uuid_rp=filter_input(INPUT_GET, 'uuid_rp');

        $reportePago = new ReportePago();
        $reportePagoController = new ReportePagoController($reportePago, false);

        $response = $reportePagoController->borrarDiot($uuid_rp);
        if($response->getStatus()==0)
        {
            $response->setMessage("SE BORRO EL REPORTE DE PAGO CORRECTAMENTE");
            $response->setStatus(0);
        }

        else
        {
            $response->setMessage("ERROR AL BORRAR EL REPORTE DE PAGO");
            $response->setStatus(-1);
        }


    } catch (Exception $e) {
        $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $response->setMessage('Ocurrio un error inesperado' + $e->getMessage());
    }
} else {
    $response->setStatus(Errore::ERROR_PRIVILEGIOS);
    $response->setMessage('Permiso denegado o empresa no seleccionada' );
}



echo json_encode($response);

