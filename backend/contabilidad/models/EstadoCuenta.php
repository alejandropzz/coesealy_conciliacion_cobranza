<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EstadoCuenta
 *
 * @author PZZ
 */
class EstadoCuenta extends JSONObject{

    public $id_cuenta;
    public $id_compa;
    public $id_edc;
    public $fecha;
    public $descripcion;
    public $depositos;
    public $retiros;
    public $saldo;
    public $year;
    public $uuid;

    function parseValues($data)
    {
        if (array_key_exists("depositos", $data))
            $this->depositos = $data->depositos;

        if (array_key_exists("descripcion", $data))
            $this->descripcion = $data->descripcion;

        if (array_key_exists("fecha", $data))
            $this->fecha = $data->fecha;

        if (array_key_exists("id_compa", $data))
            $this->id_compa = $data->id_compa;

        if (array_key_exists("id_cuenta", $data))
            $this->id_cuenta = $data->id_cuenta;

        if (array_key_exists("id_edc", $data))
            $this->id_edc = $data->id_edc;

        if (array_key_exists("retiros", $data))
            $this->retiros = $data->retiros;

        if (array_key_exists("saldo", $data))
            $this->saldo = $data->saldo;

        if (array_key_exists("uuid", $data))
            $this->uuid = $data->uuid;

        if (array_key_exists("year", $data))
            $this->year = $data->year;
    }

    function getDepositos() {
        return $this->depositos;
    }
    function getDescripcion() {
        return $this->descripcion;
    }
    function getFecha() {
        return $this->fecha;
    }
    function getIdCompa() {
        return $this->id_compa;
    }
    function getIdCuenta() {
        return $this->id_cuenta;
    }
    function getIdEdc() {
        return $this->id_edc;
    }
    function getRetiros() {
        return $this->retiros;
    }
    function getSaldo() {
        return $this->saldo;
    }
    function getUuid() {
        return $this->uuid;
    }
    function getYear() {
        return $this->year;
    }

    function setIdCuenta($idCuenta) {
        $this->id_cuenta = $idCuenta;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setDepositos($depositos) {
        $this->depositos = $depositos;
    }

    function setRetiros($retiros) {
        $this->retiros = $retiros;
    }

    function setSaldo($saldo) {
        $this->saldo = $saldo;
    }

    function setIdCompa($idCompa) {
        $this->id_compa = $idCompa;
    }
}
