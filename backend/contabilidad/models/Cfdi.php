<?php

/**
 * Description of CFDI
 *
 * @author memogarrido
 */
class Cfdi extends JSONObject {

    public $id;
    public $archivo_xml;
    public $cfdiscol;
    public $combustible;
    public $complemento;
    public $conceptos;
    public $condicion_de_pago;
    public $descuento;
    public $direccion_emisor;
    public $estadopago;
    public $fecha_cobro;
    public $fecha_emision;
    public $fecha_liga;
    public $fecha_timbrado;
    public $fechapago;
    public $folio;
    public $formadepago;
    public $id_bloque_cobranza;
    public $id_cobros;
    public $ieps_3p;
    public $ieps_6p;
    public $ieps_7p;
    public $ieps_8p;
    public $ieps_9p;
    public $ieps_26_5p;
    public $ieps_30p;
    public $ieps_53p;
    public $ieps_160p;
    public $iva;
    public $ligado_cobranza;
    public $linked;
    public $localidad_emisor;
    public $lugardeexpedicion;
    public $metodo_de_pago;
    public $moneda;
    public $nombre_emisor;
    public $nombre_receptor;
    public $numctapago;
    public $numregidtrib;
    public $residenciafiscal;
    public $retenido_isr;
    public $retenido_iva;
    public $rfc_emisor;
    public $rfc_receptor;
    public $serie;
    public $subtotal;
    public $tipo;
    public $tipo_coe;
    public $tipo_de_cambio;
    public $total;
    public $total_ieps;
    public $total_localretenido;
    public $total_localtrasladado;
    public $total_retenidos;
    public $total_trasladados;
    public $totaloriginal;
    public $usocfdi;
    public $uuid;
    public $uuid_relacion;
    public $version;

    public $ligado_rp;
    public $year;

    function getId() {
        return $this->id;
    }

    function setTipo_coe($tipo_coe) {
        $this->tipo_coe = $tipo_coe;
    }

    function getArchivo_xml() {
        return $this->archivo_xml;
    }

    function getCombustible() {
        return $this->combustible;
    }

    function getComplemento() {
        return $this->complemento;
    }

    function getConceptos() {
        return $this->conceptos;
    }

    function getCondicion_de_pago() {
        return $this->condicion_de_pago;
    }

    function getDescuento() {
        return $this->descuento;
    }

    function getDireccion_emisor() {
        return $this->direccion_emisor;
    }

    function getEstadopago() {
        return $this->estadopago;
    }

    function getFecha_cobro() {
        return $this->fecha_cobro;
    }

    function getFecha_emision() {
        return $this->fecha_emision;
    }
    function getFecha_timbrado() {
        return $this->fecha_timbrado;
    }

    function getFechapago() {
        return $this->fechapago;
    }

    function getFolio() {
        return $this->folio;
    }

    function getFormadepago() {
        return $this->formadepago;
    }

    function getCfdiscol() {
        return $this->cfdiscol;
    }

    function getId_bloque_cobranza() {
        return $this->id_bloque_cobranza;
    }

    function getId_cobros() {
        return $this->id_cobros;
    }


    function getIeps_160p() {
        return $this->ieps_160p;
    }

    function getIeps_26_5p() {
        return $this->ieps_26_5p;
    }

    function getIeps_3p() {
        return $this->ieps_3p;
    }

    function getIeps_30p() {
        return $this->ieps_30p;
    }

    function getIeps_53p() {
        return $this->ieps_53p;
    }

    function getIeps_6p() {
        return $this->ieps_6p;
    }

    function getIeps_7p() {
        return $this->ieps_7p;
    }

    function getIeps_8p() {
        return $this->ieps_8p;
    }

    function getIeps_9p() {
        return $this->ieps_9p;
    }

    function getIva() {
        return $this->iva;
    }

    function getLigado_cobranza() {
        return $this->ligado_cobranza;
    }

    function getLinked() {
        return $this->linked;
    }

    function getLocalidad_emisor() {
        return $this->localidad_emisor;
    }

    function getLugardeexpedicion() {
        return $this->lugardeexpedicion;
    }

    function getMetodo_de_pago() {
        return $this->metodo_de_pago;
    }

    function getMoneda() {
        return $this->moneda;
    }

    function getNombre_emisor() {
        return $this->nombre_emisor;
    }

    function getNombre_receptor() {
        return $this->nombre_receptor;
    }

    function getNumctapago() {
        return $this->numctapago;
    }

    function getNumregidtrib() {
        return $this->numregidtrib;
    }

    function getResidenciafiscal() {
        return $this->residenciafiscal;
    }

    function getRetenido_isr() {
        return $this->retenido_isr;
    }

    function getRetenido_iva() {
        return $this->retenido_iva;
    }

    function getRfc_emisor() {
        return $this->rfc_emisor;
    }

    function getRfc_receptor() {
        return $this->rfc_receptor;
    }

    function getSerie() {
        return $this->serie;
    }

