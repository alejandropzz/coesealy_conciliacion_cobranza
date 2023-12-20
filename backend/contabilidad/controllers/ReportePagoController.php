<?php


class ReportePagoController extends DatabaseEntity
{


    private $reportePago;


    function __construct($reportePago, $year)
    {
        $this->reportePago = $reportePago;

        if(!$year || $year=="false")
            parent::__construct();
        else
            parent::getLocalDBD_year($year);



    }

    function getIdCFDI()
    {
        $response = new ResponseReportePago();

        $sql = "SELECT id FROM coedb_2019.cfdis WHERE   uuid=:uuid  AND ((total+100 > :importe_factura AND total-100 < :importe_factura) OR (-total+100 > :importe_factura AND -total-100 < :importe_factura));";
        try {



            $stmt = parent::getConnection()->prepare($sql);

            $uuid=$this->reportePago->uuid_factura;
            $importe_factura=$this->reportePago->importe_de_factura;


            $stmt->bindParam(':uuid', $uuid);
            $stmt->bindParam(':importe_factura', $importe_factura);

            if ($stmt->execute()) {

                $id=-1;
                while($row=$stmt->fetch())
                {

                    $id=$row["id"];
                }

                if($id!=-1)
                {
                    $response->setStatus(0);
                    $response->setIdCFDI($id);
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setIdCFDI($id);
                    $response->setMessage('sin resultados');
                }

            }

            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setIdCFDI(-1);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }








        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function guardarReportePago() {
        $response = new ResponseStatus();

        $sql = "INSERT INTO coedb_2019.reporte_pago(".

            "agente_aduanal, amex_raul_rivera, anticipos, banco_656_bnmx, base_de_iva, cargos_aeroportuarios,".
            "comentarios_a_la_excepcion, complemento_de_pago, comprobacion, comprobacion_1, comprobacion_2, comprobacion_3,".
            "comprobacion_4, comprobacion_5, concepto_pago_cxp, deducibles_de_autos, depositos_y_retiros_cta_tempur, descuento, ".
            "devoluciones, diferencia_en_pago, empleado_servicios_reembolsos, empresa, excento_de_iva, excepcion, fecha_de_pago, ".
            "fecha_liga, fondo_ahorro, forma_de_pago, id_cfdi, year_cfdi, id_cuenta, id_rp, importe_de_factura, importe_de_pago, importe_factura, ".
            "impuestos_derechos, intercam, iva_16, iva_factura, iva_tasa_0, metodo_de_pago, no_deducibles, no_proveedor, ".
            "nombre_del_proveedor, nomina, numero_de_factura, observaciones, otras_cuentas_interco, pago_total_de_proveedor, ".
            "proveedor, proveedores_extranjeros, push_money, retencion_2_3_partes_iva_arend, retencion_2_3_partes_iva_honorarios, ".
            "retencion_4_transporte, retencion_6, retencion_10_isr_arrend, retencion_10_isr_honorarios, retencion_isr, retencion_iva, ".
            "rfc, servicios_esp_telmex, tipo_de_proveedor, total, total_por_factura, totales, traspasos, uuid_factura, venta_outlet,".
            "number_gl, type_gl, date_gl, linked, status".

            " )VALUES(".

            ":agente_aduanal, :amex_raul_rivera, :anticipos, :banco_656_bnmx, :base_de_iva, :cargos_aeroportuarios,".
            ":comentarios_a_la_excepcion, :complemento_de_pago, :comprobacion, :comprobacion_1, :comprobacion_2, :comprobacion_3,".
            ":comprobacion_4, :comprobacion_5, :concepto_pago_cxp, :deducibles_de_autos, :depositos_y_retiros_cta_tempur, :descuento,".
            ":devoluciones, :diferencia_en_pago, :empleado_servicios_reembolsos, :empresa, :excento_de_iva, :excepcion, :fecha_de_pago,".
            ":fecha_liga, :fondo_ahorro, :forma_de_pago, :id_CFDI, :year_CFDI, :id_cuenta, :id_RP, :importe_de_factura, :importe_de_pago, :importe_factura,".
            ":impuestos_derechos, :intercam, :iva_16, :iva_factura, :iva_tasa_0, :metodo_de_pago, :no_deducibles, :no_proveedor,".
            ":nombre_del_proveedor, :nomina, :numero_de_factura, :observaciones, :otras_cuentas_interco, :pago_total_de_proveedor,".
            ":proveedor, :proveedores_extranjeros, :push_money, :retencion_2_3_partes_iva_arend, :retencion_2_3_partes_iva_honorarios,".
            ":retencion_4_transporte, :retencion_6, :retencion_10_isr_arrend, :retencion_10_isr_honorarios, :retencion_isr, :retencion_iva,".
            ":rfc, :servicios_esp_telmex, :tipo_de_proveedor, :total, :total_por_factura, :totales, :traspasos, :uuid_factura, :venta_outlet,".
            ":number_gl, :type_gl, :date_gl, :linked, :status".

            ");";
        //TO_DATE('17/12/2015', 'DD/MM/YYYY')
        try {

            $stmt = parent::getConnection()->prepare($sql);


            $agente_aduanal=$this->reportePago->agente_aduanal;
            $stmt->bindParam(':agente_aduanal',$agente_aduanal);

            $amex_raul_rivera=$this->reportePago->amex_raul_rivera;
            $stmt->bindParam(':amex_raul_rivera',$amex_raul_rivera);

            $anticipos=$this->reportePago->anticipos;
            $stmt->bindParam(':anticipos',$anticipos);

            $banco_656_bnmx=$this->reportePago->banco_656_bnmx;
            $stmt->bindParam(':banco_656_bnmx',$banco_656_bnmx);

            $base_de_iva=$this->reportePago->base_de_iva;
            $stmt->bindParam(':base_de_iva',$base_de_iva);

            $cargos_aeroportuarios=$this->reportePago->cargos_aeroportuarios;
            $stmt->bindParam(':cargos_aeroportuarios',$cargos_aeroportuarios);

            $comentarios_a_la_excepcion=$this->reportePago->comentarios_a_la_excepcion;
            $stmt->bindParam(':comentarios_a_la_excepcion',$comentarios_a_la_excepcion);

            $complemento_de_pago=$this->reportePago->complemento_de_pago;
            $stmt->bindParam(':complemento_de_pago',$complemento_de_pago);

            $comprobacion=$this->reportePago->comprobacion;
            $stmt->bindParam(':comprobacion',$comprobacion);

            $comprobacion_1=$this->reportePago->comprobacion_1;
            $stmt->bindParam(':comprobacion_1',$comprobacion_1);

            $comprobacion_2=$this->reportePago->comprobacion_2;
            $stmt->bindParam(':comprobacion_2',$comprobacion_2);

            $comprobacion_3=$this->reportePago->comprobacion_3;
            $stmt->bindParam(':comprobacion_3',$comprobacion_3);

            $comprobacion_4=$this->reportePago->comprobacion_4;
            $stmt->bindParam(':comprobacion_4',$comprobacion_4);

            $comprobacion_5=$this->reportePago->comprobacion_5;
            $stmt->bindParam(':comprobacion_5',$comprobacion_5);

            $concepto_pago_cxp=$this->reportePago->concepto_pago_cxp;
            $stmt->bindParam(':concepto_pago_cxp',$concepto_pago_cxp);

            $deducibles_de_autos=$this->reportePago->deducibles_de_autos;
            $stmt->bindParam(':deducibles_de_autos',$deducibles_de_autos);

            $depositos_y_retiros_cta_tempur=$this->reportePago->depositos_y_retiros_cta_tempur;
            $stmt->bindParam(':depositos_y_retiros_cta_tempur',$depositos_y_retiros_cta_tempur);

            $descuento=$this->reportePago->descuento;
            $stmt->bindParam(':descuento',$descuento);

            $devoluciones=$this->reportePago->devoluciones;
            $stmt->bindParam(':devoluciones',$devoluciones);

            $diferencia_en_pago=$this->reportePago->diferencia_en_pago;
            $stmt->bindParam(':diferencia_en_pago',$diferencia_en_pago);

            $empleado_servicios_reembolsos=$this->reportePago->empleado_servicios_reembolsos;
            $stmt->bindParam(':empleado_servicios_reembolsos',$empleado_servicios_reembolsos);

            $empresa=$this->reportePago->empresa;
            $stmt->bindParam(':empresa',$empresa);

            $excento_de_iva=$this->reportePago->excento_de_iva;
            $stmt->bindParam(':excento_de_iva',$excento_de_iva);

            $excepcion=$this->reportePago->excepcion;
            $stmt->bindParam(':excepcion',$excepcion);

            $fecha_de_pago=$this->reportePago->fecha_de_pago;
            $stmt->bindParam(':fecha_de_pago',$fecha_de_pago);

            $fecha_liga=$this->reportePago->fecha_liga;
            $stmt->bindParam(':fecha_liga',$fecha_liga);

            $fondo_ahorro=$this->reportePago->fondo_ahorro;
            $stmt->bindParam(':fondo_ahorro',$fondo_ahorro);

            $forma_de_pago=$this->reportePago->forma_de_pago;
            $stmt->bindParam(':forma_de_pago',$forma_de_pago);

            $id_CFDI=$this->reportePago->id_CFDI;
            $stmt->bindParam(':id_CFDI',$id_CFDI);

            $year_CFDI=$this->reportePago->year_CFDI;
            $stmt->bindParam(':year_CFDI',$year_CFDI);

            $id_cuenta=$this->reportePago->id_cuenta;
            $stmt->bindParam(':id_cuenta',$id_cuenta);

            $id_RP=$this->reportePago->id_RP;
            $stmt->bindParam(':id_RP',$id_RP);

            $importe_de_factura=$this->reportePago->importe_de_factura;
            $stmt->bindParam(':importe_de_factura',$importe_de_factura);

            $importe_de_pago=$this->reportePago->importe_de_pago;
            $stmt->bindParam(':importe_de_pago',$importe_de_pago);

            $importe_factura=$this->reportePago->importe_factura;
            $stmt->bindParam(':importe_factura',$importe_factura);

            $impuestos_derechos=$this->reportePago->impuestos_derechos;
            $stmt->bindParam(':impuestos_derechos',$impuestos_derechos);

            $intercam=$this->reportePago->intercam;
            $stmt->bindParam(':intercam',$intercam);

            $iva_16=$this->reportePago->iva_16;
            $stmt->bindParam(':iva_16',$iva_16);

            $iva_factura=$this->reportePago->iva_factura;
            $stmt->bindParam(':iva_factura',$iva_factura);

            $iva_tasa_0=$this->reportePago->iva_tasa_0;
            $stmt->bindParam(':iva_tasa_0',$iva_tasa_0);

            $metodo_de_pago=$this->reportePago->metodo_de_pago;
            $stmt->bindParam(':metodo_de_pago',$metodo_de_pago);

            $no_deducibles=$this->reportePago->no_deducibles;
            $stmt->bindParam(':no_deducibles',$no_deducibles);

            $no_proveedor=$this->reportePago->no_proveedor;
            $stmt->bindParam(':no_proveedor',$no_proveedor);

            $nombre_del_proveedor=$this->reportePago->nombre_del_proveedor;
            $stmt->bindParam(':nombre_del_proveedor',$nombre_del_proveedor);

            $nomina=$this->reportePago->nomina;
            $stmt->bindParam(':nomina',$nomina);

            $numero_de_factura=$this->reportePago->numero_de_factura;
            $stmt->bindParam(':numero_de_factura',$numero_de_factura);

            $observaciones=$this->reportePago->observaciones;
            $stmt->bindParam(':observaciones',$observaciones);

            $otras_cuentas_interco=$this->reportePago->otras_cuentas_interco;
            $stmt->bindParam(':otras_cuentas_interco',$otras_cuentas_interco);

            $pago_total_de_proveedor=$this->reportePago->pago_total_de_proveedor;
            $stmt->bindParam(':pago_total_de_proveedor',$pago_total_de_proveedor);

            $proveedor=$this->reportePago->proveedor;
            $stmt->bindParam(':proveedor',$proveedor);

            $proveedores_extranjeros=$this->reportePago->proveedores_extranjeros;
            $stmt->bindParam(':proveedores_extranjeros',$proveedores_extranjeros);

            $push_money=$this->reportePago->push_money;
            $stmt->bindParam(':push_money',$push_money);

            $retencion_2_3_partes_iva_arend=$this->reportePago->retencion_2_3_partes_iva_arend;
            $stmt->bindParam(':retencion_2_3_partes_iva_arend',$retencion_2_3_partes_iva_arend);

            $retencion_2_3_partes_iva_honorarios=$this->reportePago->retencion_2_3_partes_iva_honorarios;
            $stmt->bindParam(':retencion_2_3_partes_iva_honorarios',$retencion_2_3_partes_iva_honorarios);

            $retencion_4_transporte=$this->reportePago->retencion_4_transporte;
            $stmt->bindParam(':retencion_4_transporte',$retencion_4_transporte);

            $retencion_6=$this->reportePago->retencion_6;
            $stmt->bindParam(':retencion_6',$retencion_6);

            $retencion_10_isr_arrend=$this->reportePago->retencion_10_isr_arrend;
            $stmt->bindParam(':retencion_10_isr_arrend',$retencion_10_isr_arrend);

            $retencion_10_isr_honorarios=$this->reportePago->retencion_10_isr_honorarios;
            $stmt->bindParam(':retencion_10_isr_honorarios',$retencion_10_isr_honorarios);

            $retencion_isr=$this->reportePago->retencion_isr;
            $stmt->bindParam(':retencion_isr',$retencion_isr);

            $retencion_iva=$this->reportePago->retencion_iva;
            $stmt->bindParam(':retencion_iva',$retencion_iva);

            $rfc=$this->reportePago->rfc;
            $stmt->bindParam(':rfc',$rfc);

            $servicios_esp_telmex=$this->reportePago->servicios_esp_telmex;
            $stmt->bindParam(':servicios_esp_telmex',$servicios_esp_telmex);

            $tipo_de_proveedor=$this->reportePago->tipo_de_proveedor;
            $stmt->bindParam(':tipo_de_proveedor',$tipo_de_proveedor);

            $total=$this->reportePago->total;
            $stmt->bindParam(':total',$total);

            $total_por_factura=$this->reportePago->total_por_factura;
            $stmt->bindParam(':total_por_factura',$total_por_factura);

            $totales=$this->reportePago->totales;
            $stmt->bindParam(':totales',$totales);

            $traspasos=$this->reportePago->traspasos;
            $stmt->bindParam(':traspasos',$traspasos);

            $uuid_factura=$this->reportePago->uuid_factura;
            $stmt->bindParam(':uuid_factura',$uuid_factura);

            $venta_outlet=$this->reportePago->venta_outlet;
            $stmt->bindParam(':venta_outlet',$venta_outlet);

            $number_gl=-1;
            $stmt->bindParam(':number_gl',$number_gl);

            $type_gl=-1;
            $stmt->bindParam(':type_gl',$type_gl);

            $date_gl="0000-00-00";
            $stmt->bindParam(':date_gl',$date_gl);

            $linked=-1;
            $stmt->bindParam(':linked',$linked);


            $status=$this->reportePago->status;
            $stmt->bindParam(':status',$status);

//            $response->setMessage($sql);


            if ($stmt->execute())
            {
                $response->setStatus(0);
                $response->setMessage("INSERTADO CORRECTAMENTE");
            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()));
            }


        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }
        return $response;
    }

    function getIdReportePago()
    {
        $response = new ResponseReportePago();

        $sql = "SELECT id_rp FROM coedb_2019.reporte_pago WHERE   uuid_factura=:uuid_factura AND rfc=:rfc AND importe_de_factura=:importe_de_factura;";
        try {



            $stmt = parent::getConnection()->prepare($sql);

            $reporteP=$this->reportePago;

            $uuid_factura=$reporteP->uuid_factura;
            $rfc=$reporteP->rfc;
            $importe_de_factura=$reporteP->importe_de_factura;


            $stmt->bindParam(':uuid_factura', $uuid_factura);
            $stmt->bindParam(':rfc', $rfc);
            $stmt->bindParam(':importe_de_factura', $importe_de_factura);

            if ($stmt->execute()) {

                $id=-1;
                while($row=$stmt->fetch())
                {

                    $id=$row["id_rp"];
                }

                if($id!=-1)
                {
                    $response->setStatus(0);
                    $response->setIdReportePago($id);
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setIdReportePago($id);
                    $response->setMessage('sin resultados');
                }

            }

            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setIdReportePago(-1);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }








        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function getCFDIsSugerencia($index)
    {
        $response = new ResponseReportePago();

        $in = '('. implode(',', $index).')';
        $sql = "SELECT cfdis.id, cfdis.fecha_emision, cfdis.rfc_emisor, cfdis.total, cfdis.uuid FROM coedb_2019.cfdis 
                WHERE cfdis.year_rp=-1 AND cfdis.uuid IN". $in ."ORDER BY cfdis.total";


        try {



            $stmt = parent::getConnection()->prepare($sql);


            if ($stmt->execute()) {

                $listaCFDI = [];
                while ($row = $stmt->fetch())
                {
                    array_push($listaCFDI, $row);
                }
                if(sizeof($listaCFDI) > 0)
                {
                    $response->setStatus(0);
                    $response->setListaCFDI($listaCFDI);
                    $response->setMessage("CONSULTA REALIZADA CON EXITO");
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados');

                }
            }
            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function getCFDIsSugerenciaImporte($index)
    {
        $response = new ResponseReportePago();
        $in = '('. implode(',', $index).')';
        $sql = "SELECT cfdis.id, cfdis.fecha_emision, cfdis.rfc_emisor, cfdis.total, cfdis.uuid 
FROM coedb_2019.cfdis JOIN coedb_2019.reporte_pago ON cfdis.year_rp=-1 AND cfdis.total>=(reporte_pago.importe_de_factura-100) AND cfdis.total<=(reporte_pago.importe_de_factura+100) WHERE reporte_pago.id_rp IN". $in."ORDER BY cfdis.total";

        try {



            $stmt = parent::getConnection()->prepare($sql);

            if ($stmt->execute()) {

                $listaCFDI = [];
                while ($row = $stmt->fetch())
                {
                    array_push($listaCFDI, $row);
                }
                if(sizeof($listaCFDI) > 0)
                {
                    $response->setStatus(0);
                    $response->setListaCFDI($listaCFDI);
                    $response->setMessage("CONSULTA REALIZADA CON EXITO");
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados');

                }
            }
            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function getCFDIsSugerenciaRFC($index)
    {
        $response = new ResponseReportePago();
        $in = '('. implode(',', $index).')';
        $sql = "SELECT cfdis.id, cfdis.fecha_emision, cfdis.rfc_emisor, cfdis.total, cfdis.uuid 
FROM coedb_2019.cfdis JOIN coedb_2019.reporte_pago ON cfdis.year_rp=-1 AND cfdis.rfc_emisor=reporte_pago.rfc WHERE reporte_pago.id_rp IN". $in."ORDER BY cfdis.total";

        try {



            $stmt = parent::getConnection()->prepare($sql);

            if ($stmt->execute()) {

                $listaCFDI = [];
                while ($row = $stmt->fetch())
                {
                    array_push($listaCFDI, $row);
                }
                if(sizeof($listaCFDI) > 0)
                {
                    $response->setStatus(0);
                    $response->setListaCFDI($listaCFDI);
                    $response->setMessage("CONSULTA REALIZADA CON EXITO");
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados');

                }
            }
            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function getCFDIsNoLigados()
    {
        $response = new ResponseReportePago();

        $sql = "SELECT cfdis.id, cfdis.fecha_emision, cfdis.rfc_emisor, cfdis.total, cfdis.uuid FROM coedb_2019.cfdis WHERE cfdis.year_rp=-1 ORDER BY cfdis.total";
        try {
            $stmt = parent::getConnection()->prepare($sql);
            if ($stmt->execute()) {

                $listaCFDI = [];
                while ($row = $stmt->fetch())
                {
                    array_push($listaCFDI, $row);
                }
                if(sizeof($listaCFDI) > 0)
                {
                    $response->setStatus(0);
                    $response->setListaCFDI($listaCFDI);
                    $response->setMessage("CONSULTA REALIZADA CON EXITO");
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados');

                }
            }
            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function linkRP_CFDI($idRP,$idCFDI, $fechaLiga, $importeFactura,$fechaPago){

        $response = new ResponseStatus();
        $logger = new Logger();
        $sql = "UPDATE coedb_2019.cfdis SET id_rp=:id_rp, fechapago=:fecha_de_pago, importe_de_factura=:importe_de_factura, fecha_liga=:fecha_liga, linked=1 , data_rp='-1'  WHERE id=:id_cfdi ;";

        try {

            $stmt = parent::getConnection()->prepare($sql);


            $id_rp=$idRP;
            $stmt->bindParam(':id_rp',$id_rp);

            $fecha_de_pago=$fechaPago;
            $stmt->bindParam(':fecha_de_pago',$fecha_de_pago);

            $importe_de_factura=$importeFactura;
            $stmt->bindParam(':importe_de_factura',$importe_de_factura);

            $fecha_liga=$fechaLiga;
            $stmt->bindParam(':fecha_liga',$fecha_liga);

            $id_cfdi=$idCFDI;
            $stmt->bindParam(':id_cfdi',$id_cfdi);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $response->setMessage("Reporte de pago ligado");

            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()));
                $logger->logEntry("ERROR linkRP_CFDI",'excecute failed' . json_encode($stmt->errorInfo()));
            }


        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
            $logger->logEntry("ERROR linkRP_CFDI",$e->getMessage());

        }
        return $response;

    }

    function linkCFDI_RP($idRP,$idCFDI, $fechaLiga){

        $response = new ResponseStatus();
        $logger = new Logger();
        $sql = "UPDATE coedb_2019.reporte_pago SET id_cfdi=:id_cfdi, fecha_liga=:fecha_liga  WHERE id_rp=:id_rp ;";

        try {

            $stmt = parent::getConnection()->prepare($sql);


            $id_cfdi=$idCFDI;
            $stmt->bindParam(':id_cfdi',$id_cfdi);

            $fecha_liga=$fechaLiga;
            $stmt->bindParam(':fecha_liga',$fecha_liga);

            $id_rp=$idRP;
            $stmt->bindParam(':id_rp',$id_rp);


            if ($stmt->execute())
            {
                $response->setStatus(0);
                $response->setMessage("CFDI ligado");

            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()));
                $logger->logEntry("ERROR linCFDI_RP",'excecute failed' . json_encode($stmt->errorInfo()));
            }


        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
            $logger->logEntry("ERROR linCFDI_RP",$e->getMessage());
        }
        return $response;

    }

    function linkCFDI_RP_2($index, $fechaLiga){

        $response = new ResponseStatus();
        $logger = new Logger();
        $in = '('. implode(',', $index).')';
        $sql = "UPDATE coedb_2019.reporte_pago SET id_cfdi=-2, fecha_liga=:fecha_liga  WHERE reporte_pago.id_rp IN". $in;

        try {

            $stmt = parent::getConnection()->prepare($sql);


            $fecha_liga=$fechaLiga;
            $stmt->bindParam(':fecha_liga',$fecha_liga);


            if ($stmt->execute())
            {
                $response->setStatus(0);
                $response->setMessage("Pagos marcados como ligados");

            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()));
                $logger->logEntry("ERROR linCFDI_RP_2",'excecute failed' . json_encode($stmt->errorInfo()));
            }


        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $logger->logEntry("ERROR linCFDI_RP_2",$e->getMessage());
            $response->setMessage($e->getMessage());
        }
        return $response;

    }

    function linkCFDI_RP_varios($index, $idCFDI, $fechaLiga){

        $response = new ResponseStatus();
        $index_decode=json_decode($index);
        $logger = new Logger();
        $fecha_liga=$fechaLiga;
        $id_CFDI=$idCFDI;
        $data=$index;


        $in = '('. implode(',', $index_decode).')';
        $sql_RP = "UPDATE coedb_2019.reporte_pago SET id_cfdi=:id_cfdi, fecha_liga=:fecha_liga  WHERE reporte_pago.id_rp IN". $in;



        $sql_CFDI = $sql = "UPDATE coedb_2019.cfdis SET id_rp=-3,   fecha_liga=:fecha_liga, linked=3, data_rp=:data  WHERE id=:id_cfdi ;";
        try {

            $connection = parent::getConnection();
            $connection->beginTransaction();

            $stmt_RP= $connection->prepare($sql_RP);
            $stmt_RP->bindParam(':id_cfdi',$id_CFDI);
            $stmt_RP->bindParam(':fecha_liga',$fecha_liga);


            $stmt_CFDI= $connection->prepare($sql_CFDI);
            $stmt_CFDI->bindParam(':fecha_liga',$fecha_liga);
            $stmt_CFDI->bindParam(':data',$data);
            $stmt_CFDI->bindParam(':id_cfdi',$id_CFDI);


            if ($stmt_RP->execute() and $stmt_CFDI->execute())
            {
                $connection->commit();
                $response->setStatus(0);
                $response->setMessage("Ligas realizadas correctamente");

            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt_RP->errorInfo()) .  json_encode($stmt_CFDI->errorInfo()));
                $logger->logEntry("ERROR linkCFDI_RP_varios",'excecute failed' . json_encode($stmt_RP->errorInfo()) .  json_encode($stmt_CFDI->errorInfo()));
            }


        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
            $logger->logEntry("ERROR linkCFDI_RP_varios",$e->getMessage());

        }
        return $response;

    }

    function getRPData($cuenta,$mes,$year)
    {
        $response = new ResponseReportePago();

        $sql = "SELECT id_rp, fecha_de_pago, totales FROM coedb_2019.reporte_pago WHERE".
            " MONTH(reporte_pago.fecha_de_pago) =:mes  AND YEAR(reporte_pago.fecha_de_pago)=:year AND reporte_pago.id_cuenta =:cuenta";
        try {

            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':cuenta', $cuenta);
            $stmt->bindParam(':mes', $mes);
            $stmt->bindParam(':year', $year);

            if ($stmt->execute()) {

                $listaRP = [];
                while ($row = $stmt->fetch())
                {
                    array_push($listaRP, $row);
                }
                if(sizeof($listaRP) > 0)
                {
                    $response->setStatus(0);
                    $response->setListaRP($listaRP);
                    $response->setMessage("CONSULTA REALIZADA CON EXITO");
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados');

                }
            }
            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function getRPData_desfase($cuenta,$mes,$year)
    {
        $response = new ResponseReportePago();

        $sql =  "SELECT id_rp, fecha_de_pago, totales FROM coedb_2019.reporte_pago WHERE ".
                "MONTH(reporte_pago.fecha_de_pago) =:mes  AND YEAR(reporte_pago.fecha_de_pago)=:year AND reporte_pago.id_cuenta =:cuenta AND linked=-1";
        try {

            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':cuenta', $cuenta);
            $stmt->bindParam(':mes', $mes);
            $stmt->bindParam(':year', $year);

            if ($stmt->execute()) {

                $listaRP = [];
                while ($row = $stmt->fetch())
                {
                    array_push($listaRP, $row);
                }
                if(sizeof($listaRP) > 0)
                {
                    $response->setStatus(0);
                    $response->setListaRP($listaRP);
                    $response->setMessage("CONSULTA REALIZADA CON EXITO");
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados');

                }
            }
            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function getGL($RP, $mes, $year)
    {
        $response = new ResponseReportePago();
        //ESTE QUERY SE MODIFICO, VERIFICAR QUE FUNCIONA
        $sql =  "SELECT journal_entries.date, journal_entries.type, journal_entries.number, journal_entries.credit_amount, journal_entries.debit_amount FROM coedb_2019.journal_entries ".
                "WHERE MONTH(journal_entries.date) =:mes  AND YEAR(journal_entries.date)=:anio AND ((debit_amount + 0.5 >= :total AND debit_amount - 0.5 <= :total) OR (credit_amount + 0.5 >= :total AND credit_amount - 0.5 <= :total)) AND (type='AP' OR type='RJ');";
        try {

            $stmt = parent::getConnection()->prepare($sql);

            $total=$RP["totales"];

            $stmt->bindParam(':mes', $mes);
            $stmt->bindParam(':anio', $year);
            $stmt->bindParam(':total', $total);

            if ($stmt->execute()) {

                $listaGL = [];
                while ($row = $stmt->fetch())
                {
                    array_push($listaGL, $row);
                }
                if(sizeof($listaGL) == 2 && ($listaGL[0]["debit_amount"]==$listaGL[1]["credit_amount"] && $listaGL[1]["debit_amount"]==$listaGL[0]["credit_amount"] && $listaGL[0]["number"]==$listaGL[1]["number"] && $listaGL[0]["type"]==$listaGL[1]["type"]))//VERIFICAR QUE SI SEA 2, SOLO NECESITO DOS ELEMENTOS
                {


                    //FILTRAR MAS LOS DATOS
                    $response->setStatus(0);
                    $response->setListaGL($listaGL[0]);         //solo necesitamos un elemento para extraer los datos
                    $response->setMessage("CONSULTA REALIZADA CON EXITO");
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados');
                }
            }

            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }
        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function  ligarRPGL($indexMin, $indexMax, $gl)
    {

        $response = new ResponseStatus();
        $logger = new Logger();

        $sql = "UPDATE coedb_2019.reporte_pago SET number_gl=:number_gl, type_gl=:type_gl, date_gl=:date_gl, linked=1  WHERE id_rp>=:index_min AND id_rp<=:index_max ;";

        try {

            $stmt = parent::getConnection()->prepare($sql);

            $number_gl=$gl["number"];
            $type_gl=$gl["type"];
            $date_gl=$gl["date"];


            $stmt->bindParam(':number_gl',$number_gl);
            $stmt->bindParam(':type_gl',$type_gl);
            $stmt->bindParam(':date_gl',$date_gl);

            $stmt->bindParam(':index_min',$indexMin);
            $stmt->bindParam(':index_max',$indexMax);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $response->setMessage("Reporte de pago ligado con el gl");

            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()));
                $logger->logEntry("ERROR ligarRPGL",'excecute failed' . json_encode($stmt->errorInfo()));
            }


        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
            $logger->logEntry("ERROR ligarRPGL",$e->getMessage());
        }
        return $response;

    }

    function verificarPoliza($mes, $year, $tipo, $numero, $credito, $debito)
    {
        $response = new ResponseStatus();

        $sql = "SELECT id FROM coedb_2019.journal_entries WHERE period=:mes AND year=:year AND type=:tipo AND number=:numero AND credit_amount=:credito AND debit_amount=:debito";
        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':mes',$mes);
            $stmt->bindParam(':year',$year);
            $stmt->bindParam(':tipo',$tipo);
            $stmt->bindParam(':numero',$numero);
            $stmt->bindParam(':credito',$credito);
            $stmt->bindParam(':debito',$debito);

            if ($stmt->execute()) {

                $listaPolizas = [];
                while ($row = $stmt->fetch())
                {
                    array_push($listaPolizas, $row);
                }
                if(sizeof($listaPolizas) > 0)
                {
                    $response->setStatus(true);
                    $response->setMessage("Poliza Verificada");
                }
                else
                {
                    $response->setStatus(false);
                    $response->setMessage('Poliza No Encontrada');

                }
            }
            else {
                $response->setStatus(false);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }

        } catch (Exception $e) {
            $response->setStatus(false);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }

    function  ligarRPGL_manual($indexMin, $indexMax, $data)
    {

        $response = new ResponseStatus();
        $logger = new Logger();
        $sql = "UPDATE coedb_2019.reporte_pago SET data_gl=:data, linked=1  WHERE id_rp>=:index_min AND id_rp<=:index_max ;";

        try {

            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':data',$data);
            $stmt->bindParam(':index_min',$indexMin);
            $stmt->bindParam(':index_max',$indexMax);

            if ($stmt->execute())
            {
                $response->setStatus(0);
                $response->setMessage("Reporte de pago ligado con el gl");

            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()));
                $logger->logEntry("ERROR ligarRPGL_manual",'excecute failed' . json_encode($stmt->errorInfo()));
            }


        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
            $logger->logEntry("ERROR ligarRPGL_manual",$e->getMessage());
        }
        return $response;

    }

    function  ligarRPGL_manual2($indexMin, $indexMax, $data, $index)
    {

        $response = new ResponseStatus();
        $logger = new Logger();
        $in = '('. implode(',', $index).')';

        $sqlLink = "UPDATE coedb_2019.reporte_pago SET data_gl=:data, linked=1  WHERE id_rp>=:index_min AND id_rp<=:index_max ;";
        //$sqlUnlink= "UPDATE cfdis JOIN reporte_pago on reporte_pago.id_cfdi=cfdis.id SET cfdis.linked = 0, cfdis.id_rp=NULL, cfdis.importe_de_factura=NULL, cfdis.fecha_liga=NULL, reporte_pago.id_cfdi=-1, reporte_pago.fecha_liga='0000-00-00' WHERE reporte_pago.id_rp IN". $in;
        $sqlUnlink= "UPDATE cfdis JOIN reporte_pago on reporte_pago.id_rp=cfdis.id_rp SET cfdis.linked = 0, cfdis.id_rp=NULL, cfdis.importe_de_factura=NULL, cfdis.fecha_liga=NULL, reporte_pago.id_cfdi=-1, reporte_pago.fecha_liga='0000-00-00' WHERE reporte_pago.id_rp IN". $in;

        try {




            $connection = parent::getConnection();
            $connection->beginTransaction();

            $stmtLink= $connection->prepare($sqlLink);
            $stmtLink->bindParam(':data',$data);
            $stmtLink->bindParam(':index_min',$indexMin);
            $stmtLink->bindParam(':index_max',$indexMax);

            $stmtUnlink=$connection->prepare($sqlUnlink);


            if ($stmtLink->execute() and $stmtUnlink->execute())
            {
                $connection->commit();
                $response->setStatus(0);
                $response->setMessage("Ligas y desligas realizadas correctamente");

            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmtLink->errorInfo()) .  json_encode($stmtUnlink->errorInfo()));
                $logger->logEntry("ERROR ligarRPGL_manual2",'excecute failed' . json_encode($stmtLink->errorInfo()).json_encode($stmtUnlink->errorInfo()));
            }


        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->ligarRPGL_manual2());
            $logger->logEntry("ERROR ligarRPGL",$e->getMessage());
        }
        return $response;

    }


    //FOR THE SEVERAL PERIODS VERSION
    function getIdDiotNoLigados()
    {
        $sql =  "SELECT id_rp as `id`, uuid_rp FROM `reporte_pago` WHERE importe_de_factura!=0 AND status%13=0 AND status%11<>0";
        $response=new ResponseReportePago();

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
            $response->er=$e;
            return $response;
        }
    }
    function getDiotById($id)
    {
        $sql =  "SELECT * FROM `reporte_pago` WHERE id_rp=:id;";
        $response=new ResponseReportePago();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':id', $id);


            if ($stmt->execute())
            {
                $value = [];

                while ($row = $stmt->fetch()) {
                    $reportePagoTemp = new reportePago();
                    $reportePagoTemp->parseValuesFromSQL($row);
                    array_push($value, $reportePagoTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->reportePago=$value[0];
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->reportePago=null;
                    $response->setStatus(-1);
                    return $response;
                }
            }
            else
            {
                $response->reportePago=null;
                $response->setStatus(-2);
                return $response;
            }


        }
        catch (Exception $e) {
            $response->reportePago=null;
            $response->setStatus(-3);
            return $response;
        }
    }
    function guardarReportePagoConciliacion() {
        $response = new ResponseStatus();

        $sql = "INSERT IGNORE INTO reporte_pago(".

            "agente_aduanal, amex_raul_rivera, anticipos, banco_656_bnmx, base_de_iva, cargos_aeroportuarios,".
            "comentarios_a_la_excepcion, complemento_de_pago, comprobacion, comprobacion_1, comprobacion_2, comprobacion_3,".
            "comprobacion_4, comprobacion_5, concepto_pago_cxp, deducibles_de_autos, depositos_y_retiros_cta_tempur, descuento, ".
            "devoluciones, diferencia_en_pago, empleado_servicios_reembolsos, empresa, excento_de_iva, excepcion, fecha_de_pago, ".
            "fondo_ahorro, forma_de_pago, id_cuenta, id_local, id_rp, importe_de_factura, importe_de_pago, importe_factura, ".
            "impuestos_derechos, intercam, iva_16, iva_factura, iva_tasa_0, metodo_de_pago, no_deducibles, no_proveedor, ".
            "nombre_del_proveedor, nomina, numero_de_factura, observaciones, otras_cuentas_interco, pago_total_de_proveedor, ".
            "proveedor, proveedores_extranjeros, push_money, retencion_2_3_partes_iva_arend, retencion_2_3_partes_iva_honorarios, ".
            "retencion_4_transporte, retencion_6, retencion_10_isr_arrend, retencion_10_isr_honorarios, retencion_isr, retencion_iva, ".
            "rfc, servicios_esp_telmex, tipo_de_proveedor, total, total_por_factura, totales, traspasos, uuid_factura, venta_outlet,".
            "number_gl, type_gl, date_gl, linked, data_gl,status, year, uuid_rp".

            " )VALUES(".

            ":agente_aduanal, :amex_raul_rivera, :anticipos, :banco_656_bnmx, :base_de_iva, :cargos_aeroportuarios,".
            ":comentarios_a_la_excepcion, :complemento_de_pago, :comprobacion, :comprobacion_1, :comprobacion_2, :comprobacion_3,".
            ":comprobacion_4, :comprobacion_5, :concepto_pago_cxp, :deducibles_de_autos, :depositos_y_retiros_cta_tempur, :descuento,".
            ":devoluciones, :diferencia_en_pago, :empleado_servicios_reembolsos, :empresa, :excento_de_iva, :excepcion, :fecha_de_pago,".
            ":fondo_ahorro, :forma_de_pago, :id_cuenta, null, :id_rp, :importe_de_factura, :importe_de_pago, :importe_factura,".
            ":impuestos_derechos, :intercam, :iva_16, :iva_factura, :iva_tasa_0, :metodo_de_pago, :no_deducibles, :no_proveedor,".
            ":nombre_del_proveedor, :nomina, :numero_de_factura, :observaciones, :otras_cuentas_interco, :pago_total_de_proveedor,".
            ":proveedor, :proveedores_extranjeros, :push_money, :retencion_2_3_partes_iva_arend, :retencion_2_3_partes_iva_honorarios,".
            ":retencion_4_transporte, :retencion_6, :retencion_10_isr_arrend, :retencion_10_isr_honorarios, :retencion_isr, :retencion_iva,".
            ":rfc, :servicios_esp_telmex, :tipo_de_proveedor, :total, :total_por_factura, :totales, :traspasos, :uuid_factura, :venta_outlet,".
            ":number_gl, :type_gl, :date_gl, :linked, data_gl, :status, :year, :uuid_rp".

            ");";
        //TO_DATE('17/12/2015', 'DD/MM/YYYY')
        try {

            $stmt = parent::getConnection()->prepare($sql);


            $agente_aduanal=$this->reportePago->agente_aduanal;
            $stmt->bindParam(':agente_aduanal',$agente_aduanal);

            $amex_raul_rivera=$this->reportePago->amex_raul_rivera;
            $stmt->bindParam(':amex_raul_rivera',$amex_raul_rivera);

            $anticipos=$this->reportePago->anticipos;
            $stmt->bindParam(':anticipos',$anticipos);

            $banco_656_bnmx=$this->reportePago->banco_656_bnmx;
            $stmt->bindParam(':banco_656_bnmx',$banco_656_bnmx);

            $base_de_iva=$this->reportePago->base_de_iva;
            $stmt->bindParam(':base_de_iva',$base_de_iva);

            $cargos_aeroportuarios=$this->reportePago->cargos_aeroportuarios;
            $stmt->bindParam(':cargos_aeroportuarios',$cargos_aeroportuarios);

            $comentarios_a_la_excepcion=$this->reportePago->comentarios_a_la_excepcion;
            $stmt->bindParam(':comentarios_a_la_excepcion',$comentarios_a_la_excepcion);

            $complemento_de_pago=$this->reportePago->complemento_de_pago;
            $stmt->bindParam(':complemento_de_pago',$complemento_de_pago);

            $comprobacion=$this->reportePago->comprobacion;
            $stmt->bindParam(':comprobacion',$comprobacion);

            $comprobacion_1=$this->reportePago->comprobacion_1;
            $stmt->bindParam(':comprobacion_1',$comprobacion_1);

            $comprobacion_2=$this->reportePago->comprobacion_2;
            $stmt->bindParam(':comprobacion_2',$comprobacion_2);

            $comprobacion_3=$this->reportePago->comprobacion_3;
            $stmt->bindParam(':comprobacion_3',$comprobacion_3);

            $comprobacion_4=$this->reportePago->comprobacion_4;
            $stmt->bindParam(':comprobacion_4',$comprobacion_4);

            $comprobacion_5=$this->reportePago->comprobacion_5;
            $stmt->bindParam(':comprobacion_5',$comprobacion_5);

            $concepto_pago_cxp=$this->reportePago->concepto_pago_cxp;
            $stmt->bindParam(':concepto_pago_cxp',$concepto_pago_cxp);

            $deducibles_de_autos=$this->reportePago->deducibles_de_autos;
            $stmt->bindParam(':deducibles_de_autos',$deducibles_de_autos);

            $depositos_y_retiros_cta_tempur=$this->reportePago->depositos_y_retiros_cta_tempur;
            $stmt->bindParam(':depositos_y_retiros_cta_tempur',$depositos_y_retiros_cta_tempur);

            $descuento=$this->reportePago->descuento;
            $stmt->bindParam(':descuento',$descuento);

            $devoluciones=$this->reportePago->devoluciones;
            $stmt->bindParam(':devoluciones',$devoluciones);

            $diferencia_en_pago=$this->reportePago->diferencia_en_pago;
            $stmt->bindParam(':diferencia_en_pago',$diferencia_en_pago);

            $empleado_servicios_reembolsos=$this->reportePago->empleado_servicios_reembolsos;
            $stmt->bindParam(':empleado_servicios_reembolsos',$empleado_servicios_reembolsos);

            $empresa=$this->reportePago->empresa;
            $stmt->bindParam(':empresa',$empresa);

            $excento_de_iva=$this->reportePago->excento_de_iva;
            $stmt->bindParam(':excento_de_iva',$excento_de_iva);

            $excepcion=$this->reportePago->excepcion;
            $stmt->bindParam(':excepcion',$excepcion);

            $fecha_de_pago=$this->reportePago->fecha_de_pago;
            $stmt->bindParam(':fecha_de_pago',$fecha_de_pago);

            $fondo_ahorro=$this->reportePago->fondo_ahorro;
            $stmt->bindParam(':fondo_ahorro',$fondo_ahorro);

            $forma_de_pago=$this->reportePago->forma_de_pago;
            $stmt->bindParam(':forma_de_pago',$forma_de_pago);

            $id_cuenta=$this->reportePago->id_cuenta;
            $stmt->bindParam(':id_cuenta',$id_cuenta);

            $id_rp=$this->reportePago->id_rp;
            $stmt->bindParam(':id_rp',$id_rp);

            $importe_de_factura=$this->reportePago->importe_de_factura;
            $stmt->bindParam(':importe_de_factura',$importe_de_factura);

            $importe_de_pago=$this->reportePago->importe_de_pago;
            $stmt->bindParam(':importe_de_pago',$importe_de_pago);

            $importe_factura=$this->reportePago->importe_factura;
            $stmt->bindParam(':importe_factura',$importe_factura);

            $impuestos_derechos=$this->reportePago->impuestos_derechos;
            $stmt->bindParam(':impuestos_derechos',$impuestos_derechos);

            $intercam=$this->reportePago->intercam;
            $stmt->bindParam(':intercam',$intercam);

            $iva_16=$this->reportePago->iva_16;
            $stmt->bindParam(':iva_16',$iva_16);

            $iva_factura=$this->reportePago->iva_factura;
            $stmt->bindParam(':iva_factura',$iva_factura);

            $iva_tasa_0=$this->reportePago->iva_tasa_0;
            $stmt->bindParam(':iva_tasa_0',$iva_tasa_0);

            $metodo_de_pago=$this->reportePago->metodo_de_pago;
            $stmt->bindParam(':metodo_de_pago',$metodo_de_pago);

            $no_deducibles=$this->reportePago->no_deducibles;
            $stmt->bindParam(':no_deducibles',$no_deducibles);

            $no_proveedor=$this->reportePago->no_proveedor;
            $stmt->bindParam(':no_proveedor',$no_proveedor);

            $nombre_del_proveedor=$this->reportePago->nombre_del_proveedor;
            $stmt->bindParam(':nombre_del_proveedor',$nombre_del_proveedor);

            $nomina=$this->reportePago->nomina;
            $stmt->bindParam(':nomina',$nomina);

            $numero_de_factura=$this->reportePago->numero_de_factura;
            $stmt->bindParam(':numero_de_factura',$numero_de_factura);

            $observaciones=$this->reportePago->observaciones;
            $stmt->bindParam(':observaciones',$observaciones);

            $otras_cuentas_interco=$this->reportePago->otras_cuentas_interco;
            $stmt->bindParam(':otras_cuentas_interco',$otras_cuentas_interco);

            $pago_total_de_proveedor=$this->reportePago->pago_total_de_proveedor;
            $stmt->bindParam(':pago_total_de_proveedor',$pago_total_de_proveedor);

            $proveedor=$this->reportePago->proveedor;
            $stmt->bindParam(':proveedor',$proveedor);

            $proveedores_extranjeros=$this->reportePago->proveedores_extranjeros;
            $stmt->bindParam(':proveedores_extranjeros',$proveedores_extranjeros);

            $push_money=$this->reportePago->push_money;
            $stmt->bindParam(':push_money',$push_money);

            $retencion_2_3_partes_iva_arend=$this->reportePago->retencion_2_3_partes_iva_arend;
            $stmt->bindParam(':retencion_2_3_partes_iva_arend',$retencion_2_3_partes_iva_arend);

            $retencion_2_3_partes_iva_honorarios=$this->reportePago->retencion_2_3_partes_iva_honorarios;
            $stmt->bindParam(':retencion_2_3_partes_iva_honorarios',$retencion_2_3_partes_iva_honorarios);

            $retencion_4_transporte=$this->reportePago->retencion_4_transporte;
            $stmt->bindParam(':retencion_4_transporte',$retencion_4_transporte);

            $retencion_6=$this->reportePago->retencion_6;
            $stmt->bindParam(':retencion_6',$retencion_6);

            $retencion_10_isr_arrend=$this->reportePago->retencion_10_isr_arrend;
            $stmt->bindParam(':retencion_10_isr_arrend',$retencion_10_isr_arrend);

            $retencion_10_isr_honorarios=$this->reportePago->retencion_10_isr_honorarios;
            $stmt->bindParam(':retencion_10_isr_honorarios',$retencion_10_isr_honorarios);

            $retencion_isr=$this->reportePago->retencion_isr;
            $stmt->bindParam(':retencion_isr',$retencion_isr);

            $retencion_iva=$this->reportePago->retencion_iva;
            $stmt->bindParam(':retencion_iva',$retencion_iva);

            $rfc=$this->reportePago->rfc;
            $stmt->bindParam(':rfc',$rfc);

            $servicios_esp_telmex=$this->reportePago->servicios_esp_telmex;
            $stmt->bindParam(':servicios_esp_telmex',$servicios_esp_telmex);

            $tipo_de_proveedor=$this->reportePago->tipo_de_proveedor;
            $stmt->bindParam(':tipo_de_proveedor',$tipo_de_proveedor);

            $total=$this->reportePago->total;
            $stmt->bindParam(':total',$total);

            $total_por_factura=$this->reportePago->total_por_factura;
            $stmt->bindParam(':total_por_factura',$total_por_factura);

            $totales=$this->reportePago->totales;
            $stmt->bindParam(':totales',$totales);

            $traspasos=$this->reportePago->traspasos;
            $stmt->bindParam(':traspasos',$traspasos);

            $uuid_factura=$this->reportePago->uuid_factura;
            $stmt->bindParam(':uuid_factura',$uuid_factura);

            $venta_outlet=$this->reportePago->venta_outlet;
            $stmt->bindParam(':venta_outlet',$venta_outlet);


            //ONLY FOR GL
            $number_gl=-1;
            $stmt->bindParam(':number_gl',$number_gl);

            $type_gl=-1;
            $stmt->bindParam(':type_gl',$type_gl);

            $date_gl="0000-00-00";
            $stmt->bindParam(':date_gl',$date_gl);

            $linked=-1;
            $stmt->bindParam(':linked',$linked);

            //FOR DE SEVERAL YEARS

            $status=$this->reportePago->status;
            $stmt->bindParam(':status',$status);

            $uuid_rp=$this->reportePago->uuid_rp;
            $stmt->bindParam(':uuid_rp',$uuid_rp);

            $year=$this->reportePago->year;
            $stmt->bindParam(':year',$year);


//            $response->setMessage($sql);


            if ($stmt->execute())
            {
                $response->setStatus(0);
                $response->setMessage("INSERTADO CORRECTAMENTE");
            }
            else
            {
                $response->setStatus(Errore::ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD);
                $response->setMessage('excecute failed' . json_encode($stmt->errorInfo()));
            }


        }
        catch (Exception $e)
        {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }
        return $response;
    }
    function getDiotByIds($ids)
    {
        $sql =  "SELECT * FROM `reporte_pago` WHERE id_rp in ".$ids.";";
        $response=new ResponseReportePago();
        try {

            $stmt = parent::getConnection()->prepare($sql);

            if ($stmt->execute())
            {

                $response->setStatus(0);

                $value = [];
                while ($row = $stmt->fetch()) {
                    $reportePagoTemp = new reportePago();
                    $reportePagoTemp->parseValuesFromSQL($row);
                    array_push($value, $reportePagoTemp);
                }

                if (sizeof($value) > 0)
                {

                    $response->reportePago=$value;
                    $response->setStatus(0);
                    return $response;
                }
                else
                {
                    $response->reportePago=null;
                    $response->setStatus(-1);
                    return $response;
                }


            }
            else
            {
                $response->reportePago=null;
                $response->setStatus(-2);
                return $response;
            }


        }
        catch (Exception $e) {
            $response->reportePago=null;
            $response->setMessage($e);
            $response->setStatus(-3);
            return $response;
        }
    }

    function actualizarStatus($id,$status)
    {
        $sql =  "UPDATE reporte_pago set status=status*:status  WHERE id_rp=:id AND status%:status<>0;";

        $response=new ResponseReportePago();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);


            if ($stmt->execute())
            {
                $response->setStatus(0);
                return $response;
            }
            else
            {
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            {
                $response->setStatus(-1);
                return $response;
            }
        }
    }
    function actualizarStatusUuidRp($uuid_rp,$status)
    {
        $sql =  "UPDATE reporte_pago set status=status*:status  WHERE uuid_rp=:uuid_rp AND status%:status<>0;";

        $response=new ResponseReportePago();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':uuid_rp', $uuid_rp);
            $stmt->bindParam(':status', $status);


            if ($stmt->execute())
            {
                $response->setStatus(0);
                return $response;
            }
            else
            {
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            {
                $response->setStatus(-1);
                return $response;
            }
        }
    }
    function removeStatus($uuid_rp,$status)
    {
        $sql =  "UPDATE reporte_pago set status=status/:status  WHERE uuid_rp=:uuid_rp AND status%:status=0;";

        $response=new ResponseReportePago();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':uuid_rp', $uuid_rp);
            $stmt->bindParam(':status', $status);


            if ($stmt->execute())
            {
                $response->setStatus(0);
                return $response;
            }
            else
            {
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            {
                $response->setStatus(-1);
                return $response;
            }
        }
    }

    //LIGA MANUAL RP
    function getCfdisNoLigadosPeriodo($mes, $year)
    {
        $response = new ResponseReportePago();


        $sql = "SELECT cfdis.id, cfdis.fecha_emision, cfdis.rfc_emisor, cfdis.total, cfdis.uuid, cfdis.year FROM `cfdis` WHERE MONTH(fecha_emision)=:mes AND YEAR(fecha_emision)=:year AND (rfc_receptor='SMM950911V10' or rfc_receptor='SCM991217AU7' or rfc_receptor='SSM991217R47') AND ligado_rp%2<>0";
        try {
            $stmt = parent::getConnection()->prepare($sql);

            $stmt->bindParam(':mes', $mes);
            $stmt->bindParam(':year', $year);
            if ($stmt->execute())
            {

                $listaCFDI = [];
                while ($row = $stmt->fetch())
                {
                    array_push($listaCFDI, $row);
                }
                if(sizeof($listaCFDI) > 0)
                {
                    $response->setStatus(0);
                    $response->setListaCFDI($listaCFDI);
                    $response->setMessage("CONSULTA REALIZADA CON EXITO");
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados');

                }
            }
            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }
    function getPagosNoLigadosCfdi()
    {
        $response = new ResponseReportePago();

        $sql = "SELECT id_local, id_rp, fecha_de_pago, no_proveedor, rfc, nombre_del_proveedor, numero_de_factura, importe_de_factura, importe_de_pago, traspasos, base_de_iva, iva_16, uuid_factura, status, year, uuid_rp FROM reporte_pago WHERE importe_de_factura!=0  AND status%13=0;";
        try {

            $stmt = parent::getConnection()->prepare($sql);

            if ($stmt->execute()) {

                $listaPagos = [];
                while ($row = $stmt->fetch())
                {
                    $reportePago = new ReportePago();

                    $reportePago->id_rp=$row["id_rp"];
                    $reportePago->id_local=$row["id_local"];
                    $reportePago->fecha_de_pago=$row["fecha_de_pago"];
                    $reportePago->no_proveedor=$row["no_proveedor"];
                    $reportePago->rfc=$row["rfc"];
                    $reportePago->nombre_del_proveedor=$row["nombre_del_proveedor"];
                    $reportePago->numero_de_factura=$row["numero_de_factura"];
                    $reportePago->importe_de_factura=$row["importe_de_factura"];
                    $reportePago->importe_de_pago=$row["importe_de_pago"];
                    $reportePago->traspasos=$row["traspasos"];
                    $reportePago->base_de_iva=$row["base_de_iva"];
                    $reportePago->iva_16=$row["iva_16"];
                    $reportePago->uuid_factura=$row["uuid_factura"];
                    $reportePago->year=$row["year"];
                    $reportePago->uuid_rp=$row["uuid_rp"];

                    array_push($listaPagos, $reportePago);


                }


                if(sizeof($listaPagos) > 0)
                {
                    $response->setStatus(0);
                    $response->setListaRP($listaPagos);
                    $response->setMessage("CONSULTA REALIZADA CON EXITO");
                }
                else
                {
                    $response->setStatus(Errore::WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS);
                    $response->setMessage('sin resultados');

                }
            }
            else {
                $response->setStatus(Errore::ERROR_DE_CONEXION_BD);
                $response->setMessage('excecute failed ' . json_encode($stmt->errorInfo()));
            }

        } catch (Exception $e) {
            $response->setStatus(Errore::ERROR_INESPERADO_CATCH);
            $response->setMessage($e->getMessage());
        }


        return $response;
    }
    function borrarDiot($uuid_rp)
    {
        $sql="DELETE FROM `reporte_pago` WHERE uuid_rp= :uuid_rp";

        $response=new ResponseReportePago();

        try {

            $stmt = parent::getConnection()->prepare($sql);
            $stmt->bindParam(':uuid_rp', $uuid_rp);


            if ($stmt->execute())
            {
                $response->setStatus(0);
                return $response;
            }
            else
            {
                $response->setStatus(-1);
                return $response;
            }

        }
        catch (Exception $e) {
            {
                $response->setStatus(-1);
                return $response;
            }
        }
    }

}
class ResponseReportePago extends ResponseStatus {

    public $idCFDI;
    public $idReportePago;
    public $listaRP=[];
    public $listaCFDI=[];
    public $listaGL=[];
    public $ids=[];
    public $er;
    public $reportePago;

    function getIdCFDI() {
        return $this->idCFDI;
    }
    function getIdReportePago() {
        return $this->idReportePago;
    }
    function setIdReportePago($id) {
        $this->idReportePago = $id;
    }
    function setIdCFDI($id) {
        $this->idCFDI = $id;
    }
    function getListaRP() {
        return $this->listaRP;
    }
    function setListaRP($lista) {
        $this->listaRP = $lista;
    }
    function getListaCFDI() {
        return $this->listaCFDI;
    }
    function setListaCFDI($lista) {
        $this->listaCFDI = $lista;
    }
    function getListaGL() {
        return $this->listaGL;
    }
    function setListaGL($lista) {
        $this->listaGL = $lista;
    }

}



