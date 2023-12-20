<?php


class reporteCobranza extends JSONObject{

    public $age_ays="";
    public $autorizacion="";
    public $check_number="";
    public $comments="";
    public $id_cuenta="";
    public $deposit_date="";
    public $folio="";
    public $gl_account="";
    public $invoice_amount="";
    public $invoice_balance="";
    public $payment_amount=0;
    public $voice_date="";
    public $periodo=0;
    public $id=0;
    public $uuid="";
    public $id_gl="";
    public $ligado=0;
    public $erroneo=0;
    public $folio_nota_credito=-1;
    public $completo="";
    public $comentarios="";
    public $group_cxc="";
    public $year="";
    public $id_cobranza="";

    function parseValues($data)
    {
        if (array_key_exists("age_ays", $data))
            $this->age_ays = $data->age_ays;

        if (array_key_exists("autorizacion", $data))
            $this->autorizacion = $data->autorizacion;

        if (array_key_exists("check_number", $data))
            $this->check_number = $data->check_number;

        if (array_key_exists("comments", $data))
            $this->comments  = $data->comments;

        if (array_key_exists("id_cuenta", $data))
            $this->id_cuenta  = $data->id_cuenta;

        if (array_key_exists("deposit_date", $data))
            $this->deposit_date = $data->deposit_date;

        if (array_key_exists("customer_name", $data))
            $this->customer_name = $data->customer_name;

        if (array_key_exists("folio", $data))
            $this->folio = $data->folio;

        if (array_key_exists("gl_account", $data))
            $this->gl_account = $data->gl_account;

        if (array_key_exists("invoice_amount", $data))
            $this->invoice_amount = $data->invoice_amount;

        if (array_key_exists("invoice_balance", $data))
            $this->invoice_balance = $data->invoice_balance;

        if (array_key_exists("payment_amount", $data))
            $this->payment_amount = $data->payment_amount;

        if (array_key_exists("voice_date", $data))
            $this->voice_date = $data->voice_date;

        if (array_key_exists("periodo", $data))
            $this->periodo = $data->periodo;

        if (array_key_exists("uuid", $data))
            $this->uuid = $data->uuid;

        if (array_key_exists("id_gl", $data))
            $this->id_gl = $data->id_gl;

        if (array_key_exists("erroneo", $data))
            $this->erroneo = $data->erroneo;

        if (array_key_exists("ligado", $data))
            $this->ligado = $data->ligado;

        if (array_key_exists("folio_nota_credito", $data))
            $this->folio_nota_credito = $data->folio_nota_credito;

        if (array_key_exists("completo", $data))
            $this->completo = $data->completo;
        if (array_key_exists("comentarios", $data))
            $this->comentarios = $data->comentarios;
        if (array_key_exists("completo", $data))
            $this->completo = $data->completo;
        if (array_key_exists("comentarios", $data))
            $this->comentarios = $data->comentarios;
        if (array_key_exists("group_cxc", $data))
            $this->group_cxc = $data->group_cxc;
        if (array_key_exists("year", $data))
            $this->year = $data->year;

    }
    function parseValuesFromSQL($data)
    {
        if (array_key_exists("id", $data))
        {
            $this->id = $data['id'];
            $this->id_cobranza = $data['id'];
        }
        if (array_key_exists("folio", $data))
            $this->folio = $data['folio'];
        if (array_key_exists("voice_date", $data))
            $this->voice_date  = $data['voice_date'];
        if (array_key_exists("deposit_date", $data))
            $this->deposit_date = $data['deposit_date'];
        if (array_key_exists("check_number", $data))
            $this->check_number = $data['check_number'];
        if (array_key_exists("invoice_amount", $data))
            $this->invoice_amount = $data['invoice_amount'];
        if (array_key_exists("payment_amount", $data))
            $this->payment_amount = $data['payment_amount'];
        if (array_key_exists("invoice_balance", $data))
            $this->invoice_balance = $data['invoice_balance'];
        if (array_key_exists("age_ays", $data))
            $this->age_ays = $data['age_ays'];
        if (array_key_exists("gl_account", $data))
            $this->gl_account = $data['gl_account'];
        if (array_key_exists("comments", $data))
            $this->comments = $data['comments'];
        if (array_key_exists("cuenta", $data))
            $this->id_cuenta = $data['cuenta'];
        if (array_key_exists("uuid", $data))
            $this->uuid = $data['uuid'];
        if (array_key_exists("id_gl", $data))
            $this->id_gl = $data['id_gl'];
        if (array_key_exists("erroneo", $data))
            $this->erroneo = $data['erroneo'];
        if (array_key_exists("ligado", $data))
            $this->ligado = $data['ligado'];
        if (array_key_exists("folio_nota_credito", $data))
            $this->folio_nota_credito = $data['folio_nota_credito'];

        if (array_key_exists("completo", $data))
            $this->completo = $data["completo"];
        if (array_key_exists("comentarios", $data))
            $this->comentarios = $data["comentarios"];
        if (array_key_exists("group_cxc", $data))
            $this->group_cxc = $data["group_cxc"];
        if (array_key_exists("year", $data))
            $this->year = $data["year"];
    }







}