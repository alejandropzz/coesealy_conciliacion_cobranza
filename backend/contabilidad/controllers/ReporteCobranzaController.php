<?php

class ReporteCobranzaController extends DatabaseEntity {
    private $reporteCobranza;

    function __construct($reporteCobranza, $year) {
        $this->reporteCobranza = $reporteCobranza;


        if(!$year)
            parent::__construct();
        else
            parent::getLocalDBD_year($year);


    }

    /*
     * IMPORTAR
     */
    function getIdCobranzaNoLigada()
    {
        $sql =  "SELECT id FROM `reporte_cobranza` WHERE completo=0;";
        $response=new ResponseReporteCobranza();

        try {

            $stmt = parent::getConnection()->prepare($sql);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                {
                    $response->idsCobranza=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->idsCobranza=null;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->idsCobranza=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->idsCobranza=null;
            $response->setStatus(3);
        }
    }
    function getCobranzaById($id)
    {
        $sql =  "SELECT * FROM `reporte_cobranza` WHERE id=:id;";
        $response=new ResponseReporteCobranza();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $reporteCobranzaTemp = new reporteCobranza();
                    $reporteCobranzaTemp->parseValuesFromSQL($row);
                    array_push($value, $reporteCobranzaTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->cobranza=$value[0];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->cobranza=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->cobranza=null;
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            $response->cobranza=null;
            $response->setStatus(-1);
            return $response;
        }
    }
    function getCobranzaByFolio($folio)
    {
        $sql =  "SELECT * FROM `reporte_cobranza` WHERE ABS(folio)=:folio ORDER  BY deposit_date;";
        $response=new ResponseReporteCobranza();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $reporteCobranzaTemp = new reporteCobranza();
                    $reporteCobranzaTemp->parseValuesFromSQL($row);
                    array_push($value, $reporteCobranzaTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->cobranza=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->cobranza=null;
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
    function guardarReporteCobranza()
    {
        $logger = new Logger();
        $response = new ResponseReporteCobranza();

        $sql = "INSERT IGNORE INTO `reporte_cobranza` ".
            "(`id`, `folio`, `voice_date`, `deposit_date`, `check_number`, `invoice_amount`, `payment_amount`, `invoice_balance`, `age_ays`, `gl_account`, `comments`, `cuenta`, `uuid`, `id_gl`, `ligado`, `erroneo`, `folio_nota_credito`, `completo`, `comentarios`, `group_cxc`, `year` ) ".
            "VALUES (NULL, :folio, :voice_date, :deposit_date, :check_number, :invoice_amount, :payment_amount, :invoice_balance, :age_ays, :gl_account, :comments, :cuenta, :uuid, :id_gl, :ligado, :erroneo, :folio_nota_credito, :completo, :comentarios, :group_cxc, :year);";


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
            $group_cxc=$reporteCobranza->group_cxc;
            $year=$reporteCobranza->year;

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
            $stmt->bindParam(':group_cxc',$group_cxc);
            $stmt->bindParam(':year',$year);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $message = "OK|INSERTED REPORTECOBRANZA|"."folio:".$folio." |INSERTION OK";
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

    function getCobranzaByFolioPendiente($folio, $mes)
    {
        $sql =  "SELECT * FROM `reporte_cobranza` WHERE folio=:folio and MONTH (deposit_date)<:mes ORDER  BY deposit_date;";
        $response=new ResponseReporteCobranza();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':mes', $mes);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $reporteCobranzaTemp = new reporteCobranza();
                    $reporteCobranzaTemp->parseValuesFromSQL($row);
                    array_push($value, $reporteCobranzaTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->cobranza=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->cobranza=null;
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


    /*
     * VALIDACION
     */
    function getIdGrupoCobranza()
    {
        $sql =  "SELECT DISTINCT group_cxc FROM reporte_cobranza;";
        $response=new ResponseReporteCobranza();

        try {

            $stmt = parent::getConnection()->prepare($sql);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row["group_cxc"]);

                if (sizeof($value) > 0)
                {
                    $response->idsCobranza=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->idsCobranza=null;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->idsCobranza=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->idsCobranza=null;
            $response->setStatus(3);
        }
    }
    function getGrupoCobranza($uuid)
    {
        $sql =  "SELECT * FROM `reporte_cobranza` WHERE group_cxc=:uuid ORDER BY folio;";
        $response=new ResponseReporteCobranza();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':uuid', $uuid);

            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $reporteCobranzaTemp = new reporteCobranza();
                    $reporteCobranzaTemp->parseValuesFromSQL($row);
                    array_push($value, $reporteCobranzaTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->listaRC=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->listaRC=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->listaRC=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->listaRC=null;
            $response->setStatus(3);
        }
    }















    //saldos old
/*
    function getIdSaldosNoLigados()
    {
        $sql =  "SELECT id FROM `saldos`;";
        $response=new ResponseReporteCobranza();

        try {

            $stmt = parent::getConnection()->prepare($sql);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                {
                    $response->idsSaldos=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->idsSaldos=null;
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
            {
                $response->idsSaldos=null;
                $response->setStatus(2);
            }

        }
        catch (Exception $e) {
            $response->idsSaldos=null;
            $response->setStatus(3);
        }
    }
    function getSaldoById($id)
    {
        $sql =  "SELECT * FROM `saldos` WHERE id=:id;";
        $response=new ResponseReporteCobranza();

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
                    $response->cobranza=null;
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
*/















    function validarRegistroNonar()
    {

        $response = new ResponseReporteCobranza();
        try
        {
            //$VNPA=$this->validarNonarPorAutorizacion();
            $VNPA=true;
            //$VN_GL=$this->validarNonarGL();
            $VN_GL=true;

            if($VN_GL!=false)
            {
                $response->setGL($VN_GL);

                if ($VNPA != false)
                {
                    $response->setStatus(0);
                    $response->setEstadoCuenta($VNPA);
                }
                else
                {
                    $VNPFE = $this->validarNonarPorFechaExacta();
                    if ($VNPFE != false) {
                        $response->setStatus(0);
                        $response->setEstadoCuenta($VNPFE);

                    } else {
                        $VNPF = $this->validarNonarPorFecha123();
                        if ($VNPF != false) {
                            $response->setStatus(0);
                            $response->setEstadoCuenta($VNPF);

                        } else {
                            $response->setStatus(1);
                        }
                    }
                }
            }
            else
            {
                $response->setStatus(1);
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }

        return $response;
    }
    function validarRegistroNonarGL()
    {
        $response = new ResponseReporteCobranza();
        try {
            //$VN_GL=$this->validarNonarGL();
            $VN_GL=true;
            if($VN_GL!=false)
            {
                $response->setGL($VN_GL);
                $response->setStatus(0);
            }
            else
            {
                $response->setStatus(1);
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }
        return $response;
    }
    private function validarNonarPorFecha123()
    {
        $sql =  "SELECT * FROM `edc_banco` WHERE id_cuenta=:id_cuenta AND (depositos=:payment_amount OR retiros=:payment_amount) AND (MONTH(fecha)=:deposit_date1 OR MONTH(fecha)=:deposit_date2 OR MONTH(fecha)=:deposit_date3);";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;
            $id_cuenta=$reporteCobranza->id_cuenta;
            $payment_amount=abs($reporteCobranza->payment_amount);
            $periodo1=$reporteCobranza->periodo-1;
            $periodo2=$reporteCobranza->periodo;
            $periodo3=$reporteCobranza->periodo+1;

            $stmt->bindParam(':id_cuenta', $id_cuenta);
            $stmt->bindParam(':payment_amount', $payment_amount);
            $stmt->bindParam(':deposit_date1', $periodo1);
            $stmt->bindParam(':deposit_date2', $periodo2);
            $stmt->bindParam(':deposit_date3', $periodo3);

            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value[0];
                else
                    return false;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }

    }
    private function validarNonarPorFechaExacta()
    {
        $sql =  "SELECT * FROM `edc_banco` WHERE id_cuenta=:id_cuenta AND (depositos=:payment_amount OR retiros=:payment_amount) AND fecha=:deposit_date;";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;

            $id_cuenta=$reporteCobranza->id_cuenta;
            $payment_amount=abs($reporteCobranza->payment_amount);
            $deposit_date=$reporteCobranza->deposit_date;
            $stmt->bindParam(':id_cuenta', $id_cuenta);
            $stmt->bindParam(':payment_amount', $payment_amount);
            $stmt->bindParam(':deposit_date', $deposit_date);

            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value[0];
                else
                    return false;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    private function validarNonarPorAutorizacion()
    {
        $sql =  "SELECT * FROM `edc_banco` WHERE id_cuenta=:id_cuenta AND (depositos=:payment_amount OR retiros=:payment_amount) AND description LIKE =:autorizacion;";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;

            $id_cuenta=$reporteCobranza->id_cuenta;
            $payment_amount=abs($reporteCobranza->payment_amount);
            $autorizacion=$reporteCobranza->autorizacion;

            $stmt->bindParam(':id_cuenta', $id_cuenta);
            $stmt->bindParam(':payment_amount', $payment_amount);
            $stmt->bindParam(':autorizacion', $autorizacion);

            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value[0];
                else
                    return false;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    private function validarNonarGL()
    {

        //se valida solo con debit?
        //$sql =  "SELECT * FROM `journal_entries` WHERE  (debit_amount=:payment_amount OR credit_amount=:payment_amount);";
        $sql =  "SELECT * FROM `journal_entries` WHERE  (debit_amount=:payment_amount );";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;
            $payment_amount=abs($reporteCobranza->payment_amount);

            $stmt->bindParam(':payment_amount', $payment_amount);




            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value;
                else
                    return false;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }

    function getCFDI_Saldo_GL()
    {
        $response = new ResponseReporteCobranza();
        try
        {

            $CFDI=$this->getCFDI();
            $saldo=$this->getSaldo();
            $factura=$this->getFactura();

            //$GL=$this->getGLCompuesto();
            $GL=true;
            if($GL==false)
                $GL=$this->getGL();





            $response->setCFDI($CFDI);
            $response->setGL($GL);
            $response->setSaldo($saldo);
            $response->setFactura($factura);
            $response->setStatus(0);


        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }
        return $response;
    }

    private function getCFDI()
    {
        $sql =  "SELECT * FROM `cfdis` WHERE folio=:folio;";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;
            $folio="20".$reporteCobranza->folio;

            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value[0];
                else
                    return false;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    private function getSaldo()
    {
        $sql =  "SELECT * FROM `saldos` WHERE folio=:folio;";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;
            $folio=$reporteCobranza->folio;

            $stmt->bindParam(':folio', $folio);

            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value;
                else
                    return null;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    private function getGL()
    {
        $sql =  "SELECT * FROM `journal_entries` WHERE description like :folio AND debit_amount=:debit AND  credit_amount=:credit;";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;
            $folio="%".$reporteCobranza->folio."%";
            $debit=0;
            $credit=0;

            if($reporteCobranza->invoice_amount<0)
                $debit=abs($reporteCobranza->payment_amount);
            else
                $credit=$reporteCobranza->payment_amount;


            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':credit', $credit);
            $stmt->bindParam(':debit', $debit);

            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value;
                else
                    return false;
                    //return $sql."___".$folio."___".$credit."______".$debit;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    private function getGLCompuesto()
    {
        $sql =  "SELECT * FROM `journal_entries` WHERE description like :folio;";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;
            $folio="%".$reporteCobranza->folio."%";

            $payment_amount=$reporteCobranza->payment_amount;

            $stmt->bindParam(':folio', $folio);
            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);
                if (sizeof($value) > 0)
                {
                    $i=0;

                    $ventas_credit=0;
                    $iva_credit=0;
                    $clientes_credit=0;
                    $descuentos_credit=0;

                    $ventas_debit=0;
                    $iva_debit=0;
                    $clientes_debit=0;
                    $descuentos_debit=0;


                    //suma de importes por cuenta
                    for ($i=0;$i<sizeof($value);$i++)
                    {
                        //ventas
                        if($value[$i]["account"]=="1120-1020-1000-1001-0001")
                        {
                            $ventas_credit=$value[$i]["credit_amount"];
                            $ventas_debit=$value[$i]["debit_amount"];
                        }
                        //descuentos
                        if($value[$i]["account"]=="1120-1000-1000-0111-0000")
                        {
                            $descuentos_credit=$value[$i]["credit_amount"];
                            $descuentos_debit=$value[$i]["debit_amount"];

                        }
                        //clientes
                        if($value[$i]["account"]=="1120-1000-1000-0100-0000")
                        {
                            $clientes_credit=$value[$i]["credit_amount"];
                            $clientes_debit=$value[$i]["debit_amount"];
                        }
                        //iva
                        if($value[$i]["account"]=="1120-1000-1000-0660-2750")
                        {
                            $iva_credit=$value[$i]["credit_amount"];
                            $iva_debit=$value[$i]["debit_amount"];
                        }

                    }

                    $bandera=true;

                    if($payment_amount<0)
                    {
                        if($ventas_credit+$iva_credit+$clientes_credit-$descuentos_credit!=abs($payment_amount))
                            $bandera=false;
                        if($clientes_debit-$ventas_debit-$iva_debit!=0)
                            $bandera=false;
                    }
                    else
                    {
                        if($clientes_debit-$descuentos_debit-$ventas_debit-$iva_debit!=$payment_amount)
                            $bandera=false;
                        if($ventas_credit+$iva_credit-$clientes_credit!=0)
                            $bandera=false;
                    }


                    if($bandera==false)
                        return false;
                    else
                        return $value;

                }
                else
                    return false;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }

    private function getidCobro()
    {
        $sql =  "SELECT id FROM `reporte_cobranza` WHERE folio=:folio AND payment_amount=:payment_amount AND deposit_date=:deposit_date;";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;
            $folio=$reporteCobranza->folio;
            $payment_amount=$reporteCobranza->payment_amount;
            $deposit_date=$reporteCobranza->deposit_date;

            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':payment_amount', $payment_amount);
            $stmt->bindParam(':deposit_date', $deposit_date);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value[0]["id"];
                else
                    return $sql.$folio.$payment_amount.$deposit_date;
            }
            else
                return $sql.$folio.$payment_amount.$deposit_date;;

        }
        catch (Exception $e) {
            return false;
        }
    }
    private function getFactura()
    {
        $sql =  "SELECT * FROM `cuentas_cobrar_facturas` WHERE number_folio=:folio;";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;
            $folio=$reporteCobranza->folio;

            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value[0];
                else
                    return false;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    function subirCobro()
    {
        $logger = new Logger();
        $response = new ResponseReporteCobranza();

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

    function getReporteCobranza($month, $year, $tipo) {

        $response = new ResponseReporteCobranza();

        $sql =  "SELECT * FROM reporte_cobranza WHERE MONTH(reporte_cobranza.deposit_date) =:month  AND YEAR(reporte_cobranza.deposit_date)=:year;";
        if($tipo==1)
            $sql =  "SELECT * FROM reporte_cobranza WHERE MONTH(reporte_cobranza.deposit_date) =:month  AND YEAR(reporte_cobranza.deposit_date)=:year and  erroneo=0;";
        if($tipo==2)
            $sql =  "SELECT * FROM reporte_cobranza WHERE MONTH(reporte_cobranza.deposit_date) =:month  AND YEAR(reporte_cobranza.deposit_date)=:year and  erroneo=1;";

        try {

            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);


            if ($stmt->execute()) {
                $listaRC = [];
                while ($row = $stmt->fetch()) {
                    $reporteCobranzaTemp = new reporteCobranza();

                    $reporteCobranzaTemp->parseValuesFromSQL($row);
                    array_push($listaRC, $reporteCobranzaTemp);
                }
                if (sizeof($listaRC) > 0) {
                    $response->setStatus(0);
                    $response->setListaRC($listaRC);
                } else {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setListaCC(null);
                    $response->setMessage('sin resultados');
                }
            } else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setListaCC(null);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }
        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function existReporteCobranza($uuid) {

        $response = new ResponseReporteCobranza();

        $sql =  "SELECT * FROM reporte_cobranza WHERE uuid=:uuid;";

        try {

            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':uuid', $uuid);


            if ($stmt->execute()) {
                $listaRC = [];
                while ($row = $stmt->fetch()) {
                    $reporteCobranzaTemp = new reporteCobranza();

                    $reporteCobranzaTemp->parseValuesFromSQL($row);
                    array_push($listaRC, $reporteCobranzaTemp);
                }
                if (sizeof($listaRC) > 0) {
                    $response->setStatus(0);
                    $response->setListaRC($listaRC);
                } else {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setListaCC(null);
                    $response->setMessage('sin resultados');
                }
            } else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setListaCC(null);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }
        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }
    function registroLigado($uuid) {

        $response = new ResponseReporteCobranza();

        $sql =  "SELECT * FROM reporte_cobranza WHERE uuid=:uuid and ligado=0;";

        try {

            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':uuid', $uuid);


            if ($stmt->execute()) {
                $listaRC = [];
                while ($row = $stmt->fetch())
                {
                    $reporteCobranzaTemp = new reporteCobranza();

                    $reporteCobranzaTemp->parseValuesFromSQL($row);
                    array_push($listaRC, $reporteCobranzaTemp);
                }
                if (sizeof($listaRC) > 0)
                {
                    $response->setStatus(0);
                    $response->setListaRC($listaRC);
                    $this->setLigado($uuid);
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setListaCC(null);
                    $response->setMessage('sin resultados');
                }
            } else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setListaCC(null);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }
        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    private function setLigado($uuid)
    {

        $sql="UPDATE `reporte_cobranza` SET `ligado` = '1' WHERE `reporte_cobranza`.`uuid` = :uuid";

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
    private function validarCFDI($CFDI)
    {
        $sql =  "SELECT * FROM `cfdis` WHERE folio=:folio;";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $reporteCobranza=$this->reporteCobranza;
            $folio="20".$reporteCobranza->folio;

            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                $value = [];
                while ($row = $stmt->fetch())
                    array_push($value, $row);

                if (sizeof($value) > 0)
                    return $value[0];
                else
                    return false;
            }
            else
                return false;

        }
        catch (Exception $e) {
            return false;
        }









    }

    function setFacturaCompleta($folio)
    {

        $sql="UPDATE `cuentas_cobrar_facturas` SET `completo` = '1' WHERE `cuentas_cobrar_facturas`.`number_folio` = :folio";

        try {
            $stmt = parent::getConnection()->prepare($sql);
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
    function setCobrosCompletos($ids)
    {

        $sql="UPDATE `reporte_cobranza` SET `completo` = '1' WHERE `reporte_cobranza`.`id` in ".$ids;

        try {
            $stmt = parent::getConnection()->prepare($sql);

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

    function setErrorImporteFactura($folio)
    {

        $comentario="Hubo un error al sumar el importe de los saldos y el monto del cfdi. ";
        $sql="UPDATE `cuentas_cobrar_facturas` SET `erroneo` = '1',`comentarios` =:comentario  WHERE `cuentas_cobrar_facturas`.`number_folio` = :folio";

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':comentario', $comentario);


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
    function setErrorImporteCobro($ids)
    {

        $comentario="Hubo un error al sumar el importe de los saldos y el monto del cfdi. ";
        $sql="UPDATE `reporte_cobranza` SET `erroneo` = '1',`comentarios` =:comentario WHERE `reporte_cobranza`.`id` in ".$ids;

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':comentario', $comentario);

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

class ResponseReporteCobranza extends ResponseStatus {
    public $obj;
    public $index;
    public $id;
    public $listaCC;
    public $listaRC;
    public $estado_cuenta=null;
    public $GL=null;
    public $CFDI=[];
    public $saldo=[];
    public $factura=[];
    public $cadena="";
    public $idsCobranza;
    public $idsSaldos;
    public $cobranza;


    function getListaCC() {
        return $this->listaCC;
    }

    function setListaCC($lst) {
        $this->listaCC = $lst;
    }
    function setListaRC($lst) {
        $this->listaRC = $lst;
    }

    function setEstadoCuenta($obj)
    {
        $this->estado_cuenta=$obj;
    }

    function setGL($obj)
    {
        $this->GL=$obj;
    }
    function setCFDI($obj)
    {
        $this->CFDI=$obj;
    }
    function setSaldo($obj)
    {
        $this->saldo=$obj;
    }
    function setFactura($obj)
    {
        $this->factura=$obj;
    }

    function setIndex($i){
        $this->index=$i;
    }
    function setId($i){
        $this->id=$i;
    }
    function setCadena($c){
        $this->cadena=$c;
    }


    function getIdSaldos()
    {
        return $this->idsSaldos;
    }
    function getIdCobranza()
    {
        return $this->idsCobranza;
    }


}