    function getSubtotal() {
        return $this->subtotal;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getTipo_coe() {
        return $this->tipo_coe;
    }

    function getTipo_de_cambio() {
        return $this->tipo_de_cambio;
    }

    function getTotal() {
        return $this->total;
    }

    function getTotal_ieps() {
        return $this->total_ieps;
    }

    function getTotal_localretenido() {
        return $this->total_localretenido;
    }

    function getTotal_localtrasladado() {
        return $this->total_localtrasladado;
    }

    function getTotal_retenidos() {
        return $this->total_retenidos;
    }

    function getTotal_trasladados() {
        return $this->total_trasladados;
    }

    function getTotaloriginal() {
        return $this->totaloriginal;
    }

    function getUsocfdi() {
        return $this->usocfdi;
    }

    function getUuid() {
        return $this->uuid;
    }

    function getUuid_relacion() {
        return $this->uuid_relacion;
    }

    function getVersion() {
        return $this->version;
    }

    function getLigado_rp() {
        return $this->ligado_rp;
    }
    function getYear() {
        return $this->year;
    }





    function setId($id) {
        $this->id = $id;
    }

    function setarchivo_xml($archivo_xml) {
        $this->archivo_xml = $archivo_xml;
    }

    function setCombustible($combustible) {
        $this->combustible = $combustible;
    }

    function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    function setConceptos($conceptos) {
        $this->conceptos = $conceptos;
    }

    function setcondicion_de_pago($condicion_de_pago) {
        $this->condicion_de_pago = $condicion_de_pago;
    }

    function setDescuento($descuento) {
        $this->descuento = $descuento;
    }

    function setdireccion_emisor($direccion_emisor) {
        $this->direccion_emisor = $direccion_emisor;
    }

    function setEstadopago($estadopago) {
        $this->estadopago = $estadopago;
    }

    function setfecha_emision($fecha_emision) {
        $this->fecha_emision = $fecha_emision;
    }

    function setfecha_timbrado($fecha_timbrado) {
        $this->fecha_timbrado = $fecha_timbrado;
    }

    function setFechapago($fechapago) {
        $this->fechapago = $fechapago;
    }

    function setFolio($folio) {
        $this->folio = $folio;
    }

    function setFormadepago($formadepago) {
        $this->formadepago = $formadepago;
    }

    function setCfdiscol($cfdiscol) {
        $this->cfdiscol = $cfdiscol;
    }

    function setIeps_160p($ieps_160p) {
        $this->ieps_160p = $ieps_160p;
    }

    function setIeps_26_5p($ieps_26_5p) {
        $this->ieps_26_5p = $ieps_26_5p;
    }

    function setIeps_3p($ieps_3p) {
        $this->ieps_3p = $ieps_3p;
    }

    function setIeps_30p($ieps_30p) {
        $this->ieps_30p = $ieps_30p;
    }

    function setIeps_53p($ieps_53p) {
        $this->ieps_53p = $ieps_53p;
    }

    function setIeps_6p($ieps_6p) {
        $this->ieps_6p = $ieps_6p;
    }

    function setIeps_7p($ieps_7p) {
        $this->ieps_7p = $ieps_7p;
    }

    function setIeps_8p($ieps_8p) {
        $this->ieps_8p = $ieps_8p;
    }

    function setIeps_9p($ieps_9p) {
        $this->ieps_9p = $ieps_9p;
    }

    function setIva($iva) {
        $this->iva = $iva;
    }

    function setlocalidad_emisor($localidad_emisor) {
        $this->localidad_emisor = $localidad_emisor;
    }

    function setLugardeexpedicion($lugardeexpedicion) {
        $this->lugardeexpedicion = $lugardeexpedicion;
    }

    function setmetodo_de_pago($metodo_de_pago) {
        $this->metodo_de_pago = $metodo_de_pago;
    }

    function setMoneda($moneda) {
        $this->moneda = $moneda;
    }

    function setnombre_emisor($nombre_emisor) {
        $this->nombre_emisor = $nombre_emisor;
    }

    function setnombre_receptor($nombre_receptor) {
        $this->nombre_receptor = $nombre_receptor;
    }

    function setNumctapago($numctapago) {
        $this->numctapago = $numctapago;
    }

    function setNumregidtrib($numregidtrib) {
        $this->numregidtrib = $numregidtrib;
    }

    function setResidenciafiscal($residenciafiscal) {
        $this->residenciafiscal = $residenciafiscal;
    }

    function setretenido_isr($retenido_isr) {
        $this->retenido_isr = $retenido_isr;
    }

    function setretenido_iva($retenido_iva) {
        $this->retenido_iva = $retenido_iva;
    }

    function setrfc_emisor($rfc_emisor) {
        $this->rfc_emisor = $rfc_emisor;
    }

    function setrfc_receptor($rfc_receptor) {
        $this->rfc_receptor = $rfc_receptor;
    }

    function setSerie($serie) {
        $this->serie = $serie;
    }

    function setSubtotal($subtotal) {
        $this->subtotal = $subtotal;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function settipo_de_cambio($tipo_de_cambio) {
        $this->tipo_de_cambio = $tipo_de_cambio;
    }

    function setTotal($total) {
        $this->total = $total;
    }

    function settotal_ieps($total_ieps) {
        $this->total_ieps = $total_ieps;
    }

    function settotal_localretenido($total_localretenido) {
        $this->total_localretenido = $total_localretenido;
    }

    function settotal_localtrasladado($total_localtrasladado) {
        $this->total_localtrasladado = $total_localtrasladado;
    }

    function settotal_retenidos($total_retenidos) {
        $this->total_retenidos = $total_retenidos;
    }

    function settotal_trasladados($total_trasladados) {
        $this->total_trasladados = $total_trasladados;
    }

    function setTotaloriginal($totaloriginal) {
        $this->totaloriginal = $totaloriginal;
    }

    function setUsocfdi($usocfdi) {
        $this->usocfdi = $usocfdi;
    }

    function setUuid($uuid) {
        $this->uuid = $uuid;
    }

    function setuuid_relacion($uuid_relacion) {
        $this->uuid_relacion = $uuid_relacion;
    }

    function setVersion($version) {
        $this->version = $version;
    }

    function setLinked($link){
        $this->linked = $link;
    }
}
