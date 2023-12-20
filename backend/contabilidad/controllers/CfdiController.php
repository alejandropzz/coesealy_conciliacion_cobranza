<?php

class CfdiController extends DatabaseEntity {
    private $cfdi;

    function __construct($cfdi,$year) {
        $this->cfdi = $cfdi;
        parent::__construct();
        if($year && $year!="false")
            parent::getLocalDBD_year($year);
    }

    function getIdCfdiNoLigado()
    {
        $sql =  "SELECT id FROM `cfdis` WHERE ((ligado_cobranza!=1) and (rfc_emisor='SMM950911V10' or rfc_emisor='SCM991217AU7' or rfc_emisor='SSM991217R47'));";

        $stmt = parent::getConnection()->prepare($sql);


        $response=new ResponseCfdi();

        try {




            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    {
                        $response->ids=$value;
                        $response->setStatus(0);
                        return $response;
                    }
                else
                    {
                        $response->ids=null;
                        $response->setStatus(-1);
                        return $response;
                    }
            }
            else
            {
                $response->ids=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e)
        {
            $response->ids=null;
            $response->setStatus(-1);
            return $response;
        }
    }
    function getSaldoCfdiNoLigado()
    {
        $sql =  "SELECT folio, total FROM `cfdis` WHERE ((ligado_cobranza!=1) and (rfc_emisor='SMM950911V10' or rfc_emisor='SCM991217AU7' or rfc_emisor='SSM991217R47'));";

        $stmt = parent::getConnection()->prepare($sql);


        $response=new ResponseCfdi();

        try {
            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                {
                    $response->ids=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->ids=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->ids=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e)
        {
            $response->ids=null;
            $response->setStatus(-1);
            return $response;
        }
    }
    function getIdCfdiNoLigadoCXP()
    {
        $sql =  "SELECT id FROM `cfdis` WHERE ((ligado_rp%2<>0)  and (ligado_rp%3<>0)and (rfc_receptor='SMM950911V10' or rfc_receptor='SCM991217AU7' or rfc_receptor='SSM991217R47'));";

        $stmt = parent::getConnection()->prepare($sql);


        $response=new ResponseCfdi();

        try {




            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                {
                    $response->ids=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->ids=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->ids=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e)
        {
            $response->ids=null;
            $response->setStatus(-1);
            return $response;
        }
    }
    function getCfdiById($id)
    {
        $sql =  "SELECT * FROM `cfdis` WHERE id=:id;";

        $response=new ResponseCfdi();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id);


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
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    function getCfdiByFolio($folio)
    {
        $sql =  "SELECT * FROM `cfdis` WHERE folio=:folio_cfdi;";
        $folio_cfdi="20".$folio;

        $response=new ResponseCfdi();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio_cfdi', $folio_cfdi);


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
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }

    function subirCfdi()
    {
        $logger = new Logger();
        $response=new ResponseCfdi();


        $sql = "INSERT IGNORE INTO `reporte_cobranza` ".
            "(`id`, `folio`, `voice_date`, `deposit_date`, `check_number`, `invoice_amount`, `payment_amount`, `invoice_balance`, `age_ays`, `gl_account`, `comments`, `cuenta`, `uuid`, `id_gl`, `ligado`, `erroneo`, `folio_nota_credito`, `completo`, `comentarios` ) ".
            "VALUES (NULL, :folio, :voice_date, :deposit_date, :check_number, :invoice_amount, :payment_amount, :invoice_balance, :age_ays, :gl_account, :comments, :cuenta, :uuid, :id_gl, :ligado, :erroneo, :folio_nota_credito, :completo, :comentarios);";


        try {
            $stmt = parent::getConnection()->prepare($sql);

            $reporteCobranza=$this->reporteCobranza;
            $folio=$reporteCobranza->folio;
            $voice_date=$reporteCobranza->voice_date;
            $deposit_date=$reporteCobranza->deposit_date;
            $check_number=$reporteCobranza->check_number;
            $invoice_amount=$reporteCobranza->invoice_amount;
            $payment_amount=$reporteCobranza->payment_amount;
            $invoice_balance=$reporteCobranza->invoice_balance;
            $age_ays=$reporteCobranza->age_ays;
            $gl_account=$reporteCobranza->gl_account;
            $comments=$reporteCobranza->comments;
            $cuenta=$reporteCobranza->id_cuenta;
            $uuid=$reporteCobranza->uuid;
            $id_gl=$reporteCobranza->id_gl;
            $ligado=$reporteCobranza->ligado;
            $erroneo=$reporteCobranza->erroneo;
            $folio_nota_credito=$reporteCobranza->folio_nota_credito;

            $completo=$reporteCobranza->completo;
            $comentarios=$reporteCobranza->comentarios;

            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':voice_date', $voice_date);
            $stmt->bindParam(':deposit_date', $deposit_date);
            $stmt->bindParam(':check_number', $check_number);
            $stmt->bindParam(':invoice_amount', $invoice_amount);
            $stmt->bindParam(':payment_amount', $payment_amount);
            $stmt->bindParam(':invoice_balance', $invoice_balance);
            $stmt->bindParam(':age_ays', $age_ays);
            $stmt->bindParam(':gl_account', $gl_account);
            $stmt->bindParam(':comments', $comments);
            $stmt->bindParam(':cuenta', $cuenta);
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':id_gl', $id_gl);
            $stmt->bindParam(':ligado', $ligado);
            $stmt->bindParam(':erroneo', $erroneo);
            $stmt->bindParam(':folio_nota_credito', $folio_nota_credito);

            $stmt->bindParam(':completo',$completo);
            $stmt->bindParam(':comentarios',$comentarios);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|INSERTED REPORTECOBRO|"."folio:".$folio." |INSERTION OK";
                $id=$this->getidCobro();
                $response->setId($id);
                $response->message=$message;

                $logger->logEntry("Insert Cobro.reporte_Cobranza",$message,date("Y-m-d"));

                return $response;
            }
            else
            {
                $response->setStatus(1);
                $message = "Error al insertar el cobro. Folio: ".$folio;
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
    function guardarCfdi() {
        $message = "";
        $logger = new Logger();
        $response = new ResponseCfdi();

        $sql = "INSERT IGNORE INTO `cfdis` ".
            "(`id`, `archivo_xml`, `combustible`, `complemento`, `conceptos`,".
            "`condicion_de_pago`, `descuento`, `direccion_emisor`, `estadopago`,".
            "`fecha_emision`, `fecha_timbrado`, `folio`, `formadepago`, `cfdiscol`,".
            "`ieps_160p`, `ieps_26_5p`, `ieps_3p`, `ieps_30p`, `ieps_53p`, `ieps_6p`, `ieps_7p`, `ieps_8p`, `ieps_9p`,".
            "`iva`, `localidad_emisor`, `lugardeexpedicion`, `metodo_de_pago`, `moneda`, `nombre_emisor`, `nombre_receptor`, `numctapago`,".
            "`numregidtrib`, `residenciafiscal`, `retenido_isr`, `retenido_iva`, `rfc_emisor`, `rfc_receptor`,".
            "`serie`, `subtotal`, `tipo`, `tipo_de_cambio`, `total`, `total_ieps`, `total_localretenido`,".
            "`total_localtrasladado`, `total_retenidos`, `total_trasladados`, `totaloriginal`, `usocfdi`, `uuid`,".
            "`uuid_relacion`, `version`, `tipo_coe`, `linked`, `fechapago`, `ligado_rp`,".
            "`fecha_cobro`, `id_bloque_cobranza`, `id_cobros`, `ligado_cobranza`,`year`)"
            . "VALUES"
            ."(NULL, :archivo_xml, :combustible, :complemento, :conceptos,".
            ":condicion_de_pago, :descuento, :direccion_emisor, :estadopago,".
            ":fecha_emision, :fecha_timbrado, :folio, :formadepago, :cfdiscol,".
            ":ieps_160p, :ieps_26_5p, :ieps_3p, :ieps_30p, :ieps_53p, :ieps_6p, :ieps_7p, :ieps_8p, :ieps_9p,".
            ":iva, :localidad_emisor, :lugardeexpedicion, :metodo_de_pago, :moneda, :nombre_emisor, :nombre_receptor, :numctapago,".
            ":numregidtrib, :residenciafiscal, :retenido_isr, :retenido_iva, :rfc_emisor, :rfc_receptor,".
            ":serie, :subtotal, :tipo, :tipo_de_cambio, :total, :total_ieps, :total_localretenido,".
            ":total_localtrasladado, :total_retenidos, :total_trasladados, :totaloriginal, :usocfdi, :uuid,".
            ":uuid_relacion, :version, :tipo_coe, :linked, :fechapago, :ligado_rp,".
            ":fecha_cobro, :id_bloque_cobranza, :id_cobros, :ligado_cobranza, :year);";


        try {

            $stmt = parent::getConnection()->prepare($sql);


            $cfdi_new=$this->cfdi;
            $archivo_xml=$cfdi_new->getArchivo_xml();
            $cfdiscol=$cfdi_new->getCfdiscol();
            $combustible=$cfdi_new->getCombustible();
            $complemento=$cfdi_new->getComplemento();
            $conceptos=$cfdi_new->getConceptos();
            $condicion_de_pago=$cfdi_new->getCondicion_de_pago();
            $descuento=$cfdi_new->getDescuento();
            $direccion_emisor=$cfdi_new->getDireccion_emisor();
            $estadopago=$cfdi_new->getEstadopago();
            $fecha_cobro=$cfdi_new->getFecha_cobro();
            $fecha_emision=$cfdi_new->getFecha_emision();
            $fecha_timbrano=$cfdi_new->getFecha_timbrado();
            $fechapago=$cfdi_new->getFechapago();
            $folio=$cfdi_new->getFolio();
            $formadepago=$cfdi_new->getFormadepago();
            $id_bloque_cobranza=$cfdi_new->getId_bloque_cobranza();
            $id_cobros=$cfdi_new->getId_cobros();
            $ieps_160p=$cfdi_new->getIeps_160p();
            $ieps_26_5p=$cfdi_new->getIeps_26_5p();
            $ieps_3p=$cfdi_new->getIeps_3p();
            $ieps_30p=$cfdi_new->getIeps_30p();
            $ieps_53p=$cfdi_new->getIeps_53p();
            $ieps_6p=$cfdi_new->getIeps_6p();
            $ieps_7p=$cfdi_new->getIeps_7p();
            $ieps_8p=$cfdi_new->getIeps_8p();
            $ieps_9p=$cfdi_new->getIeps_9p();
            $iva=$cfdi_new->getIva();
            $ligado_cobranza=$cfdi_new->getLigado_cobranza();
            $linked=$cfdi_new->getLinked();
            $localidad_emisor=$cfdi_new->getLocalidad_emisor();
            $lugardeexpedicion=$cfdi_new->getLugardeexpedicion();
            $metodo_de_pago=$cfdi_new->getMetodo_de_pago();
            $moneda=$cfdi_new->getMoneda();
            $nombre_emisor=$cfdi_new->getNombre_emisor();
            $nombre_receptor=$cfdi_new->getNombre_receptor();
            $numctapago=$cfdi_new->getNumctapago();
            $numregidtrib=$cfdi_new->getNumregidtrib();
            $residenciafiscal=$cfdi_new->getResidenciafiscal();
            $retenido_isr=$cfdi_new->getRetenido_isr();
            $retenido_iva=$cfdi_new->getRetenido_iva();
            $rfc_emisor=$cfdi_new->getRfc_emisor();
            $rfc_receptor=$cfdi_new->getRfc_receptor();
            $serie=$cfdi_new->getSerie();
            $subtotal=$cfdi_new->getSubtotal();
            $tipo=$cfdi_new->getTipo();
            $tipo_coe=$cfdi_new->getTipo_coe();
            $tipo_de_cambio=$cfdi_new->getTipo_de_cambio();
            $total=$cfdi_new->getTotal();
            $total_ieps=$cfdi_new->getTotal_ieps();
            $total_localretenido=$cfdi_new->getTotal_localretenido();
            $total_localtrasladado=$cfdi_new->getTotal_localtrasladado();
            $total_retenidos=$cfdi_new->getTotal_retenidos();
            $total_trasladados=$cfdi_new->getTotal_trasladados();
            $totaloriginal=$cfdi_new->getTotaloriginal();
            $usocfdi=$cfdi_new->getUsocfdi();
            $uuid=$cfdi_new->getUuid();
            $uuid_relacion=$cfdi_new->getUuid_relacion();
            $version=$cfdi_new->getVersion();

            $ligado_rp=$cfdi_new->getLigado_rp();
            $year=$cfdi_new->getYear();




            $stmt->bindParam(':archivo_xml', $archivo_xml);
            $stmt->bindParam(':cfdiscol', $cfdiscol);
            $stmt->bindParam(':combustible', $combustible);
            $stmt->bindParam(':complemento', $complemento);
            $stmt->bindParam(':conceptos', $conceptos);
            $stmt->bindParam(':condicion_de_pago', $condicion_de_pago);
            $stmt->bindParam(':descuento', $descuento);
            $stmt->bindParam(':direccion_emisor', $direccion_emisor);
            $stmt->bindParam(':estadopago', $estadopago);
            $stmt->bindParam(':fecha_cobro', $fecha_cobro);
            $stmt->bindParam(':fecha_emision', $fecha_emision);
            $stmt->bindParam(':fecha_timbrado', $fecha_timbrano);
            $stmt->bindParam(':fechapago', $fechapago);
            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':formadepago', $formadepago);
            $stmt->bindParam(':id_bloque_cobranza', $id_bloque_cobranza);
            $stmt->bindParam(':id_cobros', $id_cobros);
            $stmt->bindParam(':ieps_160p', $ieps_160p);
            $stmt->bindParam(':ieps_26_5p', $ieps_26_5p);
            $stmt->bindParam(':ieps_3p', $ieps_3p);
            $stmt->bindParam(':ieps_30p', $ieps_30p);
            $stmt->bindParam(':ieps_53p', $ieps_53p);
            $stmt->bindParam(':ieps_6p', $ieps_6p);
            $stmt->bindParam(':ieps_7p', $ieps_7p);
            $stmt->bindParam(':ieps_8p', $ieps_8p);
            $stmt->bindParam(':ieps_9p', $ieps_9p);
            $stmt->bindParam(':iva', $iva);
            $stmt->bindParam(':ligado_cobranza', $ligado_cobranza);
            $stmt->bindParam(':linked', $linked);
            $stmt->bindParam(':localidad_emisor', $localidad_emisor);
            $stmt->bindParam(':lugardeexpedicion',$lugardeexpedicion );
            $stmt->bindParam(':metodo_de_pago',$metodo_de_pago );
            $stmt->bindParam(':moneda',$moneda );
            $stmt->bindParam(':nombre_emisor',$nombre_emisor );
            $stmt->bindParam(':nombre_receptor',$nombre_receptor );
            $stmt->bindParam(':numctapago',$numctapago );
            $stmt->bindParam(':numregidtrib',$numregidtrib );
            $stmt->bindParam(':residenciafiscal',$residenciafiscal );
            $stmt->bindParam(':retenido_isr',$retenido_isr );
            $stmt->bindParam(':retenido_iva',$retenido_iva );
            $stmt->bindParam(':rfc_emisor',$rfc_emisor );
            $stmt->bindParam(':rfc_receptor',$rfc_receptor );
            $stmt->bindParam(':serie',$serie );
            $stmt->bindParam(':subtotal',$subtotal );
            $stmt->bindParam(':tipo',$tipo );
            $stmt->bindParam(':tipo_coe',$tipo_coe );
            $stmt->bindParam(':tipo_de_cambio',$tipo_de_cambio );
            $stmt->bindParam(':total',$total );
            $stmt->bindParam(':total_ieps',$total_ieps );
            $stmt->bindParam(':total_localretenido',$total_localretenido );
            $stmt->bindParam(':total_localtrasladado',$total_localtrasladado );
            $stmt->bindParam(':total_retenidos',$total_retenidos );
            $stmt->bindParam(':total_trasladados', $total_trasladados);
            $stmt->bindParam(':totaloriginal', $totaloriginal);
            $stmt->bindParam(':usocfdi', $usocfdi);
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':uuid_relacion', $uuid_relacion);
            $stmt->bindParam(':version', $version);
            $stmt->bindParam(':ligado_rp', $ligado_rp);
            $stmt->bindParam(':year', $year);




            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|INSERTED CFDI|"."UUID:".$cfdi_new->getUuid()." TIPO:".$cfdi_new->getTipo_coe()."|INSERTION OK";
                $logger->logEntry("insert_cfdis".$cfdi_new->getTipo_coe(), $message, str_replace("-","|",substr($cfdi_new->getFecha_emision(),0,7)));
                return $response;
            }


            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()).$sql);
                $message = "ERROR|FAILED TO INSERT CFDI|".$cfdi_new->getUuid()." TIPO:".$cfdi_new->getTipo_coe()."|".json_encode($stmt->errorInfo());
            }
        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
            $message = "ERROR|EXCEPTION CAUGHT|".$cfdi_new->getUuid()." TIPO:".$cfdi_new->getTipo_coe()."|".$e->getMessage();

        }
        $logger->logEntry("insert_cfdis".$cfdi_new->getTipo_coe(), $message, str_replace("-","|",substr($cfdi_new->getFecha_emision(),0,7)));




        return $response;
    }

    function getCfdi($month, $year) {

        $response = new ResponseCfdi();

        $sql="";


        $sql =  "SELECT * FROM cfdis WHERE MONTH(cfdis.fecha_emision) =:month  AND cfdis.year=:year;";

        try {

            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);


            if ($stmt->execute()) {
                $lista = [];
                while ($row = $stmt->fetch()) {

                    array_push($lista, $row);
                }
                if (sizeof($lista) > 0) {
                    $response->setStatus(0);
                    $response->setCfdis($lista);
                } else {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setCfdis(null);
                    $response->setMessage('sin resultados');
                }
            } else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setCfdis(null);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }
        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }
    function getCfdiByUuid($uuid)
    {
        $sql =  "SELECT * FROM `cfdis` WHERE uuid=:uuid;";

        $response=new ResponseCfdi();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':uuid', $uuid);


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
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    function setStatusCfdiRp($uuid, $estado)
    {
        $sql="UPDATE `cfdis` SET `ligado_rp` =`ligado_rp`*:estado WHERE `cfdis`.`uuid` = :uuid AND `ligado_rp`%:estado <> 0";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':estado', $estado);


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
    function removerStatusCfdiRp($uuid, $estado)
    {
        $sql="UPDATE `cfdis` SET `ligado_rp` =`ligado_rp`/:estado WHERE `cfdis`.`uuid` = :uuid AND `ligado_rp`%:estado = 0";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':estado', $estado);


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
    function borrarCfdi($uuid)
    {
        $sql="DELETE FROM `cfdis` WHERE uuid= :uuid";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':uuid', $uuid);


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


}

class ResponseCfdi extends ResponseStatus {

    public $ids;
    public $cfdi;
    public $cfdis;

    function setCfdis($lista) {
        $this->cfdis = $lista;
    }
}
