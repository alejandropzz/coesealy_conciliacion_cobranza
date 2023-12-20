<?php
session_start();

require __DIR__ . '/../../../commun/JSONObject.php';
require __DIR__ . '/../../../commun/DatabaseEntity.php';
require __DIR__ . '/../../../commun/ResponseStatus.php';
require __DIR__ . '/../../../commun/Errore.php';
require __DIR__ . '/../../controllers/ReporteCobranzaController.php';
require __DIR__ . '/../../models/reporteCobranza.php';


$response = new ResponseStatus();

if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $reporteCobranzaModel= new reporteCobranza();

        $data=json_decode(filter_input(INPUT_POST, 'data'));

        $reporteCobranzaModel->parseValues($data);

        $reporteCobranzaController = new ReporteCobranzaController($reporteCobranzaModel,false);
        $response = $reporteCobranzaController->getCFDI_Saldo_GL();

        $response->setObject($data);


    } catch (Exception $e) {
        $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $response->setMessage('Ocurrio un error inesperado' + $e->getMessage());
    }
} else {
    $response->setStatus(Errore::ERROR_PRIVILEGIOS);
    $response->setMessage('Permiso denegado o empresa no seleccionada' );
}



echo json_encode($response);
