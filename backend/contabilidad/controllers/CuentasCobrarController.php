<?php

class CuentasCobrarController extends DatabaseEntity {

    private $cuentasCobrar;


    function __construct($cuentasCobrar, $year) {
        $this->cuentasCobrar = $cuentasCobrar;

        if(!$year)
            parent::__construct();
        else
            parent::getLocalDBD_year($year);
    }

    function getIdFacturasNoLigadas()
    {
        $sql =  "SELECT id FROM `cuentas_cobrar_facturas` WHERE completo=-1;";
        $response=new ResponseCuentasCobrar();

        try {

            $stmt = parent::getConnection()->prepare($sql);


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
                    $response->setStatus(1);
                    return $response;
                }

            }
            else
                {
                    $response->ids=null;
                    $response->setStatus(2);
                    return $response;
                }

        }
        catch (Exception $e) {
            $response->ids=null;
            $response->setStatus(3);
            return $response;
        }
    }
    function getFacturaById($id)
    {
        $sql =  "SELECT * FROM `cuentas_cobrar_facturas` WHERE id=:id;";
        $response=new ResponseCuentasCobrar();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $cuentasCobrarTemp = new CuentasCobrarFactura();
                    $cuentasCobrarTemp->parseValuesFromSQL($row);
                    array_push($value, $cuentasCobrarTemp);
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
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    function getFacturaByFolio($folio)
    {
        $sql =  "SELECT * FROM `cuentas_cobrar_facturas` WHERE number_folio=:folio;";
        $response=new ResponseCuentasCobrar();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $cuentasCobrarTemp = new CuentasCobrarFactura();
                    $cuentasCobrarTemp->parseValuesFromSQL($row);
                    array_push($value, $cuentasCobrarTemp);
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
                return false;

        }
        catch (Exception $e) {
            return false;
        }
    }
    function guardarFacturaCuentasCobrar() {
        $response = new ResponseCuentasCobrar();
        $logger = new Logger();

        $sql = 'INSERT IGNORE INTO coedb_conciliacion_cobranza.cuentas_cobrar_facturas('.

            'UUID, billing, charges, comprobacion, customer_name, customer_po,'.
            'diferencia, diff, diff_sales, discount, estado_del_sat, fecha_timbrado,'.
            'financial, freight, gross_sales, id_gl, importe_xml, in_transit, invoice, '.
            'list_sales, net_sales, number, number_folio, periodo, tax_amount, tipo, tipo_factura,'.
            'type, registro_contable, charges_type, erroneo, completo, comentarios, year, id_unico'.
            ' )VALUES('.
            ':UUID, :billing, :charges, :comprobacion, :customer_name, :customer_po,'.
            ':diferencia, :diff, :diff_sales, :discount, :estado_del_sat, :fecha_timbrado,'.
            ':financial, :freight, :gross_sales, :id_gl, :importe_xml, :in_transit, :invoice, '.
            ':list_sales, :net_sales, :number, :number_folio, :periodo, :tax_amount, :tipo, :tipo_factura,'.
            ':type, :registro_contable, :charges_type, :erroneo, :completo, :comentarios, :year, :id_unico'.
            ');';
        //TO_DATE('17/12/2015', 'DD/MM/YYYY')




        try {
            $stmt = parent::getConnection()->prepare($sql);
            $cuentasCobrar=$this->cuentasCobrar;

            $UUID=$cuentasCobrar->UUID;
            $stmt->bindParam(':UUID',$UUID);
            $billing=$cuentasCobrar->billing;
            $stmt->bindParam(':billing',$billing);
            $charges=$cuentasCobrar->charges;
            $stmt->bindParam(':charges',$charges);
            $comprobacion=$cuentasCobrar->comprobacion;
            $stmt->bindParam(':comprobacion',$comprobacion);
            $customer_name=$cuentasCobrar->customer_name;
            $stmt->bindParam(':customer_name',$customer_name);
            $customer_po=$cuentasCobrar->customer_po;
            $stmt->bindParam(':customer_po',$customer_po);
            $diferencia=$cuentasCobrar->diferencia;
            $stmt->bindParam(':diferencia',$diferencia);
            $diff=$cuentasCobrar->diff;
            $stmt->bindParam(':diff',$diff);
            $diff_sales=$cuentasCobrar->diff_sales;
            $stmt->bindParam(':diff_sales',$diff_sales);
            $discount=$cuentasCobrar->discount;
            $stmt->bindParam(':discount',$discount);
            $estado_del_sat=$cuentasCobrar->estado_del_sat;
            $stmt->bindParam(':estado_del_sat',$estado_del_sat);
            $fecha_timbrado=$cuentasCobrar->fecha_timbrado;
            $stmt->bindParam(':fecha_timbrado',$fecha_timbrado);
            $financial=$cuentasCobrar->financial;
            $stmt->bindParam(':financial',$financial);
            $freight=$cuentasCobrar->freight;
            $stmt->bindParam(':freight',$freight);
            $gross_sales=$cuentasCobrar->gross_sales;
            $stmt->bindParam(':gross_sales',$gross_sales);
            $id_gl=$cuentasCobrar->id_gl;
            $stmt->bindParam(':id_gl',$id_gl);
            $importe_xml=$cuentasCobrar->importe_xml;
            $stmt->bindParam(':importe_xml',$importe_xml);
            $in_transit=$cuentasCobrar->in_transit;
            $stmt->bindParam(':in_transit',$in_transit);
            $invoice=$cuentasCobrar->invoice;
            $stmt->bindParam(':invoice',$invoice);
            $list_sales=$cuentasCobrar->list_sales;
            $stmt->bindParam(':list_sales',$list_sales);
            $net_sales=$cuentasCobrar->net_sales;
            $stmt->bindParam(':net_sales',$net_sales);
            $number=$cuentasCobrar->number;
            $stmt->bindParam(':number',$number);
            $number_folio=$cuentasCobrar->number_folio;
            $stmt->bindParam(':number_folio',$number_folio);
            $periodo=$cuentasCobrar->periodo;
            $stmt->bindParam(':periodo',$periodo);
            $tax_amount=$cuentasCobrar->tax_amount;
            $stmt->bindParam(':tax_amount',$tax_amount);
            $tipo=$cuentasCobrar->tipo;
            $stmt->bindParam(':tipo',$tipo);
            $tipo_factura=$cuentasCobrar->tipo_factura;
            $stmt->bindParam(':tipo_factura',$tipo_factura);
            $type=$cuentasCobrar->type;
            $stmt->bindParam(':type',$type);

            $registro_contable=$cuentasCobrar->registro_contable;
            $stmt->bindParam(':registro_contable',$registro_contable);
            $charges_type=$cuentasCobrar->charges_type;
            $stmt->bindParam(':charges_type',$charges_type);
            $erroneo=$cuentasCobrar->erroneo;
            $stmt->bindParam(':erroneo',$erroneo);

            $completo=$cuentasCobrar->completo;
            $stmt->bindParam(':completo',$completo);
            $comentarios=$cuentasCobrar->comentarios;
            $stmt->bindParam(':comentarios',$comentarios);

            $year=$cuentasCobrar->year;
            $stmt->bindParam(':year',$year);

            $id_unico=$cuentasCobrar->id_unico;
            $stmt->bindParam(':id_unico',$id_unico);
            if ($stmt->execute())
            {
                $response->setStatus(0);
                $response->setMessage("INSERTADO CORRECTAMENTE");
                $response->setObject($cuentasCobrar);
                $logger->logEntry("factura_insert", "Insertado correctamente", str_replace("-","|",substr(date("Y-m-d"),0,7)));
            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()).$sql);
                $response->setObject($this->cuentasCobrar);
            }
        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH."PZZ");
            $response->setMessage($e->getMessage());
        }


        return $response;
    }




















    function guardarFacturaErroneaCXC() {
        $response = new ResponseCuentasCobrar();


        $sql = 'INSERT INTO coedb_conciliacion_cobranza.facturas_erroneas_cxc('.
            'folio, fecha '.
            ' )VALUES('.
            ':folio, :fecha'.

            ');';
        //TO_DATE('17/12/2015', 'DD/MM/YYYY')

        try {
            $stmt = parent::getConnection()->prepare($sql);
            $cuentasCobrar=$this->cuentasCobrar;


            $fecha=$cuentasCobrar->invoice;
            $stmt->bindParam(':fecha',$fecha);

            $folio=$cuentasCobrar->number_folio;
            $stmt->bindParam(':folio',$folio);



            if ($stmt->execute())
            {
                $response->setStatus(0);
                $response->setMessage("INSERTADO CORRECTAMENTE");
                $response->setObject($cuentasCobrar);
            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()).$sql);
                $response->setObject($this->cuentasCobrar);
            }
        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH."PZZ");
            $response->setMessage($e->getMessage());
        }


        return $response;
    }
    function getCFDI_iva_total($index) {

        $response = new ResponseCuentasCobrar();
           
            $sql =  "SELECT id,iva, total, subtotal, folio, rfc_emisor, moneda FROM coedb_conciliacion_cobranza.cfdis WHERE uuid=:uuid;";


            try {

                $stmt = parent::getConnection()->prepare($sql);
                $cuentasCobrar=$this->cuentasCobrar;
                $stmt->bindParam(':uuid', $cuentasCobrar->UUID);


                if ($stmt->execute()) {

                    $value = [];
                    while ($row = $stmt->fetch()) {
                        array_push($value, $row);
                    }
                    if (sizeof($value) > 0)
                    {
                        $response->setStatus(0);
                        $response->setObject(json_encode($value[0]));
                        $response->index=$index;
                    }
                    else {
                        $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                        $response->setMessage('sin resultados'.$sql);
                        $response->index=$index;
                    }
                } else {
                    $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                    $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
                }
            } catch (Exception $e) {
                $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
                $response->setMessage($e->getMessage());
            }


        return $response;
    }
    function getGL($index, $period, $folio) {

        $response = new ResponseCuentasCobrar();

        //$sql="SELECT id, description, credit_amount, debit_amount, number, type FROM `journal_entries` WHERE (description LIKE :folio2 OR description LIKE :folio) AND type!='CR' AND period=:period";
        $sql =  "SELECT id, description, credit_amount, debit_amount, number, type FROM coedb_conciliacion_cobranza.journal_entries WHERE description LIKE :folio  AND period=:period AND type!='CR';";


        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':folio', $folio);
            $stmt->bindParam(':period', $period);


            if ($stmt->execute()) {

                $value = [];
                while ($row = $stmt->fetch()) {
                    array_push($value, $row);
                }
                if (sizeof($value) > 0)
                {
                    $response->setStatus(0);
                    $response->setObject(json_encode($value));
                    $response->index=$index;
                }
                else {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados'.$sql);
                    $response->index=$index;
                }
            } else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }
        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function getCuentasCobrar($month, $year) {

        $response = new ResponseCuentasCobrar();

        $sql="";

        //if($tipo==1)
        $sql =  "SELECT * FROM coedb_conciliacion_cobranza.cuentas_cobrar_facturas WHERE MONTH(cuentas_cobrar_facturas.fecha_timbrado) =:month  AND YEAR(cuentas_cobrar_facturas.fecha_timbrado)=:year and registro_contable=0 and charges_type=0 and erroneo=0;";
        /*
        if($tipo==2)
            $sql =  "SELECT * FROM coedb_conciliacion_cobranza.cuentas_cobrar_facturas WHERE registro_contable=0 and charges_type=0 and erroneo=1;";
        if($tipo==3)
            $sql =  "SELECT * FROM coedb_conciliacion_cobranza.cuentas_cobrar_facturas WHERE registro_contable=0 and charges_type=1 and erroneo=0;";
        if($tipo==4)
            $sql =  "SELECT * FROM coedb_conciliacion_cobranza.cuentas_cobrar_facturas WHERE registro_contable=1 and charges_type=0 and erroneo=0;";
*/



        try {

            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);


            if ($stmt->execute()) {
                $listaCC = [];
                while ($row = $stmt->fetch()) {
                    $cuentasCobrarTemp = new CuentasCobrarFactura();

                    $cuentasCobrarTemp->parseValuesFromSQL($row);

                    array_push($listaCC, $cuentasCobrarTemp);
                }
                if (sizeof($listaCC) > 0) {
                    $response->setStatus(0);
                    $response->setListaCC($listaCC);
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

}

class ResponseCuentasCobrar extends ResponseStatus {
    public $obj;
    public $index;
    public $listaCC;
    public $ids;
    public $factura;

    function getListaCC() {
        return $this->listaCC;
    }

    function setListaCC($lst) {
        $this->listaCC = $lst;
    }



    function setIndex($i){
        $this->index=$i;
    }


}
