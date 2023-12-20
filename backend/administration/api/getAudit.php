<?php
    session_start();

    require __DIR__ . '/../../commun/ResponseStatus.php';
    require __DIR__ . '/../../commun/JSONObject.php';
    require __DIR__ . '/../../commun/Errore.php';

    require __DIR__ . '/../models/AuditRecord.php';
    require __DIR__ . '/../controllers/AuditTracking.php';

    $response = new ResponseAuditTracks();
    
    if(isset($_SESSION["id"]) && !empty($_SESSION["id"])){
        try{
            $controller = new AuditTracking();
            $response = $controller->getAuditTracks(filter_input(INPUT_GET, "period"),filter_input(INPUT_GET, "year"));
        }catch(Exception $e){
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage('Ocurrio un error inesperado '+$e);
        }
    }else{
        $response->setStatus(Errore::ERROR_PRIVILEGIOS);
        $response->setMessage('Permiso denegado');
    }
    echo json_encode($response);
?>