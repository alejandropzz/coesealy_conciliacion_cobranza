<?php

session_start();
require __DIR__ . '/../models/Company.php';
require __DIR__ . '/../../commun/DatabaseEntity.php';
require __DIR__ . '/../../commun/ResponseStatus.php';
require __DIR__ . '/../../commun/Errore.php';
require __DIR__ . '/../controllers/CompanyController.php';
require __DIR__ . '/../../commun/Logger.php';

$response = new ResponseCompanies();
if (isset($_SESSION["id"]) && !empty($_SESSION["id"])) {
    try {
        $company = new Company();
        $companyController = new CompanyController($company);
        $response = $companyController->getCompanies();
    } catch (Exception $e) {
        $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
        $response->setMessage('Ocurrio un eror inesperado' + $e->getMessage());
    }
} else {
    $response->setStatus(Errore::ERROR_PRIVILEGIOS);
    $response->setMessage('Permiso denegado' + $e->getMessage());
}
echo json_encode($response);

