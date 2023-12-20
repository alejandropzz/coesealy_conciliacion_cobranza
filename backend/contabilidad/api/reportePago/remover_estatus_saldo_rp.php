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
$logger = new Logger();
if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $id=filter_input(INPUT_GET, 'id');
        $status=json_decode(filter_input(INPUT_GET, 'status'));
        $year=json_decode(filter_input(INPUT_GET, 'year'));

        $saldosModel= new saldos();
        $saldosController = new SaldosController($saldosModel, $year);


        $res = $saldosController->removerStatusSaldoRp($id, $status);

        if($res)
        {
            $response->setStatus(0);
            $message = "OK|UPDATED ESTATUS SALDO RP|"."folio:".$folio." |INSERTION OK";
            $response->message=$message;
            $logger->logEntry("Update ESTATUS saldo.SaldosController",$message,date("Y-m-d"));

        }
        else
        {
            $response->setStatus(-1);
            $message = "ERROR|UPDATED ESTATUS SALDO|"."folio:".$folio." |INSERTION FAIL";
            $response->message=$message;
            $logger->logEntry( "ERROR Update ESTATUS saldo.SaldosController",$message,date("Y-m-d"));
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

