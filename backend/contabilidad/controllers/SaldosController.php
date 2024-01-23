<?php

class SaldosController extends DatabaseEntity {
    private $saldos;

    function __construct($saldos,$year) {
        $this->saldos = $saldos;
        if(!$year || $year=="false")
            parent::__construct();
        else
            parent::getLocalDBD_year($year);
    }
    function getIdSaldosNoLigados()
    {
        $sql =  "SELECT id FROM `saldos` WHERE (estatus<0 OR estatus%5=0) and estatus%23!=0 and estatus%11!=0;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["id"]);

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
    }
    function getSaldoById($id)
    {
        $sql =  "SELECT * FROM `saldos` WHERE id=:id;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $saldosTemp = new saldos();
                    $saldosTemp->parseValuesFromSQL($row);
                    array_push($value, $saldosTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->saldo=$value[0];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->saldo=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->saldo=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            {
                $response->saldo=null;
                $response->setStatus(-1);
                return $response;
            }
        }
    }
    function getSaldoByFolio($folio)
    {
        $sql =  "SELECT * FROM `saldos` WHERE folio=$folio;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            //$stmt->bindParam(':id', $id);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $saldosTemp = new saldos();
                    $saldosTemp->parseValuesFromSQL($row);
                    array_push($value, $saldosTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->saldo=$value[0];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->saldo=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->saldo=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            {
                $response->saldo=null;
                $response->setStatus(-1);
                return $response;
            }
        }
    }

    function guardarSaldo()
    {
        $logger = new Logger();
        $response = new ResponseSaldos();

        $sql = "INSERT IGNORE INTO `saldos` ".
            "(`id`, `folio`, `actual`, `total`, `id_factura`, `id_cfdi`, `id_cobranza`, `fecha_emision`, `fecha_cierre`, `ultimo_cobro`, `year`,  `estatus`, `id_unico`) ".
            "VALUES (NULL, :folio, :actual, :total, :id_factura, :id_cfdi, :id_cobranza, :fecha_emision, :fecha_cierre, :ultimo_cobro, :year, :estatus, :id_unico);";


        try {
            $stmt = parent::getConnection()->prepare($sql);

            $saldos=$this->saldos;

            $folio=$saldos->folio;
            $actual=$saldos->actual;
            $total=$saldos->total;
            $id_factura=$saldos->id_factura;
            $id_cfdi=$saldos->id_cfdi;
            $id_cobranza=$saldos->id_cobranza;
            $fecha_emision=$saldos->fecha_emision;
            $fecha_cierre=$saldos->fecha_cierre;
            $ultimo_cobro=$saldos->ultimo_cobro;
            $year=$saldos->year;
            $estatus=$saldos->estatus;
            $id_unico=$saldos->id_unico;


            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':actual', $actual);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':id_factura', $id_factura);
            $stmt->bindParam(':id_cfdi', $id_cfdi);
            $stmt->bindParam(':id_cobranza', $id_cobranza);
            $stmt->bindParam(':fecha_emision',$fecha_emision);
            $stmt->bindParam(':fecha_cierre',$fecha_cierre);
            $stmt->bindParam(':ultimo_cobro',$ultimo_cobro);
            $stmt->bindParam(':year',$year);
            $stmt->bindParam(':estatus',$estatus);
            $stmt->bindParam(':id_unico',$id_unico);



            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|INSERTED SALDO|"."folio:".$folio." |INSERTION OK";
                $response->message=$message;
                $logger->logEntry("Insert Cobro.reporte_Cobranza",$message,date("Y-m-d"));

