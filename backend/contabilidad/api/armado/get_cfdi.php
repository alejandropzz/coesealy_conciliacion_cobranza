<?php
session_start();

require __DIR__ . '/../../../commun/Logger.php';
require __DIR__ . '/../../../commun/JSONObject.php';
require __DIR__ . '/../../../commun/DatabaseEntity.php';
require __DIR__ . '/../../../commun/ResponseStatus.php';
require __DIR__ . '/../../../commun/Errore.php';
require __DIR__ . '/../../controllers/SaldosController.php';
require __DIR__ . '/../../models/saldos.php';


$response = new ResponseStatus();

if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $folio=filter_input(INPUT_GET, 'folio');
        $saldosModel= new saldos();
        $saldosController = new SaldosController($saldosModel,false);
        $response = $saldosController->getCfdiByFolio($folio);

        if($response->status==0)
        {
            $year=$response->cfdi["year"];

            $borarCfdi=$saldosController->deleteCfdiByFolio($folio);

            $saldosController2 = new SaldosController($saldosModel,$year);
            $statusCfdi=$saldosController2->setStatusCfdi($folio,3);
            if($statusCfdi==true && $borarCfdi==true)
                $response->setMessage("SE HICIERON LAS LIGAS CORRECTAMENTE");

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

