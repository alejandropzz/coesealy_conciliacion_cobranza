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

        $folio=filter_input(INPUT_GET, 'folio');
        $id_factura=filter_input(INPUT_GET, 'id_factura');
        $fecha_emision=filter_input(INPUT_GET, 'fecha_emision');
        $years=json_decode(filter_input(INPUT_GET, 'year'));

        $banderaUpdate=true;

        $saldosModel= new saldos();
        $saldosController = new SaldosController($saldosModel, false);


        $updateRes = $saldosController->updateIdFactura($folio, $id_factura, $fecha_emision);

        if($updateRes==false)
            $banderaUpdate=false;

        for ($i=0;$i<sizeof($years);$i++)
        {
            $saldosController=new SaldosController($saldosModel,$years[$i]);
            $updateRes = $saldosController->updateIdFactura($folio, $id_factura, $fecha_emision);
            if($updateRes==false)
                $banderaUpdate=false;
        }



        if($banderaUpdate==true)
        {
            $response->setStatus(0);
            $message = "OK|UPDATED ALL SALDO|"."folio:".$folio." |INSERTION OK";
            $response->message=$message;
            $logger->logEntry("Update all saldo.SaldosController",$message,date("Y-m-d"));

        }
        else
        {
            $response->setStatus(-1);
            $message = "ERROR|UPDATED ALL SALDO|"."folio:".$folio." |INSERTION FAIL";
            $response->message=$message;
            $logger->logEntry( "ERROR Update all saldo.SaldosController",$message,date("Y-m-d"));
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

