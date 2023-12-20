<?php
session_start();

require __DIR__ . '/../../../commun/Logger.php';
require __DIR__ . '/../../../commun/JSONObject.php';
require __DIR__ . '/../../../commun/DatabaseEntity.php';
require __DIR__ . '/../../../commun/ResponseStatus.php';
require __DIR__ . '/../../../commun/Errore.php';
require __DIR__ . '/../../controllers/SaldosController.php';
require __DIR__ . '/../../models/saldos.php';


$responseSaldos = new ResponseStatus();
if (isset($_SESSION["id"]) && !empty($_SESSION["id"]) && isset($_SESSION["idCompany"]) && !empty($_SESSION["idCompany"])) {
    try {

        $year=filter_input(INPUT_GET, 'year');

        $saldosModel= new saldos();
        $saldosController = new SaldosController($saldosModel, $year);

        $responseSaldos = $saldosController->getIdSaldosNoLigados();

    } catch (Exception $e) {
        $responseSaldos->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $responseSaldos->setMessage('Ocurrio un error inesperado' + $e->getMessage());
    }
} else {
    $responseSaldos->setStatus(Errore::ERROR_PRIVILEGIOS);
    $responseSaldos->setMessage('Permiso denegado o empresa no seleccionada' );
}

echo json_encode($responseSaldos);

