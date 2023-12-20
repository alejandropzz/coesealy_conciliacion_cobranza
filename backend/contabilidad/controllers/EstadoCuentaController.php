<?php

class EstadoCuentaController extends DatabaseEntity {

    private $estadoCuenta;


    function __construct($estadoCuenta, $year) {
        $this->estadoCuenta = $estadoCuenta;
        parent::__construct();
        if($year)
            parent::getLocalDBD_year($year);
    }


    function guardarEstadoCuenta() {

        $response = new ResponseStatus();
        
        $sql = "INSERT IGNORE INTO `edc_banco`(`id_edc`, `id_cuenta`,`fecha`,`descripcion`, `depositos`,`retiros`,`saldo`, `id_compa`, `year`, `uuid`)".
                "VALUES".
                " (  :id_edc, :id_cuenta, :fecha, :descripcion, :depositos, :retiros, :saldo, :id_compa, :year, :uuid);";
        //TO_DATE('17/12/2015', 'DD/MM/YYYY')
        try {

            $estadoCuentaLocal=$this->estadoCuenta;

            $idedc=null;
            $idCuenta= $estadoCuentaLocal->getIdCuenta();
            $fecha= $estadoCuentaLocal->getFecha();
            $descripcion= $estadoCuentaLocal->getDescripcion();
            $depositos= $estadoCuentaLocal->getDepositos();
            $retiros= $estadoCuentaLocal->getRetiros();
            $saldo= $estadoCuentaLocal->getSaldo();
            $id_compa=$estadoCuentaLocal->getIdCompa();
            $year=$estadoCuentaLocal->getYear();
            $uuid=$estadoCuentaLocal->getUuid();

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id_edc', $idedc);
            $stmt->bindParam(':id_cuenta', $idCuenta);
            $stmt->bindParam(':fecha',$fecha );
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':depositos', $depositos);
            $stmt->bindParam(':retiros', $retiros);
            $stmt->bindParam(':saldo', $saldo);
            $stmt->bindParam(':id_compa', $id_compa);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':uuid', $uuid);



            if ($stmt->execute()) {
                $response->setStatus(0);
            } else {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()));
            }
        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }
        return $response;
    }
    function getEstadoCuenta($month, $year, $idCuenta) {

        $response = new ResponseEstadoCuenta();
           
            $sql =  "SELECT id_cuenta, id_compa, fecha, descripcion, depositos, retiros, saldo FROM edc_banco WHERE MONTH(edc_banco.fecha) =:month  AND YEAR(edc_banco.fecha)=:year AND id_cuenta=:idCuenta;";

            try {

                $stmt = parent::getConnection()->prepare($sql);

                $stmt->bindParam(':month', $month);
                $stmt->bindParam(':year', $year);
                $stmt->bindParam(':idCuenta', $idCuenta);


                if ($stmt->execute()) {
                    $listaEC = [];
                    while ($row = $stmt->fetch()) {
                        $estadoCuenta = new EstadoCuenta();
                        $estadoCuenta->setIdCuenta($row["id_cuenta"]);
                        $estadoCuenta->setIdCompa($row["id_compa"]);
                        $estadoCuenta->setFecha($row["fecha"]);
                        $estadoCuenta->setDescripcion($row["descripcion"]);
                        $estadoCuenta->setDepositos($row["depositos"]);
                        $estadoCuenta->setRetiros($row["retiros"]);
                        $estadoCuenta->setSaldo($row["saldo"]);

                        array_push($listaEC, $estadoCuenta);
                    }
                    if (sizeof($listaEC) > 0) {
                        $response->setStatus(0);
                        $response->setListaEC($listaEC);
                    } else {
                        $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                        $response->setListaEC(null);
                        $response->setMessage('sin resultados');
                    }
                } else {
                    $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                    $response->setListaEC(null);
                    $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
                }
            } catch (Exception $e) {
                $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
                $response->setMessage($e->getMessage());
            }
        

        return $response;
    }
    function existEstadoCuenta($month, $year, $id_cuenta) {
        $response = new ResponseStatus();
        $sql = "SELECT `id_cuenta` FROM `coedb_2019`.`edc_banco` WHERE MONTH(`edc_banco`.`fecha`) =:month AND YEAR(`edc_banco`.`fecha`)=:year AND `id_cuenta`=:id_cuenta;";
        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':id_cuenta', $id_cuenta);

            if ($stmt->execute())
            {
                $bandera = true;
                while ($row = $stmt->fetch() && $bandera)
                    $bandera=false;

                if ($bandera)
                    $response->setStatus(0);

                else
                    $response->setStatus(Errore::WARN_YA_EXISTE_UN_ESTADO_DE_CUENTA_CON_ES_FECHA);

            }
            else
            {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
            }
        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function getIdEstadoCuenta()
    {
        $sql =  "SELECT id_edc FROM `edc_banco`;";
        $response=new ResponseEstadoCuenta();

        try {

            $stmt = parent::getConnection()->prepare($sql);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["id_edc"]);

                if (sizeof($value) > 0)
                {
                    $response->ids=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->ids=null;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->ids=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->ids=null;
            $response->setStatus(3);
        }
        return $response;
    }
    function getEdcById($id)
    {
        $sql =  "SELECT * FROM `edc_banco` WHERE id_edc=:id;";
        $response=new ResponseEstadoCuenta();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    array_push($value, $row);
                }

                if (sizeof($value) > 0)
                {

                    $response->edc=$value[0];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->edc=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
        return $response;
    }

}

class ResponseEstadoCuenta extends ResponseStatus {

    public $listaEC;
    public $ids;
    public $status;
    public $edc;

    function getListaEC() {
        return $this->listaEC;
    }

    function setListaEC($lstAccounts) {
        $this->listaEC = $lstAccounts;
    }
    function setStatus($status) {
        $this->status = $status;
    }

}
