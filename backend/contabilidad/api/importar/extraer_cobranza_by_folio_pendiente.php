<?php
session_start();

require __DIR__ . '/../../../commun/Logger.php';
require __DIR__ . '/../../../commun/JSONObject.php';
require __DIR__ . '/../../../commun/DatabaseEntity.php';
require __DIR__ . '/../../../commun/ResponseStatus.php';
require __DIR__ . '/../../../commun/Errore.php';
require __DIR__ . '/../../controllers/ReporteCobranzaController.php';
require __DIR__ . '/../../models/reporteCobranza.php';

$response = new ResponseStatus();

if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $folio=filter_input(INPUT_GET, 'folio');
        $mes=filter_input(INPUT_GET, 'mes');
        $year=filter_input(INPUT_GET, 'year');

        $reporteCobranza= new reporteCobranza();
        $reporteCobranzaController = new ReporteCobranzaController($reporteCobranza, $year);

        $response = $reporteCobranzaController->getCobranzaByFolioPendiente($folio, $mes);


    } catch (Exception $e) {
        $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $response->setMessage('Ocurrio un error inesperado' + $e->getMessage());
    }
} else {
    $response->setStatus(Errore::ERROR_PRIVILEGIOS);
    $response->setMessage('Permiso denegado o empresa no seleccionada' );
}



echo json_encode($response);