                return $response;
            }
            else
            {
                $response->setStatus(1);
                $message = "Error al insertar el saldo. Folio: ".$folio;
                $response->message=$message;

                return $response;
            }

        }
        catch (Exception $e) {
            $response->setStatus(1);
            $message = "Error inesperado al insertar el cobro. Folio: ".$folio;
            $response->message=$message;

            return $response;
        }


        return $response;
    }

    function getAllFolios()
    {
        $sql =  "SELECT DISTINCT folio, id_cfdi FROM saldos WHERE id_cfdi!=-1;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                {
                    $temp["folio"]=$row["folio"];
                    $temp["cfdi"]=$row["id_cfdi"];
                    array_push($value, $temp);
                }

                if (sizeof($value) > 0)
                {
                    $response->folios=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->folio=null;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->folio=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->folio=null;
            $response->setStatus(3);
        }
    }
    function getFoliosPendiente($fecha)
    {
        $sql =  "SELECT DISTINCT folio, id_cfdi FROM saldos WHERE id_cfdi!=-1 AND fecha_emision<=:fecha AND (fecha_cierre>:fecha OR fecha_cierre='0001-01-01')  ;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':fecha', $fecha);

            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                {
                    $temp["folio"]=$row["folio"];
                    $temp["cfdi"]=$row["id_cfdi"];
                    array_push($value, $temp);
                }

                if (sizeof($value) > 0)
                {
                    $response->folios=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->folios=null;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->folios=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->folio=null;
            $response->setStatus(3);
        }
    }
    function getfoliosIncompletos()
    {
        $sql =  "SELECT DISTINCT folio FROM saldos WHERE (((estatus%2!=0 AND estatus%13!=0) OR (estatus%3!=0 AND estatus%17!=0) OR (estatus%5=0 OR estatus%19=0)) AND (estatus%31!=0 AND estatus%37!=0 AND estatus%23!=0)) ORDER BY id;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["folio"]);

                if (sizeof($value) > 0)
                {
                    $response->folios=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->folio=null;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->folio=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->folio=null;
            $response->setStatus(3);
        }
    }
    function getGrupoFolio($folio)
    {
        $sql =  "SELECT * FROM `saldos` WHERE folio=:folio ORDER BY year;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);

            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $saldosTemp = new saldos();
                    $saldosTemp->parseValuesFromSQL($row);
                    array_push($value, $saldosTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->grupoSaldos=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->grupoSaldos=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->grupoSaldos=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->grupoSaldos=null;
            $response->setStatus(3);
        }
    }
    function getGrupoFolioPendiente($folio, $year)
    {
        $sql =  "SELECT * FROM `saldos` WHERE folio=:folio and year<=:year ORDER BY year;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':year', $year);

            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $saldosTemp = new saldos();
                    $saldosTemp->parseValuesFromSQL($row);
                    array_push($value, $saldosTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->grupoSaldos=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->grupoSaldos=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->grupoSaldos=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->grupoSaldos=null;
            $response->setStatus(3);
        }
    }

    function updateIdFactura($folio, $id_factura, $fecha_emision)
    {

        $sql = "UPDATE `saldos` SET `id_factura` =:id_factura, `fecha_emision`=:fecha_emision WHERE `saldos`.`folio` =:folio;";
        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id_factura', $id_factura);
            $stmt->bindParam(':fecha_emision', $fecha_emision);
            $stmt->bindParam(':folio', $folio);

            if ($stmt->execute())
                return true;

            else
                return false;
        }
        catch (Exception $e)
        {
            return false;
        }

    }
    function updateIdCfdi($folio, $id_cfdi, $total, $fecha_emision)
    {

        $sql = "UPDATE `saldos` SET `id_cfdi` =:id_cfdi, `total` =:total, `fecha_emision`=:fecha_emision WHERE `saldos`.`folio` =:folio;";
        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id_cfdi', $id_cfdi);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':fecha_emision', $fecha_emision);
            $stmt->bindParam(':folio', $folio);

            if ($stmt->execute())
                return true;

            else
                return false;
        }
        catch (Exception $e) {
            {
                return false;
            }
        }
    }

    function getCfdiByFolio($folio)
    {
        $sql =  "SELECT * FROM `cfdis` WHERE folio=:folio;";

        $response=new ResponseSaldos();
        try {

            $stmt = parent::getConnection()->prepare($sql);
            $folioCfdi="20".$folio;
            $stmt->bindParam(':folio', $folioCfdi);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                {
                    $response->cfdi=$value[0];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->cfdi=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->cfdi=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            $response->cfdi=null;
            $response->setStatus(-1);
            return $response;
        }
    }
    function getFacturaByFolio($folio)
    {
        $sql =  "SELECT * FROM `cuentas_cobrar_facturas` WHERE number_folio=:folio;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    array_push($value, $row);
                }

                if (sizeof($value) > 0)
                {

                    $response->factura=$value[0];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->factura=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->factura=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            $response->factura=null;
            $response->setStatus(-1);
            return $response;
        }
    }

    function deleteCfdiByFolio($folio)
    {
        $sql =  "DELETE FROM `cfdis` WHERE folio=:folio AND year_rp <> -1;";

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $folioCfdi="20".$folio;
            $stmt->bindParam(':folio', $folioCfdi);


            if ($stmt->execute())
            {
                return true;
            }
            else
            {
                return false;
            }

        }
        catch (Exception $e) {
            return false;
        }
    }
    function deleteFacturaByFolio($folio)
    {
        $sql =  "DELETE FROM `cuentas_cobrar_facturas` WHERE number_folio=:folio;";

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                return true;
            }
            else
            {
                return false;
            }

        }
        catch (Exception $e) {
            return false;
        }
    }

    function getYearCfdi($folio)
    {
        $sql =  "SELECT DISTINCT id_cfdi FROM `saldos` WHERE folio=:folio;";

        $response=new ResponseSaldos();
        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                {
                    $response->cfdi=$value[0]["id_cfdi"];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->cfdi=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->cfdi=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            $response->cfdi=null;
            $response->setStatus(-1);
            return $response;
        }
    }
    function getYearFactura($folio)
    {
        $sql =  "SELECT DISTINCT id_factura FROM `saldos` WHERE folio=:folio;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    array_push($value, $row);
                }

                if (sizeof($value) > 0)
                {

                    $response->factura=$value[0]["id_factura"];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->factura=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->factura=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            $response->factura=null;
            $response->setStatus(-1);
            return $response;
        }
    }

    function setStatusFactura($folio, $estado)
    {

        $sql="UPDATE `cuentas_cobrar_facturas` SET `completo` =:estado WHERE `cuentas_cobrar_facturas`.`number_folio` = :folio";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                return true;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }

    }
    function setStatusCfdi($folio, $estado)
    {
        $sql="UPDATE `cfdis` SET `ligado_cobranza` =:estado WHERE `cfdis`.`folio` = :folio";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':estado', $estado);
            $folio_cfdi="20".$folio;
            $stmt->bindParam(':folio', $folio_cfdi);


            if ($stmt->execute())
            {
                return true;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }

    }



    function getStatusSaldo($folio)
    {
        $sql =  "SELECT estatus FROM `saldos` WHERE folio=:folio;";

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':folio', $folio);

            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return (int)$value[0]["estatus"];
                else
                    return -1;
            }
            else
                return -1;

        }
        catch (Exception $e) {
            return -1;
        }
    }
    function setFechaCierre($folio, $fecha)
    {
        if($fecha=="0001-01-01")
            return true;


        $sql="UPDATE `saldos` SET `fecha_cierre` =:fecha WHERE folio = :folio";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                return true;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }

    }
    function setStatusSaldo($folio, $estado)
    {

        $sql="UPDATE `saldos` SET `estatus` =`saldos`.`estatus`*:estado WHERE folio = :folio";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                return true;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }

    }
    function crearEstadoSaldo($estado, $estados)
    {
        for($i=0;$i<sizeof($estados);$i++)
        {
            if($estados[$i]==13)
                if($estado%13!=0)
                    $estado=$estado*13;

            if($estados[$i]==17)
                if($estado%17!=0)
                    $estado=$estado*17;

            if($estados[$i]==19)
                if($estado%19!=0)
                    $estado=$estado*19;

            if($estados[$i]==23)
            {
                if($estado%19==0)
                    $estado=$estado/19;
                if($estado%23!=0)
                    $estado=$estado*23;
            }

            if($estados[$i]==29)
            {
                if($estado%19==0)
                    $estado=$estado/19;
                if($estado%23==0)
                    $estado=$estado/23;
                if($estado%29!=0)
                    $estado=$estado*29;
            }

            if($estados[$i]==31)
                if($estado%31!=0)
                    $estado=$estado*31;

            if($estados[$i]==37)
                if($estado%37!=0)
                    $estado=$estado*37;
        }
        return $estado;
    }

    function getSumYear($folio, $year)
    {
        $sql =  "SELECT SUM(actual) FROM `saldos` WHERE folio=:folio AND year<:year;";
        $response=new ResponseSaldos();

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':year', $year);

            if ($stmt->execute())
            {


                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["SUM(actual)"]);

                if (sizeof($value) > 0)
                {
                    if($value[0]!=null)
                        $response->suma=floatval($value[0]);
                    else
                        $response->suma=0;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->suma=0;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->suma=0;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->suma=0;
            $response->setStatus(3);
        }
    }
    function getSumMes($folio, $mes)
    {
        $sql =  "SELECT SUM(Abs(payment_amount)) FROM `reporte_cobranza` WHERE folio=:folio AND MONTH(deposit_date)<=:mes;";
        $response=new ResponseSaldos();

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':mes', $mes);

            if ($stmt->execute())
            {


                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["SUM(Abs(payment_amount))"]);

                if (sizeof($value) > 0)
                {

                    if($value[0]!=null)
                        $response->suma=floatval($value[0]);
                    else
                        $response->suma=0;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->suma=0;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->suma=0;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->suma=0;
            $response->setStatus(3);
        }
    }
    function getTotalSaldo($folio)
    {
        $sql =  "SELECT DISTINCT total FROM `saldos` WHERE folio=:folio;";
        $response=new ResponseSaldos();

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':folio', $folio);

            if ($stmt->execute())
            {


                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["total"]);

                if (sizeof($value) > 0)
                {
                    if(floatval($value[0])!=-1)
                    {
                        $response->total=floatval($value[0]);
                        $response->setStatus(0);
                    }
                    else
                    {
                        $response->total=null;
                        $response->setStatus(1);
                    }
                    return $response;
                }
                else
                {
                    $response->total=null;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->total=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->total=null;
            $response->setStatus(3);
        }
    }



    function getSumYearLocal($folio, $year)
    {
        $sql =  "SELECT SUM(actual) FROM `saldos` WHERE folio=:folio AND year<:year;";

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':year', $year);

            if ($stmt->execute())
            {


                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["SUM(actual)"]);

                if (sizeof($value) > 0)
                {
                    $suma=0;
                    if($value[0]!=null)
                        $suma=floatval($value[0]);

                    return $suma;
                }
                else
                {
                    return 0;
                }

            }
            else
            {
                return 0;
            }

        }
        catch (Exception $e) {
            return(0);
        }
    }
    function getSumMesLocal($folio, $mes)
    {
        $sql =  "SELECT SUM(payment_amount) FROM `reporte_cobranza` WHERE folio=:folio AND MONTH(deposit_date)<:mes;";
        $response=new ResponseSaldos();

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':mes', $mes);

            if ($stmt->execute())
            {


                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["SUM(payment_amount)"]);

                if (sizeof($value) > 0)
                {
                    $suma=0;
                    if($value[0]!=null)
                        $suma=floatval($value[0]);

                    return $suma;
                }
                else
                {
                    return 0;
                }

            }
            else
            {
                return 0;
            }

        }
        catch (Exception $e) {
            return 0;
        }
    }
    function getTotalSaldoLocal($folio)
    {
        $sql =  "SELECT DISTINCT total FROM `saldos` WHERE folio=:folio;";
        $response=new ResponseSaldos();

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':folio', $folio);

            if ($stmt->execute())
            {


                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["total"]);

                if (sizeof($value) > 0)
                {
                    if(floatval($value[0])!=-1)
                        return floatval($value[0]);
                    else
                        return null;
                }
                else
                {
                    return null;
                }

            }
            else
            {
                return(null);
            }

        }
        catch (Exception $e) {
            return(null);
        }
    }

    function crearSaldo()
    {
        $logger = new Logger();
        $response = new ResponseSaldos();
        $sql = "INSERT IGNORE INTO `saldos` ".
            "(`id`, `folio`, `actual`, `total`, `id_cobranza`) ".
            "VALUES (NULL, :folio, :actual, :total, :id_cobranza);";

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $saldos=$this->saldos;

            $folio=$saldos->folio;
            $actual=$saldos->actual;
            $total=$saldos->total;
            $id_cobranza=$saldos->id_cobranza;


            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':actual', $actual);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':id_cobranza', $id_cobranza);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|INSERTED SALDO|"."folio:".$folio." |INSERTION OK";
                $response->message=$message;
                $logger->logEntry("Insert saldo.SaldosController",$message,date("Y-m-d"));

                return $response;
            }
            else
            {
                $response->setStatus(1);
                $message = "Error al insertar el saldo. Folio: ".$folio;
                $response->message=$message;

                return $response;
            }

        }
        catch (Exception $e) {
            $response->setStatus(1);
            $message = "Error inesperado al insertar el saldo. Folio: ".$folio;
            $response->message=$message;

            return $response;
        }


        return $response;
    }
    function actualizarSaldo()
    {
        $logger = new Logger();
        $response = new ResponseSaldos();
        $sql = "UPDATE `saldos` SET `actual` =:actual , `id_cobranza` = :id_cobranza WHERE `saldos`.`id` =:id;";

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $saldos=$this->saldos;
            $folio=$saldos->folio;
            $id=$saldos->id;
            $actual=$saldos->actual;
            $id_cobranza=$saldos->id_cobranza;


            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':actual', $actual);
            $stmt->bindParam(':id_cobranza', $id_cobranza);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|UPDATED SALDO|"."folio:".$folio." |INSERTION OK";
                $response->message=$message;
                $logger->logEntry("Update saldo.SaldosController",$message,date("Y-m-d"));

                return $response;
            }
            else
            {
                $response->setStatus(1);
                $message = "Error al actualizar el saldo. Folio: ".$folio;
                $response->message=$message;

                return $response;
            }

        }
        catch (Exception $e) {
            $response->setStatus(1);
            $message = "Error inesperado al actualizar el saldo. Folio: ".$folio;
            $response->message=$message;

            return $response;
        }


        return $response;
    }
    function borrarSaldo()
    {
        $logger = new Logger();
        $response = new ResponseSaldos();
        $sql = "DELETE FROM saldos WHERE id=:id";

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $saldos=$this->saldos;

            $folio=$saldos->folio;
            $id=$saldos->id;

            $stmt->bindParam(':id', $id);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|DELETE SALDO|"."folio:".$folio." |INSERTION OK";
                $response->message=$message;
                $logger->logEntry("Delete saldo.SaldosController",$message,date("Y-m-d"));

                return $response;
            }
            else
            {
                $response->setStatus(1);
                $message = "Error al borrar el saldo. Folio: ".$folio;
                $response->message=$message;

                return $response;
            }

        }
        catch (Exception $e) {
            $response->setStatus(1);
            $message = "Error inesperado al borrar el saldo. Folio: ".$folio;
            $response->message=$message;

            return $response;
        }


        return $response;
    }
    function ligarCFDI()
    {
        $logger = new Logger();
        $response = new ResponseSaldos();
        $sql = "UPDATE `cfdis` SET `fecha_cobro` =:fecha_cobro , `id_bloque_cobranza` = :id_bloque_cobranza, `id_cobros` = :id_cobros WHERE `cfdis`.`id` =:id_cfdi;";

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $saldos=$this->saldos;

            $fecha_cobro=$saldos->fecha_cobro;
            $id_bloque_cobranza=$saldos->id_bloque_cobranza;
            $id_cobros=$saldos->id_cobros;
            $id_cfdi=$saldos->id_cfdi;


            $stmt->bindParam(':fecha_cobro', $fecha_cobro);
            $stmt->bindParam(':id_bloque_cobranza', $id_bloque_cobranza);
            $stmt->bindParam(':id_cobros', $id_cobros);
            $stmt->bindParam(':id_cfdi', $id_cfdi);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|UPDATED CFDI|"."id:".$id_cfdi." |INSERTION OK";
                $response->message=$message;
                $logger->logEntry("Update cfdi.CFDIController",$message,date("Y-m-d"));

                return $response;
            }
            else
            {
                $response->setStatus(1);
                $message = "Error al actualizar el cfdi. id: ".$id_cfdi;
                //$response->cfdi=$saldos;
                $response->message=$message;

                return $response;
            }

        }
        catch (Exception $e) {
            $response->setStatus(1);
            $message = "Error inesperado al actualizar el cfdi. id: ".$id_cfdi;
            $response->message=$message;

            return $response;
        }


        return $response;
    }

    //FOR THE CXP VERSION
    function getIdSaldosNoLigadosRP()
    {
        $sql =  "SELECT uuid FROM `saldos_rp` WHERE (estatus%2!=0 OR estatus%3=0 OR estatus%5=0) && estatus%7 <> 0;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["uuid"]);

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
    }
    function getSaldoByIdRP($id)
    {
        $sql =  "SELECT * FROM `saldos_rp` WHERE id=:id;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $saldosTemp = new saldos();
                    $saldosTemp->parseValuesFromSQLRP($row);
                    array_push($value, $saldosTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->saldo=$value[0];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->saldo=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->saldo=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            {
                $response->saldo=null;
                $response->setStatus(-1);
                return $response;
            }
        }
    }
    function getSaldoByUuidRP($uuid)
    {
        $sql =  "SELECT * FROM `saldos_rp` WHERE uuid=:uuid;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':uuid', $uuid);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $saldosTemp = new saldos();
                    $saldosTemp->parseValuesFromSQLRP($row);
                    array_push($value, $saldosTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->saldo=$value[0];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->saldo=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->saldo=null;
                $response->setStatus(-2);
                return $response;
            }

        }
        catch (Exception $e) {
            {
                $response->saldo=null;
                $response->setStatus(-3);
                return $response;
            }
        }
    }
    function guardarSaldoRP()
    {
        $logger = new Logger();
        $response = new ResponseSaldos();

        $sql = "INSERT IGNORE INTO `saldos_rp` ".
            "(`id`, `uuid`, `actual`, `total`, `year_cfdi`, `id_rp`, `fecha_emision`, `fecha_cierre`, `ultimo_pago`, `year`,  `estatus`,`uuid_year` ) ".
            "VALUES (NULL, :uuid, :actual, :total, :year_cfdi, :id_rp, :fecha_emision, :fecha_cierre, :ultimo_pago, :year, :estatus, :uuid_year);";

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $saldos=$this->saldos;

            $uuid=$saldos->uuid;
            $actual=$saldos->actual;
            $total=$saldos->total;
            $year_cfdi=$saldos->year_cfdi;
            //ID RP ES UN ARRAY
            $id_rp=$saldos->id_rp;
            $fecha_emision=$saldos->fecha_emision;
            $fecha_cierre=$saldos->fecha_cierre;
            $ultimo_pago=$saldos->ultimo_pago;
            $year=$saldos->year;
            $estatus=$saldos->estatus;
            $uuid_year=$uuid."_".$year;

            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':actual', $actual);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':year_cfdi', $year_cfdi);
            $stmt->bindParam(':id_rp', $id_rp);
            $stmt->bindParam(':fecha_emision',$fecha_emision);
            $stmt->bindParam(':fecha_cierre',$fecha_cierre);
            $stmt->bindParam(':ultimo_pago',$ultimo_pago);
            $stmt->bindParam(':year',$year);
            $stmt->bindParam(':estatus',$estatus);
            $stmt->bindParam(':uuid_year', $uuid_year);



            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|INSERTED SALDO rp|"."uuid:".$uuid." |INSERTION OK";
                $response->message=$message;
                $logger->logEntry("Insert Saldo rp",$message,date("Y-m-d"));

                return $response;
            }
            else
            {
                $response->setStatus(1);
                $message = "Error al insertar el saldo.rp";
                $response->message=$message;

                return $response;
            }

        }
        catch (Exception $e) {
            $response->setStatus(-1);
            $response->setObject($e);
            $message = "Error inesperado al insertar el saldo.rp uuid: ".$uuid;
            $response->message=$message;

            return $response;
        }


        return $response;
    }

    function getGrupoUUID($uuid)
    {
        $sql =  "SELECT * FROM `saldos_rp` WHERE uuid=:uuid ORDER BY year;";
        $response=new ResponseSaldos();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':uuid', $uuid);

            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $saldosTemp = new saldos();
                    $saldosTemp->parseValuesFromSQLRP($row);
                    array_push($value, $saldosTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->grupoSaldos=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->grupoSaldos=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->grupoSaldos=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->grupoSaldos=null;
            $response->setStatus(3);
        }
    }

    function agregarStatusSaldoRp($uuid, $status)
    {

        $sql="UPDATE `saldos_rp` SET `estatus` =`saldos_rp`.`estatus`*:estado WHERE uuid = :uuid AND `saldos_rp`.`estatus`%:estado<>0";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':estado', $status);
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':estado', $status);


            if ($stmt->execute())
            {
                return true;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }

    }
    function agregarStatusSaldoRpUuidYear($uuid_year, $status)
    {

        $sql="UPDATE `saldos_rp` SET `estatus` =`saldos_rp`.`estatus`*:estado WHERE uuid_year = :uuid_year AND `saldos_rp`.`estatus`%:estado<>0";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':estado', $status);
            $stmt->bindParam(':uuid_year', $uuid_year);
            $stmt->bindParam(':estado', $status);


            if ($stmt->execute())
            {
                return true;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }

    }
    function removerStatusSaldoRp($id, $status)
    {

        $sql="UPDATE `saldos_rp` SET `estatus` =`saldos_rp`.`estatus`/:estado WHERE id = :id AND `saldos_rp`.`estatus%:estado=0`";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':estado', $status);
            $stmt->bindParam(':folio', $id);
            $stmt->bindParam(':estado', $status);


            if ($stmt->execute())
            {
                return true;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }

    function nuevoSaldoRpAnual($saldo)
    {
        $uuid=$saldo->uuid;
        $actual=$saldo->actual;
        $total=$saldo->total;
        $year_cfdi=$saldo->year_cfdi;
        $id_rp=$saldo->id_rp;
        $fecha_emision=$saldo->fecha_emision;
        $fecha_cierre=$saldo->fecha_cierre;
        $ultimo_pago=$saldo->ultimo_pago;
        $status=$saldo->status;


        $logger = new Logger();
        $response = new ResponseSaldos();
        $sql = "INSERT IGNORE INTO `saldos_rp` ".
            "(`id`, `uuid`, `actual`, `total`, `year_cfdi`, `id_rp`, `fecha_emision`, `fecha_cierre`, `ultimo_pago`, `estatus` ) ".
            "VALUES  (NULL, :uuid , :actual,  :total,  :year_cfdi,  :id_rp,  :fecha_emision,  :fecha_cierre,  :ultimo_pago, :status);";

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':actual', $actual);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':year_cfdi', $year_cfdi);
            $json_array_id_pago=json_encode($id_rp);
            $stmt->bindParam(':id_rp', $json_array_id_pago);
            $stmt->bindParam(':fecha_emision', $fecha_emision);
            $stmt->bindParam(':fecha_cierre', $fecha_cierre);
            $stmt->bindParam(':ultimo_pago', $ultimo_pago);
            $stmt->bindParam(':status', $status);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|INSERTED SALDO RP|"."uuid:".$uuid." |INSERTION OK";
                $response->message=$message;
                $logger->logEntry("Insert saldo.SaldosController",$message,date("Y-m-d"));

                return $response;
            }
            else
            {
                $response->setStatus(1);
                $message = "Error al insertar el saldoRp. UUID: ".$uuid;
                $response->message=$message;

                return $response;
            }

        }
        catch (Exception $e) {
            $response->setStatus(1);
            $message = "Error inesperado al insertar el saldoRP. UUID: ".$uuid;
            $response->message=$message;

            return $response;
        }


        return $response;
    }
    function nuevoSaldoRp($saldo)
    {
        $uuid=$saldo->uuid;
        $actual=$saldo->actual;
        $total=$saldo->total;
        $year_cfdi=$saldo->year_cfdi;
        $id_rp=$saldo->id_rp;
        $fecha_emision=$saldo->fecha_emision;
        $fecha_cierre=$saldo->fecha_cierre;
        $ultimo_pago=$saldo->ultimo_pago;
        $status=$saldo->status;
        $year=$saldo->year;
        $uuid_year=$saldo->uuid_year;


        $logger = new Logger();
        $response = new ResponseSaldos();
        $sql = "INSERT IGNORE INTO `saldos_rp` ".
            "(`id`, `uuid`, `actual`, `total`, `year_cfdi`, `id_rp`, `fecha_emision`, `fecha_cierre`, `ultimo_pago`, `estatus`, `year`, `uuid_year` ) ".
            "VALUES  (NULL, :uuid , :actual,  :total,  :year_cfdi,  :id_rp,  :fecha_emision,  :fecha_cierre,  :ultimo_pago, :status, :year, :uuid_year);";

        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':actual', $actual);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':year_cfdi', $year_cfdi);
            $json_array_id_pago=json_encode($id_rp);
            $stmt->bindParam(':id_rp', $json_array_id_pago);
            $stmt->bindParam(':fecha_emision', $fecha_emision);
            $stmt->bindParam(':fecha_cierre', $fecha_cierre);
            $stmt->bindParam(':ultimo_pago', $ultimo_pago);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':uuid_year', $uuid_year);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|INSERTED SALDO RP|"."uuid:".$uuid." |INSERTION OK";
                $response->message=$message;
                $logger->logEntry("Insert saldo.SaldosController",$message,date("Y-m-d"));

                return $response;
            }
            else
            {
                $response->setStatus(1);
                $message = "Error al insertar el saldoRp. UUID: ".$uuid;
                $response->message=$message;

                return $response;
            }

        }
        catch (Exception $e) {
            $response->setStatus(1);
            $message = "Error inesperado al insertar el saldoRP. UUID: ".$uuid;
            $response->message=$message;

            return $response;
        }


        return $response;
    }

}

class ResponseSaldos extends ResponseStatus {
    public $obj;
    public $saldo;
    public $ids;
    public $folios=[];
    public $grupoSaldos=[];
    public $cfdi;
    public $factura;
    public $suma;
    public $total;
    public $folio;

}
