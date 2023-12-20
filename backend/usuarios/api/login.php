<?php

require __DIR__ . '/../models/User.php';
require __DIR__ . '/../../commun/DatabaseEntity.php';
require __DIR__ . '/../../commun/ResponseStatus.php';
require __DIR__ . '/../../commun/Errore.php';
require __DIR__ . '/../controllers/UserController.php';
require __DIR__ . '/../../commun/Logger.php';

$respuesta = new ResponseUser();
$logger    = new Logger();

try {
    if (!empty(filter_input(INPUT_POST, 'inEmail'))  && !empty(filter_input(INPUT_POST, 'inPasswd'))) {
        $user = new User();
        $user->setEmail(filter_input(INPUT_POST, 'inEmail'));
        $user->setPasswd(filter_input(INPUT_POST, 'inPasswd'));
        
        $userController = new UserController($user);
        $registeredUser=$userController->getUser();
         if ($registeredUser === NULL) {
            header('Location: /coesealy-conciliacion-cobranza/index.php?result=' . Errore::ERROR_PRIVILEGIOS);
            $logger->logAccess("Inicio de sesion invalido: Usuario: ".filter_input(INPUT_POST, 'inEmail'));
        } else {
            session_start();
            $_SESSION["name"] = $registeredUser->getName();
            $_SESSION["email"] = $registeredUser->getEmail();
            $_SESSION["priv"] = $registeredUser->getPriv();
            $_SESSION["id"] = $registeredUser->getId();

            $logger->logAccess("Inicio sesion el usuario ".$_SESSION["name"]);

            header('Location: /coesealy-conciliacion-cobranza/dashboard/');
        }
    } else {
        header('Location: /coesealy-conciliacion-cobranza/index.php?result=' . Errore::ERROR_FALTARON_PARAMETROS);
    }
} catch (Exception $e) {
    header('Location: /coesealy-conciliacion-cobranza/index.php?result=' . Errore::ERROR_INESPERADO_CATCH);
}