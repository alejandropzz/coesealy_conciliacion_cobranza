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
        $estatusList=json_decode(filter_input(INPUT_GET, 'estatus'));
        $fecha_cierre=json_decode(filter_input(INPUT_GET, 'fecha_cierre'));
        $years=json_decode(filter_input(INPUT_GET, 'year'));

        $saldosModel= new saldos();
        $saldosController = new SaldosController($saldosModel, false);

        //$estadoActual=$saldosController->getStatusSaldo($folio);
        $estadoActual=1;
        $nuevoEstado=$saldosController->crearEstadoSaldo($estadoActual,$estatusList);


        $banderaUpdateCompleto=true;
        $banderaUpdate=true;

        $updateRes = $saldosController->setStatusSaldo($folio, $nuevoEstado);

        $updateResCompleto = $saldosController->setFechaCierre($folio, $fecha_cierre);
        if($updateResCompleto==false)
            $banderaUpdateCompleto=false;



        if($updateRes==false)
            $banderaUpdate=false;

        for ($i=0;$i<sizeof($years);$i++)
        {
            $saldosController=new SaldosController($saldosModel,$years[$i]);
            //$estadoA=$saldosController->getStatusSaldo($folio);
            $estadoA=1;
            $nuevoE=$saldosController->crearEstadoSaldo($estadoA,$estatusList);
            $updateRes = $saldosController->setStatusSaldo($folio, $nuevoE);
            if($updateRes==false)
                $banderaUpdate=false;

            $updateResCompleto = $saldosController->setFechaCierre($folio, $fecha_cierre);
            if($updateResCompleto==false)
                $banderaUpdateCompleto=false;



        }



        if($banderaUpdate==true && $banderaUpdateCompleto==true)
        {
            $response->setStatus(0);
            $message = "OK|UPDATED ESTATUS SALDO|"."folio:".$folio." |INSERTION OK";
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

