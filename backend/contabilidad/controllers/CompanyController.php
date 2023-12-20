<?php


class CompanyController extends DatabaseEntity{
    
    private $company;
    
    function __construct($company) {
        $this->company = $company;        
        parent::__construct();
    }
    
    
    function getCompanies(){
         $response = new ResponseCompanies();
        if (true) {
            $sql = "SELECT `companies`.`id`,`companies`.`name`,`companies`.`taxid`"
                    . " FROM `coedb_conciliacion_cobranza`.`companies`;";
            try {
                $stmt = parent::getConnection()->prepare($sql);
                if ($stmt->execute()) {
                    $lstCompanies = [];
                    while ($row = $stmt->fetch()) {
                        $company = new Company();
                        $company->setId($row["id"]);
                        $company->setName($row["name"]);
                        $company->setTaxid($row["taxid"]);
                        array_push($lstCompanies, $company);
                    }
                    if (sizeof($lstCompanies) > 0) {
                        $response->setStatus(0);
                        $response->setLstCompanies($lstCompanies);
                    } else {
                        $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                        $response->setLstCompanies(null);
                        $response->setMessage('sin resultados');
                    }
                } else {
                    $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                    $response->setLstCompanies(null);
                    $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
                }
            } catch (Exception $e) {
                $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
                $response->setMessage($e->getMessage());
            }
        } else {
            $response->setStatus(Errore::ERROR_DATOS_ERRONEOS);
            $response->setMessage("Offset needs to be greater or equal to 0");
        }

        return $response;
    }

    function getCompany($company){
        $response = new ResponseCompanies();
        $company = '%'.$company.'%';
        
       if (true) {
           $sql = "SELECT `companies`.`id`,`companies`.`name`,`companies`.`taxid`"
                   . " FROM `coedb_conciliacion_cobranza`.`companies` "
                   . " WHERE `name` LIKE :name;";
           try {
               $stmt = parent::getConnection()->prepare($sql);

               $stmt->bindParam(':name',$company);
               if ($stmt->execute()) {
                   $lstCompanies = [];
                   while ($row = $stmt->fetch()) {
                       $company = new Company();
                       $company->setId($row["id"]);
                       $company->setName($row["name"]);
                       $company->setTaxid($row["taxid"]);
                       array_push($lstCompanies, $company);
                   }
                   if (sizeof($lstCompanies) > 0) {
                       $response->setStatus(0);
                       $response->setLstCompanies($lstCompanies);
                   } else {
                       $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                       $response->setLstCompanies(null);
                       $response->setMessage('sin resultados');
                   }
               } else {
                   $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                   $response->setLstCompanies(null);
                   $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
               }
           } catch (Exception $e) {
               $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
               $response->setMessage($e->getMessage());
           }
       } else {
           $response->setStatus(Errore::ERROR_DATOS_ERRONEOS);
           $response->setMessage("Offset needs to be greater or equal to 0");
       }

       return $response;
   }
}

class ResponseCompanies extends ResponseStatus {

    public $lstCompanies;

    function getLstCompanies() {
        return $this->lstCompanies;
    }

    function setLstCompanies($lstCompanies) {
        $this->lstCompanies = $lstCompanies;
    }


}
