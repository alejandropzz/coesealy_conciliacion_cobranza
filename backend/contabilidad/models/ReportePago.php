<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportePago
 *
 * @author PZZ
 */
class ReportePago extends JSONObject{

    public $id_rp=null;
    public $id_local=null;
    public $id_cuenta=null;
    public $fecha_de_pago=null;
    public $no_proveedor=null;
    public $rfc=null;
    public $tipo_de_proveedor=null;
    public $nombre_del_proveedor=null;
    public $numero_de_factura=null;
    public $importe_de_factura=null;
    public $importe_de_pago=null;
    public $traspasos=null;
    public $base_de_iva=null;
    public $iva_16=null;
    public $retencion_4_transporte=null;
    public $iva_tasa_0=null;
    public $excento_de_iva=null;
    public $retencion_6=null;
    public $retencion_10_isr_honorarios=null;
    public $retencion_2_3_partes_iva_honorarios=null;
    public $retencion_10_isr_arrend=null;
    public $retencion_2_3_partes_iva_arend=null;
    public $anticipos=null;
    public $diferencia_en_pago=null;
    public $banco_656_bnmx=null;
    public $amex_raul_rivera=null;
    public $proveedores_extranjeros=null;
    public $push_money=null;
    public $venta_outlet=null;
    public $empleado_servicios_reembolsos=null;
    public $nomina=null;
    public $fondo_ahorro=null;
    public $depositos_y_retiros_cta_tempur=null;
    public $devoluciones=null;
    public $impuestos_derechos=null;
    public $agente_aduanal=null;
    public $intercam=null;
    public $deducibles_de_autos=null;
    public $empresa=null;
    public $no_deducibles=null;
    public $otras_cuentas_interco=null;
    public $servicios_esp_telmex=null;
    public $cargos_aeroportuarios=null;
    public $excepcion=null;
    public $comentarios_a_la_excepcion=null;
    public $total_por_factura=null;
    public $pago_total_de_proveedor=null;
    public $comprobacion=null;
    public $concepto_pago_cxp=null;
    public $totales=null;
    public $observaciones=null;
    public $uuid_factura=null;
    public $proveedor=null;
    public $importe_factura=null;
    public $descuento=null;
    public $iva_factura=null;
    public $retencion_iva=null;
    public $retencion_isr=null;
    public $total=null;
    public $comprobacion_1=null;
    public $comprobacion_2=null;
    public $comprobacion_3=null;
    public $comprobacion_4=null;
    public $comprobacion_5=null;
    public $forma_de_pago=null;
    public $metodo_de_pago=null;
    public $complemento_de_pago=null;

    public $status=null;
    public $uuid_rp="";
    public $year=-1;

