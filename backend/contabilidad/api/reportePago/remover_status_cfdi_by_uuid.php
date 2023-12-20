<?php
session_start();
//USADO
require __DIR__ . '/../../../commun/Logger.php';
require __DIR__ . '/../../../commun/JSONObject.php';
require __DIR__ . '/../../../commun/DatabaseEntity.php';
require __DIR__ . '/../../../commun/ResponseStatus.php';
require __DIR__ . '/../../../commun/Errore.php';
require __DIR__ . '/../../controllers/CfdiController.php';
require __DIR__ . '/../../models/Cfdi.php';


$response = new ResponseStatus();

if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $uuid_cfdi=filter_input(INPUT_GET, 'uuid_cfdi');
        $status=filter_input(INPUT_GET, 'status');
        $year=filter_input(INPUT_GET, 'year');


        $cfdiModel= new Cfdi();
        $cfdiController = new CfdiController($cfdiModel, $year);


        $res = $cfdiController->removerStatusCfdiRp($uuid_cfdi,$status);
        if($res==true)
        {
            $response->setMessage("SE ACTUALIZO EL STATUS DEL CFDI CORRECTAMENTE");
            $response->setStatus(0);
        }

        else
        {
            $response->setMessage("SE ACTUALIZO EL STATUS DEL CFDI CORRECTAMENTE");
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

