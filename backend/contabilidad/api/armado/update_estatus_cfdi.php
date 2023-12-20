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
        $estado=filter_input(INPUT_GET, 'estatus');
        $year=filter_input(INPUT_GET, 'year');

        $saldosModel= new saldos();
        $saldosController = new SaldosController($saldosModel,false);

        $res1=$saldosController->setStatusCfdi($folio,$estado);

        $saldosController = new  SaldosController($saldosModel, $year);
        $res2=$saldosController->setStatusCfdi($folio,$estado);

        if($res1==true && $res2==true)
        {
            $response->setMessage("SE HICIERON LAS LIGAS CORRECTAMENTE");
            $response->setStatus(0);
        }
        else
        {
            $response->setMessage("ERROR, NO HICIERON LAS LIGAS CORRECTAMENTE");
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

