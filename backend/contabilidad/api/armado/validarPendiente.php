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

        $eps=10;

        $folio=filter_input(INPUT_GET, 'folio');
        $mes=filter_input(INPUT_GET, 'mes');
        $year=filter_input(INPUT_GET, 'year');


        $saldosModel= new saldos();

        $saldosControllerYear = new SaldosController($saldosModel,false);
        $sumaYear=$saldosControllerYear->getSumYearLocal($folio, $year);


        $saldosControllerMes = new SaldosController($saldosModel,$year);
        $sumaMes = $saldosControllerMes->getSumMesLocal($folio, $mes);

        $saldosControllerTotal = new SaldosController($saldosModel,false);
        $total = $saldosControllerTotal->getTotalSaldoLocal($folio);

        if($total!=null)
        {
            if($sumaYear+$sumaMes+$eps<$total)
            {
                $response->setStatus(0);
                $response->setObject($sumaYear+$sumaMes);
            }
            else
                $response->setStatus(1);
        }
        else
            $response->setStatus(-1);



    } catch (Exception $e) {
        $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $response->setMessage('Ocurrio un error inesperado' + $e->getMessage());
    }
} else {
    $response->setStatus(Errore::ERROR_PRIVILEGIOS);
    $response->setMessage('Permiso denegado o empresa no seleccionada' );
}



echo json_encode($response);

