<?php
session_start();

require __DIR__ . '/../../../commun/Logger.php';
require __DIR__ . '/../../../commun/JSONObject.php';
require __DIR__ . '/../../../commun/DatabaseEntity.php';
require __DIR__ . '/../../../commun/ResponseStatus.php';
require __DIR__ . '/../../../commun/Errore.php';
require __DIR__ . '/../../controllers/ReporteCobranzaController.php';
require __DIR__ . '/../../models/reporteCobranza.php';

$response1 = new ResponseStatus();
$response= new ResponseReporteCobranza();
if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $year=filter_input(INPUT_GET, 'year');

        $reporteCobranza= new reporteCobranza();
        $reporteCobranzaController = new ReporteCobranzaController($reporteCobranza, $year);

        $response1 = $reporteCobranzaController->getIdCobranzaNoLigada();


        if($response1->getStatus()==0)
        {
            $response->idsCobranza=$response1->getIdCobranza();
            $response->setStatus(0);
        }
        else
        {
            $response->idsCobranza=null;
            $response->setStatus(1);
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

