<?php


class saldos extends JSONObject{


    public $id="";
    public $folio="";
    public $actual="";
    public $total="";
    public $id_factura="";
    public $id_cfdi="";
    public $id_cobranza="";
    public $fecha_emision="";
    public $ultimo_cobro="";
    public $fecha_cierre="";
    public $estatus="";
    public $year="";
    public $id_unico="";

    //RP
    public $uuid="";
    public $year_cfdi="";
    public $id_rp="";
    public $ultimo_pago="";


    function parseValues($data)
    {
        if (property_exists($data, "id"))
            $this->id = $data->id;

        if (property_exists($data, "folio"))
            $this->folio = $data->folio;

        if (property_exists($data, "actual"))
            $this->actual = $data->actual;

        if (property_exists($data, "total"))
            $this->total = $data->total;

        if (property_exists($data, "id_factura"))
            $this->id_factura  = $data->id_factura;

        if (property_exists($data, "id_cfdi"))
            $this->id_cfdi  = $data->id_cfdi;

        if (property_exists($data, "id_cobranza"))
            $this->id_cobranza  = $data->id_cobranza;

        if (property_exists($data, "fecha_emision"))
            $this->fecha_emision  = $data->fecha_emision;

        if (property_exists($data, "fecha_cierre"))
            $this->fecha_cierre  = $data->fecha_cierre;

        if (property_exists($data, "ultimo_cobro"))
            $this->ultimo_cobro  = $data->ultimo_cobro;

        if (property_exists($data, "estatus"))
            $this->estatus  = $data->estatus;

        if (property_exists($data, "year"))
            $this->year  = $data->year;

        if (property_exists($data, "folio") && property_exists($data, "year"))
            $this->id_unico  = $data->folio.$data->year;

    }
    function parseValuesLigaCFDI($data)
    {
        if (property_exists("id_bloque_cobranza", $data))
            $this->id_bloque_cobranza = $data->id_bloque_cobranza;

        if (property_exists("id_cobros", $data))
            $this->id_cobros = $data->id_cobros;

        if (property_exists("fecha_cobro", $data))
            $this->fecha_cobro = $data->fecha_cobro;

        if (property_exists("id_cfdi", $data))
            $this->id_cfdi = $data->id_cfdi;

    }
    function parseValuesFromSQL($data)
    {

        if (array_key_exists("id", $data))
            $this->id = intval($data['id']);
        if (array_key_exists("folio", $data))
            $this->folio = intval($data['folio']);
        if (array_key_exists("actual", $data))
            $this->actual = floatval($data['actual']);
        if (array_key_exists("total", $data))
            $this->total = floatval($data['total']);
        if (array_key_exists("id_factura", $data))
            $this->id_factura  = intval($data["id_factura"]);
        if (array_key_exists("id_cfdi", $data))
            $this->id_cfdi  = intval($data["id_cfdi"]);
        if (array_key_exists("id_cobranza", $data))
            $this->id_cobranza = $data['id_cobranza'];
        if (array_key_exists("fecha_emision", $data))
            $this->fecha_emision = $data['fecha_emision'];
        if (array_key_exists("fecha_cierre", $data))
            $this->fecha_cierre = $data['fecha_cierre'];
        if (array_key_exists("ultimo_cobro", $data))
            $this->ultimo_cobro = $data['ultimo_cobro'];
        if (array_key_exists("estatus", $data))
            $this->estatus  = intval($data['estatus']);
        if (array_key_exists("year", $data))
            $this->year  = intval($data['year']);
        if (array_key_exists("id_unico", $data))
            $this->id_unico  = $data['id_unico'];
    }

    //RP
    function parseValuesRP($data)
    {
        if (property_exists($data, "id"))
            $this->id = $data->id;

        if (property_exists($data, "uuid"))
            $this->uuid = $data->uuid;

        if (property_exists($data, "actual"))
            $this->actual = $data->actual;

        if (property_exists($data, "total"))
            $this->total = $data->total;

        if (property_exists($data, "year_cfdi"))
            $this->year_cfdi  = intval($data->year_cfdi);

        if (property_exists($data, "id_rp"))
            $this->id_rp  = $data->id_rp;

        if (property_exists($data, "fecha_emision"))
            $this->fecha_emision  = $data->fecha_emision;

        if (property_exists($data, "fecha_cierre"))
            $this->fecha_cierre  = $data->fecha_cierre;

        if (property_exists($data, "ultimo_pago"))
            $this->ultimo_pago  = $data->ultimo_pago;

        if (property_exists($data, "estatus"))
            $this->estatus  = $data->estatus;

        if (property_exists($data, "year"))
            $this->year  = $data->year;


    }
    function parseValuesFromSQLRP($data)
    {
        if (array_key_exists("id", $data))
            $this->id = intval($data['id']);
        if (array_key_exists("uuid", $data))
            $this->uuid = $data['uuid'];
        if (array_key_exists("actual", $data))
            $this->actual = floatval($data['actual']);
        if (array_key_exists("total", $data))
            $this->total = floatval($data['total']);
        if (array_key_exists("year_cfdi", $data))
            $this->year_cfdi  = intval($data["year_cfdi"]);
        if (array_key_exists("id_rp", $data))
            $this->id_rp = $data['id_rp'];
        if (array_key_exists("fecha_emision", $data))
            $this->fecha_emision = $data['fecha_emision'];
        if (array_key_exists("fecha_cierre", $data))
            $this->fecha_cierre = $data['fecha_cierre'];
        if (array_key_exists("ultimo_pago", $data))
            $this->ultimo_pago = $data['ultimo_pago'];
        if (array_key_exists("estatus", $data))
            $this->estatus  = intval($data['estatus']);
        if (array_key_exists("year", $data))
            $this->year  = intval($data['year']);
    }





}