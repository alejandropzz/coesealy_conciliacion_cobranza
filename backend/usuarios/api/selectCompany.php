<?php
require __DIR__ . '/../../commun/Logger.php';
require __DIR__ . '/../../commun/ResponseStatus.php';

    if (!empty(filter_input(INPUT_POST, 'id'))  && !empty(filter_input(INPUT_POST, 'name'))) {
            session_start();
            $_SESSION["idCompany"] =filter_input(INPUT_POST, 'id');
            $_SESSION["company"] = filter_input(INPUT_POST, 'name');
            $respuesta = new ResponseStatus();
            $respuesta->setStatus(0);
            echo json_encode($respuesta);
        
    } 