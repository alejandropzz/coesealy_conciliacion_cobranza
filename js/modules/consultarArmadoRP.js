async function consultar(uuid) {
    let grupoSaldos=await extraerGrupoUUID(uuid)
    if(grupoSaldos==false)
    {
        alert("No se encontraron registros para ese folio")
        return false;
    }
    let year=grupoSaldos[0].year_cfdi

    let i;
    let cfdi=await getCfdiByUUID(uuid,year)
    let listaDiot=[]
    for (i=0;i<grupoSaldos.length;i++)
    {
        let ids=(((grupoSaldos[i].id_rp.replace(/\\/g, "")).replace("[","(") ).replace("]",")")).replaceAll('"',"")
        let year_diot=grupoSaldos[i].year
        let diot=await extraerDiotByIds(ids,year_diot)
        listaDiot=listaDiot.concat(diot)
    }

    imprimirVerCFDI(cfdi)
    imprimirVerReportePago(listaDiot)


}
async function extraerGrupoUUID(uuid) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/extraer_grupo_rp.php",
                dataType: 'json',
                data: {"uuid": uuid.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(result.grupoSaldos)
                    }
                    else
                    {
                        resolve(false)
                    }


                }
            });
        }
        catch (e)
        {
            console.log("ERROR"+e)
            resolve(false)
        }
    });

}
async function getCfdiByUUID(uuid,year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_cfdi_by_uuid.php",
                dataType: 'json',
                data: {"uuid": uuid.toString(),"year": year.toString()},
                success: function (result) {

                    let lista=[]
                    if (result.status === 0)
                    {
                        lista.push(result.cfdi)
                        resolve(lista)
                    }
                    else
                    {

                        resolve(lista)
                    }
                }
            });
        }
        catch (e)
        {
            console.log("ERROR"+e)
            resolve(false)
        }
    });

}
async function extraerDiotByIds(ids, year){
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_diot_by_ids.php",
                dataType: 'json',
                data: {"ids": ids.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.reportePago)
                    }
                    else
                    {
                        resolve(false)
                    }


                }
            });
        }
        catch (e)
        {
            console.log("ERROR"+e)
            resolve(false)
        }
    });

}

//IMPRIMIR
function imprimirVerCFDI(tabla){
    $("#tablaVerCfdiBody").html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;
        let cfdi=tabla[i]

        var id = "";
        if (cfdi.id)
            id = cfdi.id;
        var va = cfdi.serie;

        if(!va)
            va  = "";


        $("#tablaVerCfdiBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + id + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.fecha_emision + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.folio + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + va + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.nombre_emisor + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.nombre_receptor + "</td>" +
            "<td class='right-align' bgcolor='#D1F2EB'>" +  parseFloat(cfdi.total).formatMoney(2) + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.uuid + "</td>"+
            "</tr>"
        );

    }
    $("#msgDivVerCfdi").html("");


}
function imprimirVerReportePago(tabla){
    $("#tablaVerReportePagoBody").html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        $("#tablaVerReportePagoBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].uuid_factura + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].fecha_de_pago + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].rfc + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].id_cuenta + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + formatoNumerico(tabla[i].importe_de_factura) + "</td>" +
            "</tr>"
        );
    }
    $("#msgDivVerReportePago").html("");


}

//UTIL
function validarNumeroDecimal(valor){
    var RE = /^[+-]?[0-9]{1,15}(?:.[0-9]{1,2})?$/;

    if (RE.test(valor))
        return true;
    else
    {
        return false;
    }
}
function actualizar(){
    location.reload();
}
function formatoNumerico(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}