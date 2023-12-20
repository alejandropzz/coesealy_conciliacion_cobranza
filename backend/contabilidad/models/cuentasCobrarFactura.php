<?php


class cuentasCobrarFactura extends JSONObject{

    public $id;
    public $billing=0;
    public $charges="";
    public $comprobacion=0;
    public $customer_name="";
    public $customer_po="";
    public $diferencia="";
    public $diff="";
    public $diff_sales="";
    public $discount="";
    public $estado_del_sat="";
    public $fecha_timbrado="";
    public $financial=0;
    public $freight="";
    public $gross_sales=0;
    public $id_gl=-1;
    public $importe_xml=0;
    public $in_transit="";
    public $invoice="";
    public $list_sales=0;
    public $net_sales=0;
    public $number=0;
    public $number_folio=0;
    public $periodo=0;
    public $tax_amount="";
    public $tipo="";
    public $tipo_factura="";
    public $type="";
    public $UUID="";
    public $registro_contable="";
    public $charges_type="";
    public $erroneo="";
    public $completo="";
    public $comentarios="";
    public $year;
    public $id_unico;

    function parseValues($data)
    {
        if (property_exists($data, "id"))
            $this->id = $data->id;
        if (property_exists($data, "UUID"))
            $this->UUID = $data->UUID;
        if (property_exists($data, "billing"))
            $this->billing = $data->billing;
        if (property_exists($data, "charges"))
            $this->charges  = $data->charges;
        if (property_exists($data, "comprobacion"))
            $this->comprobacion = $data->comprobacion;
        if (property_exists($data, "customer_name"))
            $this->customer_name = $data->customer_name;
        if (property_exists($data, "customer_po"))
            $this->customer_po = $data->customer_po;
        if (property_exists($data, "diferencia"))
            $this->diferencia = $data->diferencia;
        if (property_exists($data, "diff"))
            $this->diff = $data->diff;
        if (property_exists($data, "diff_sales"))
            $this->diff_sales = $data->diff_sales;
        if (property_exists($data, "discount"))
            $this->discount = $data->discount;
        if (property_exists($data, "estado_del_sat"))
            $this->estado_del_sat = $data->estado_del_sat;
        if (property_exists($data, "fecha_timbrado"))
            $this->fecha_timbrado = $data->fecha_timbrado;
        if (property_exists($data, "financial"))
            $this->financial = $data->financial;
        if (property_exists($data, "freight"))
            $this->freight = $data->freight;
        if (property_exists($data, "gross_sales"))
            $this->gross_sales = $data->gross_sales;
        if (property_exists($data, "id_gl"))
            $this->id_gl = $data->id_gl;
        if (property_exists($data, "importe_xml"))
            $this->importe_xml = $data->importe_xml;
        if (property_exists($data, "in_transit"))
            $this->in_transit = $data->in_transit;
        if (property_exists($data, "invoice"))
            $this->invoice = $data->invoice;
        if (property_exists($data, "list_sales"))
            $this->list_sales = $data->list_sales;
        if (property_exists($data, "periodo"))
            $this->periodo = $data->periodo;
        if (property_exists($data, "net_sales"))
            $this->net_sales = $data->net_sales;
        if (property_exists($data, "number"))
            $this->number = $data->number;
        if (property_exists($data, "number_folio"))
            $this->number_folio = $data->number_folio;
        if (property_exists($data, "tax_amount"))
            $this->tax_amount = $data->tax_amount;
        if (property_exists($data, "tipo"))
            $this->tipo = $data->tipo;
        if (property_exists($data, "tipo_factura"))
            $this->tipo_factura = $data->tipo_factura;
        if (property_exists($data, "type"))
            $this->type = $data->type;
        if (property_exists($data, "registro_contable"))
            $this->registro_contable = $data->registro_contable;
        if (property_exists($data, "charges_type"))
            $this->charges_type = $data->charges_type;
        if (property_exists($data, "erroneo"))
            $this->erroneo = $data->erroneo;
        if (property_exists($data, "completo"))
            $this->completo = $data->completo;
        if (property_exists($data, "comentarios"))
            $this->comentarios = $data->comentarios;
        if (property_exists($data, "year"))
            $this->year = $data->year;

        if (property_exists($data, "year") && (property_exists($data, "id")))
            $this->id_unico = $data->year."_".$data->id;



    }
    function parseValuesFromSQL($data)
    {
        if (array_key_exists("id", $data))
            $this->id = $data['id'];
        if (array_key_exists("UUID", $data))
            $this->UUID = $data['UUID'];
        if (array_key_exists("billing", $data))
            $this->billing = $data['billing'];
        if (array_key_exists("charges", $data))
            $this->charges  = $data['charges'];
        if (array_key_exists("comprobacion", $data))
            $this->comprobacion = $data['comprobacion'];
        if (array_key_exists("customer_name", $data))
            $this->customer_name = $data['customer_name'];
        if (array_key_exists("customer_po", $data))
            $this->customer_po = $data['customer_po'];
        if (array_key_exists("diferencia", $data))
            $this->diferencia = $data['diferencia'];
        if (array_key_exists("diff", $data))
            $this->diff = $data['diff'];
        if (array_key_exists("diff_sales", $data))
            $this->diff_sales = $data['diff_sales'];
        if (array_key_exists("discount", $data))
            $this->discount = $data['discount'];
        if (array_key_exists("estado_del_sat", $data))
            $this->estado_del_sat = $data['estado_del_sat'];
        if (array_key_exists("fecha_timbrado", $data))
            $this->fecha_timbrado = $data['fecha_timbrado'];
        if (array_key_exists("financial", $data))
            $this->financial = $data['financial'];
        if (array_key_exists("freight", $data))
            $this->freight = $data['freight'];
        if (array_key_exists("gross_sales", $data))
            $this->gross_sales = $data['gross_sales'];
        if (array_key_exists("id_gl", $data))
            $this->id_gl = $data['id_gl'];
        if (array_key_exists("importe_xml", $data))
            $this->importe_xml = $data['importe_xml'];
        if (array_key_exists("in_transit", $data))
            $this->in_transit = $data['in_transit'];
        if (array_key_exists("invoice", $data))
            $this->invoice = $data['invoice'];
        if (array_key_exists("list_sales", $data))
            $this->list_sales = $data['list_sales'];
        if (array_key_exists("periodo", $data))
            $this->periodo = $data['periodo'];
        if (array_key_exists("net_sales", $data))
            $this->net_sales = $data['net_sales'];
        if (array_key_exists("number", $data))
            $this->number = $data['number'];
        if (array_key_exists("number_folio", $data))
            $this->number_folio = $data['number_folio'];
        if (array_key_exists("tax_amount", $data))
            $this->tax_amount = $data['tax_amount'];
        if (array_key_exists("tipo", $data))
            $this->tipo = $data['tipo'];
        if (array_key_exists("tipo_factura", $data))
            $this->tipo_factura = $data['tipo_factura'];
        if (array_key_exists("type", $data))
            $this->type = $data['type'];

        if (array_key_exists("registro_contable", $data))
            $this->registro_contable = $data["registro_contable"];
        if (array_key_exists("charges_type", $data))
            $this->charges_type = $data["charges_type"];
        if (array_key_exists("erroneo", $data))
            $this->erroneo = $data["erroneo"];


        if (array_key_exists("completo", $data))
            $this->completo = $data["completo"];
        if (array_key_exists("comentarios", $data))
            $this->comentarios = $data["comentarios"];

        if (array_key_exists("year", $data))
            $this->year = $data["year"];
        if (array_key_exists("id_unico", $data))
            $this->id_unico = $data["id_unico"];

    }



}