    function parseValues($data) {
        if (property_exists($data, "agente_aduanal"))
            $this->agente_aduanal=$data->agente_aduanal;

        if (property_exists($data, "amex_raul_rivera"))
            $this->amex_raul_rivera=$data->amex_raul_rivera;

        if (property_exists($data, "anticipos"))
            $this->anticipos=$data->anticipos;

        if (property_exists($data, "banco_656_bnmx"))
            $this->banco_656_bnmx=$data->banco_656_bnmx;

        if (property_exists($data, "base_de_iva"))
            $this->base_de_iva=$data->base_de_iva;

        if (property_exists($data, "cargos_aeroportuarios"))
            $this->cargos_aeroportuarios=$data->cargos_aeroportuarios;

        if (property_exists($data, "comentarios_a_la_excepcion"))
            $this->comentarios_a_la_excepcion=$data->comentarios_a_la_excepcion;

        if (property_exists($data, "complemento_de_pago"))
            $this->complemento_de_pago=$data->complemento_de_pago;

        if (property_exists($data, "comprobacion"))
            $this->comprobacion=$data->comprobacion;

        if (property_exists($data, "comprobacion_1"))
            $this->comprobacion_1=$data->comprobacion_1;

        if (property_exists($data, "comprobacion_2"))
            $this->comprobacion_2=$data->comprobacion_2;

        if (property_exists($data, "comprobacion_3"))
            $this->comprobacion_3=$data->comprobacion_3;

        if (property_exists($data, "comprobacion_4"))
            $this->comprobacion_4=$data->comprobacion_4;

        if (property_exists($data, "comprobacion_5"))
            $this->comprobacion_5=$data->comprobacion_5;

        if (property_exists($data, "concepto_pago_cxp"))
            $this->concepto_pago_cxp=$data->concepto_pago_cxp;

        if (property_exists($data, "deducibles_de_autos"))
            $this->deducibles_de_autos=$data->deducibles_de_autos;

        if (property_exists($data, "depositos_y_retiros_cta_tempur"))
            $this->depositos_y_retiros_cta_tempur=$data->depositos_y_retiros_cta_tempur;

        if (property_exists($data, "descuento"))
            $this->descuento=$data->descuento;

        if (property_exists($data, "devoluciones"))
            $this->devoluciones=$data->devoluciones;

        if (property_exists($data, "diferencia_en_pago"))
            $this->diferencia_en_pago=$data->diferencia_en_pago;

        if (property_exists($data, "empleado_servicios_reembolsos"))
            $this->empleado_servicios_reembolsos=$data->empleado_servicios_reembolsos;

        if (property_exists($data, "empresa"))
            $this->empresa=$data->empresa;

        if (property_exists($data, "excento_de_iva"))
            $this->excento_de_iva=$data->excento_de_iva;

        if (property_exists($data, "excepcion"))
            $this->excepcion=$data->excepcion;

        if (property_exists($data, "fecha_de_pago"))
            $this->fecha_de_pago=$data->fecha_de_pago;

        if (property_exists($data, "fondo_ahorro"))
            $this->fondo_ahorro=$data->fondo_ahorro;

        if (property_exists($data, "forma_de_pago"))
            $this->forma_de_pago=$data->forma_de_pago;

        if (property_exists($data, "id_cuenta"))
            $this->id_cuenta=$data->id_cuenta;

        if (property_exists($data, "importe_de_factura"))
            $this->importe_de_factura=$data->importe_de_factura;

        if (property_exists($data, "importe_de_pago"))
            $this->importe_de_pago=$data->importe_de_pago;

        if (property_exists($data, "importe_factura"))
            $this->importe_factura=$data->importe_factura;

        if (property_exists($data, "impuestos_derechos"))
            $this->impuestos_derechos=$data->impuestos_derechos;

        if (property_exists($data, "intercam"))
            $this->intercam=$data->intercam;

        if (property_exists($data, "iva_16"))
            $this->iva_16=$data->iva_16;

        if (property_exists($data, "iva_factura"))
            $this->iva_factura=$data->iva_factura;

        if (property_exists($data, "iva_tasa_0"))
            $this->iva_tasa_0=$data->iva_tasa_0;

        if (property_exists($data, "metodo_de_pago"))
            $this->metodo_de_pago=$data->metodo_de_pago;

        if (property_exists($data, "no_deducibles"))
            $this->no_deducibles=$data->no_deducibles;

        if (property_exists($data, "no_proveedor"))
            $this->no_proveedor=$data->no_proveedor;

        if (property_exists($data, "nombre_del_proveedor"))
            $this->nombre_del_proveedor=$data->nombre_del_proveedor;

        if (property_exists($data, "nomina"))
            $this->nomina=$data->nomina;

        if (property_exists($data, "numero_de_factura"))
            $this->numero_de_factura=$data->numero_de_factura;

        if (property_exists($data, "observaciones"))
            $this->observaciones=$data->observaciones;

        if (property_exists($data, "otras_cuentas_interco"))
            $this->otras_cuentas_interco=$data->otras_cuentas_interco;

        if (property_exists($data, "pago_total_de_proveedor"))
            $this->pago_total_de_proveedor=$data->pago_total_de_proveedor;

        if (property_exists($data, "proveedor"))
            $this->proveedor=$data->proveedor;

        if (property_exists($data, "proveedores_extranjeros"))
            $this->proveedores_extranjeros=$data->proveedores_extranjeros;

        if (property_exists($data, "push_money"))
            $this->push_money=$data->push_money;

        if (property_exists($data, "retencion_2_3_partes_iva_arend"))
            $this->retencion_2_3_partes_iva_arend=$data->retencion_2_3_partes_iva_arend;

        if (property_exists($data, "retencion_2_3_partes_iva_honorarios"))
            $this->retencion_2_3_partes_iva_honorarios=$data->retencion_2_3_partes_iva_honorarios;

        if (property_exists($data, "retencion_4_transporte"))
            $this->retencion_4_transporte=$data->retencion_4_transporte;

        if (property_exists($data, "retencion_6"))
            $this->retencion_6=$data->retencion_6;

        if (property_exists($data, "retencion_10_isr_arrend"))
            $this->retencion_10_isr_arrend=$data->retencion_10_isr_arrend;

        if (property_exists($data, "retencion_10_isr_honorarios"))
            $this->retencion_10_isr_honorarios=$data->retencion_10_isr_honorarios;

        if (property_exists($data, "retencion_isr"))
            $this->retencion_isr=$data->retencion_isr;

        if (property_exists($data, "retencion_iva"))
            $this->retencion_iva=$data->retencion_iva;

        if (property_exists($data, "rfc"))
            $this->rfc=$data->rfc;

        if (property_exists($data, "servicios_esp_telmex"))
            $this->servicios_esp_telmex=$data->servicios_esp_telmex;

        if (property_exists($data, "tipo_de_proveedor"))
            $this->tipo_de_proveedor=$data->tipo_de_proveedor;

        if (property_exists($data, "total"))
            $this->total=$data->total;

        if (property_exists($data, "total_por_factura"))
            $this->total_por_factura=$data->total_por_factura;

        if (property_exists($data, "totales"))
            $this->totales=$data->totales;

        if (property_exists($data, "traspasos"))
            $this->traspasos=$data->traspasos;

        if (property_exists($data, "uuid_factura"))
            $this->uuid_factura=$data->uuid_factura;

        if (property_exists($data, "venta_outlet"))
            $this->venta_outlet=$data->venta_outlet;

        if (property_exists($data, "id_rp"))
            $this->id_rp=$data->id_rp;

        if (property_exists($data, "id_local"))
            $this->id_local=$data->id_local;

        if (property_exists($data, "status"))
            $this->status=$data->status;

        if (property_exists($data, "year"))
            $this->year=$data->year;

        if (property_exists($data,"uuid_rp"))
            $this->uuid_rp=$data->uuid_rp;
    }
    function parseValuesFromSQL($data) {

        if (array_key_exists("agente_aduanal", $data))
            $this->agente_aduanal=$data["agente_aduanal"];

        if (array_key_exists("amex_raul_rivera", $data))
            $this->amex_raul_rivera=$data["amex_raul_rivera"];


        if (array_key_exists("anticipos", $data))
            $this->anticipos=$data["anticipos"];


        if (array_key_exists("banco_656_bnmx", $data))
            $this->banco_656_bnmx=$data["banco_656_bnmx"];

        if (array_key_exists("base_de_iva", $data))
            $this->base_de_iva=$data["base_de_iva"];

        if (array_key_exists("cargos_aeroportuarios", $data))
            $this->cargos_aeroportuarios=$data["cargos_aeroportuarios"];

        if (array_key_exists("comentarios_a_la_excepcion", $data))
            $this->comentarios_a_la_excepcion=$data["comentarios_a_la_excepcion"];

        if (array_key_exists("complemento_de_pago", $data))
            $this->complemento_de_pago=$data["complemento_de_pago"];

        if (array_key_exists("comprobacion", $data))
            $this->comprobacion=$data["comprobacion"];

        if (array_key_exists("comprobacion_1", $data))
            $this->comprobacion_1=$data["comprobacion_1"];

        if (array_key_exists("comprobacion_2", $data))
            $this->comprobacion_2=$data["comprobacion_2"];

        if (array_key_exists("comprobacion_3", $data))
            $this->comprobacion_3=$data["comprobacion_3"];

        if (array_key_exists("comprobacion_4", $data))
            $this->comprobacion_4=$data["comprobacion_4"];

        if (array_key_exists("comprobacion_5", $data))
            $this->comprobacion_5=$data["comprobacion_5"];

        if (array_key_exists("concepto_pago_cxp", $data))
            $this->concepto_pago_cxp=$data["concepto_pago_cxp"];

        if (array_key_exists("deducibles_de_autos", $data))
            $this->deducibles_de_autos=$data["deducibles_de_autos"];

        if (array_key_exists("depositos_y_retiros_cta_tempur", $data))
            $this->depositos_y_retiros_cta_tempur=$data["depositos_y_retiros_cta_tempur"];

        if (array_key_exists("descuento", $data))
            $this->descuento=$data["descuento"];

        if (array_key_exists("devoluciones", $data))
            $this->devoluciones=$data["devoluciones"];

        if (array_key_exists("diferencia_en_pago", $data))
            $this->diferencia_en_pago=$data["diferencia_en_pago"];

        if (array_key_exists("empleado_servicios_reembolsos", $data))
            $this->empleado_servicios_reembolsos=$data["empleado_servicios_reembolsos"];

        if (array_key_exists("empresa", $data))
            $this->empresa=$data["empresa"];

        if (array_key_exists("excento_de_iva", $data))
            $this->excento_de_iva=$data["excento_de_iva"];

        if (array_key_exists("excepcion", $data))
            $this->excepcion=$data["excepcion"];

        if (array_key_exists("fecha_de_pago", $data))
            $this->fecha_de_pago=$data["fecha_de_pago"];

        if (array_key_exists("fondo_ahorro", $data))
            $this->fondo_ahorro=$data["fondo_ahorro"];

        if (array_key_exists("forma_de_pago", $data))
            $this->forma_de_pago=$data["forma_de_pago"];

        if (array_key_exists("id_cuenta", $data))
            $this->id_cuenta=$data["id_cuenta"];

        if (array_key_exists("importe_de_factura", $data))
            $this->importe_de_factura=$data["importe_de_factura"];

        if (array_key_exists("importe_de_pago", $data))
            $this->importe_de_pago=$data["importe_de_pago"];

        if (array_key_exists("importe_factura", $data))
            $this->importe_factura=$data["importe_factura"];

        if (array_key_exists("impuestos_derechos", $data))
            $this->impuestos_derechos=$data["impuestos_derechos"];

        if (array_key_exists("intercam", $data))
            $this->intercam=$data["intercam"];

        if (array_key_exists("iva_16", $data))
            $this->iva_16=$data["iva_16"];

        if (array_key_exists("iva_factura", $data))
            $this->iva_factura=$data["iva_factura"];

        if (array_key_exists("iva_tasa_0", $data))
            $this->iva_tasa_0=$data["iva_tasa_0"];

        if (array_key_exists("metodo_de_pago", $data))
            $this->metodo_de_pago=$data["metodo_de_pago"];

        if (array_key_exists("no_deducibles", $data))
            $this->no_deducibles=$data["no_deducibles"];

        if (array_key_exists("no_proveedor", $data))
            $this->no_proveedor=$data["no_proveedor"];

        if (array_key_exists("nombre_del_proveedor", $data))
            $this->nombre_del_proveedor=$data["nombre_del_proveedor"];

        if (array_key_exists("nomina", $data))
            $this->nomina=$data["nomina"];

        if (array_key_exists("numero_de_factura", $data))
            $this->numero_de_factura=$data["numero_de_factura"];

        if (array_key_exists("observaciones", $data))
            $this->observaciones=$data["observaciones"];

        if (array_key_exists("otras_cuentas_interco", $data))
            $this->otras_cuentas_interco=$data["otras_cuentas_interco"];

        if (array_key_exists("pago_total_de_proveedor", $data))
            $this->pago_total_de_proveedor=$data["pago_total_de_proveedor"];

        if (array_key_exists("proveedor", $data))
            $this->proveedor=$data["proveedor"];

        if (array_key_exists("proveedores_extranjeros", $data))
            $this->proveedores_extranjeros=$data["proveedores_extranjeros"];

        if (array_key_exists("push_money", $data))
            $this->push_money=$data["push_money"];

        if (array_key_exists("retencion_2_3_partes_iva_arend", $data))
            $this->retencion_2_3_partes_iva_arend=$data["retencion_2_3_partes_iva_arend"];

        if (array_key_exists("retencion_2_3_partes_iva_honorarios", $data))
            $this->retencion_2_3_partes_iva_honorarios=$data["retencion_2_3_partes_iva_honorarios"];

        if (array_key_exists("retencion_4_transporte", $data))
            $this->retencion_4_transporte=$data["retencion_4_transporte"];

        if (array_key_exists("retencion_6", $data))
            $this->retencion_6=$data["retencion_6"];

        if (array_key_exists("retencion_10_isr_arrend", $data))
            $this->retencion_10_isr_arrend=$data["retencion_10_isr_arrend"];

        if (array_key_exists("retencion_10_isr_honorarios", $data))
            $this->retencion_10_isr_honorarios=$data["retencion_10_isr_honorarios"];

        if (array_key_exists("retencion_isr", $data))
            $this->retencion_isr=$data["retencion_isr"];

        if (array_key_exists("retencion_iva", $data))
            $this->retencion_iva=$data["retencion_iva"];

        if (array_key_exists("rfc", $data))
            $this->rfc=$data["rfc"];

        if (array_key_exists("servicios_esp_telmex", $data))
            $this->servicios_esp_telmex=$data["servicios_esp_telmex"];

        if (array_key_exists("tipo_de_proveedor", $data))
            $this->tipo_de_proveedor=$data["tipo_de_proveedor"];

        if (array_key_exists("total", $data))
            $this->total=$data["total"];

        if (array_key_exists("total_por_factura", $data))
            $this->total_por_factura=$data["total_por_factura"];

        if (array_key_exists("totales", $data))
            $this->totales=$data["totales"];

        if (array_key_exists("traspasos", $data))
            $this->traspasos=$data["traspasos"];

        if (array_key_exists("uuid_factura", $data))
            $this->uuid_factura=$data["uuid_factura"];

        if (array_key_exists("venta_outlet", $data))
            $this->venta_outlet=$data["venta_outlet"];

        if (array_key_exists("id_rp", $data))
            $this->id_rp=$data["id_rp"];

        if (array_key_exists("id_local", $data))
            $this->id_local=$data["id_local"];

        if (array_key_exists("status", $data))
            $this->status=$data["status"];

        if (array_key_exists("uuid_rp", $data))
            $this->uuid_rp=$data["uuid_rp"];

        if (array_key_exists("year",$data))
            $this->year=$data["year"];
    }


}
