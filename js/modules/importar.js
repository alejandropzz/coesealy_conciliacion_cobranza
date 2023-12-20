let year;
let tipoBusqueda=0;
//EXTRAER Y SUBIR CFDI
//CXC





async function importarCFDI(yearLocal) {
    year=yearLocal
    try
    {
        let folios=await extraerSaldoCfdiNoLigado()
        console.log(folios)
        if(folios==false)
        {
            imprimirErrorCfdi("No se pudieron extraer los CFDIs no ligados de CXC")
            return
        }
        let i;
        for(i=0;i<folios.length;i++)
        {
            //HACER LA CARGA DE LOS SALDOS


            /*
            let scfdi=await subirCfdi(cfdi, yearLocal)
            if(!scfdi)
                imprimirErrorCfdi("ERROR AL SUBIR EL CFDI CON ID: "+ids[i].id)
            let j=i+1
            $("#progresoImportarCFDI" ).html("CFDIs Subidos "+j+" de "+ids.length)

             */
        }


    }
    catch (e) {
        console.log("ERROR",e)
    }

}
async function extraerSaldoCfdiNoLigado() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_saldo_cfdi_no_ligado.php",
                dataType: 'json',
                data: {"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.ids)
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
//CXP
async function importarCFDICXP(yearLocal) {
    year=yearLocal
    try
    {
        let ids=await extraerIdCfdiNoLigadoCXP()
        if(ids==false)
        {
            imprimirErrorCfdi("No se pudieron extraer los CFDIs no ligados para CXP")
            return
        }
        let i;
        for(i=0;i<ids.length;i++)
        {
            let cfdi=await extraerCfdiById(ids[i].id)
            if(!cfdi)
                imprimirErrorCfdi("ERROR AL CARGAR EL CFDI CON ID: "+ids[i].id)
            let scfdi=await subirCfdi(cfdi, yearLocal)
            let resS=await actualizarStatusCfdiCxp(cfdi.uuid,3, yearLocal)
            resS=await actualizarStatusCfdiCxp(cfdi.uuid,3, false)
            if(!scfdi)
                imprimirErrorCfdi("ERROR AL SUBIR EL CFDI CON ID: "+ids[i].id)
            let j=i+1
            $("#progresoImportarCFDI" ).html("CFDIs Subidos "+j+" de "+ids.length)


        }


    }
    catch (e) {
        console.log("ERROR",e)
    }

}
async function extraerIdCfdiNoLigadoCXP() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_id_cfdi_no_ligado_cxp.php",
                dataType: 'json',
                data: {"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.ids)
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
//UTIL
async function extraerCfdiById(id) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_cfdi_by_id.php",
                dataType: 'json',
                data: {"id": id.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.cfdi)
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
async function subirCfdi(cfdi, year){

    cfdi["year"]=year
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/subir/subirCfdi.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(cfdi)
                },
                success: function (result) {
                    console.log(result)

                    if (result.status === 0)
                    {
                        resolve(true)
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
function obtenerCFDI($period, $year){
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/getCFDIs.php",
        dataType: 'json',
        data: {"period": $period, "year": $year},

        success: function (result) {
            if (result.status === 0)
            {



                setTimeout(function ()
                {
                    console.log(result.cfdis)
                    $("#tablaVerCfdiBody").html("");
                    imprimirVerCFDI(result.cfdis);
                }, 500);


            }
            else
            {
                if(result.status===16)
                {
                    $("#tablaVerCfdiBody").html("");
                    mostrarMensaje("blue", "" + result.message, "msgDivVerCfdi");
                }

                else
                {
                    $("#tablaVerCfdiBody").html("");
                    mostrarMensaje("red", "Ocurrio un error al consultar los estados de cuenta: " + result.message, "msgDivVerCfdi");
                }
            }

        }
    });
}
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
            "<td>" + j + "</td>" +
            "<td>" + id + "</td>" +
            "<td>" + cfdi.fecha_emision + "</td>" +
            "<td>" + cfdi.folio + "</td>" +
            "<td>" + va + "</td>" +
            "<td>" + cfdi.nombre_emisor + "</td>" +
            "<td>" + cfdi.nombre_receptor + "</td>" +
            "<td class='right-align'>" +  parseFloat(cfdi.total).formatMoney(2) + "</td>" +
            "<td class='right-align'>" + cfdi.uuid + "</td>"+
            "</tr>"
        );

    }
    //$('#tablaVerCfdi').DataTable({"pageLength": 3000});

    $("#msgDivVerCfdi").html("");


}
async function actualizarStatusCfdiCxp(uuid, status, year){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/update_status_ligadorp_by_uuid.php",
            dataType: 'json',
            type: 'GET',
            data: {"uuid_cfdi": uuid, "status":status, "year":year},

            success: function (result)
            {
                if (result.status === 0)
                {
                    resolve(true)
                }
                else
                {
                    resolve(false);
                }
            }
        });

    });
}

//EXTRAER Y SUBIR FACTURAS
async function importarFacturas(yearLocal) {
    year=yearLocal
    try
    {
        let ids=await extraerIdFacturasNoLigadas()
        if(ids==false)
        {
            imprimirErrorFactura("No se pudieron extraer las Facturas no ligadas")
            return
        }

        let i;
        for(i=0;i<ids.length;i++)
        {
            let factura=await extraerFacturaById(ids[i].id)
            if(!factura)
                imprimirErrorFactura("ERROR AL CARGAR LA FACTURA CON ID: "+ids[i].id)
            else
                factura["year"]=year;
            let sf=await subirFactura(factura)
            if(!sf)
                imprimirErrorFactura("ERROR AL SUBIR LA FACTURA CON ID: "+ids[i].id)
            else
            {
                let j=i+1
                $("#progresoImportarFacturas" ).html("Facturas Subidas "+j+" de "+ids.length)
            }


        }




    }
    catch (e) {
        console.log("ERROR",e)
    }

}
async function extraerIdFacturasNoLigadas() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_id_facturas_no_ligadas.php",
                dataType: 'json',
                data: {"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.ids)
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
async function extraerFacturaById(id) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_factura_by_id.php",
                dataType: 'json',
                data: {"id": id.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.factura)
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
async function subirFactura(factura){

    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/subir/subirFactura.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(factura)
                },
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(true)
                    }
                    else
                    {
                        console.log(result)
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
function obtenerCuentasCobrar($period, $year){
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/getCuentasCobrar.php",
        dataType: 'json',
        data: {"period": $period, "year": $year},

        success: function (result) {
            console.log(result)
            if (result.status === 0)
            {
                setTimeout(function ()
                {
                    $("#tablaVerEstadoCuentaBody").html("");
                    imprimirVerCuentasCobrar(result.listaCC);
                }, 500);
            }
            else
            {
                if(result.status===16)
                {
                    $("#tablaVerCuentasCobrarBody").html("");
                    mostrarMensaje("blue", "" + result.message, "msgDivVerCuentasCobrar");
                }

                else
                {
                    $("#tablaVerCuentasCobrarBody").html("");
                    mostrarMensaje("red", "Ocurrio un error al consultar los estados de cuenta: " + result.message, "msgDivVerCuentasCobrar");
                }
            }

        }
    });
}
function imprimirVerCuentasCobrar(tabla){
    $("#tablaVerCuentasCobrarBody").html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        $("#tablaVerCuentasCobrarBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].number_folio + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + tabla[i].customer_name + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + tabla[i].invoice + "</td>" +
            "<td class='right-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].net_sales) + "</td>" +
            "<td class='right-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].tax_amount) + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].tipo + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].UUID + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].comentarios + "</td>" +
            "</tr>"
        );
    }
    $('#tablaVerCuentasCobrar').DataTable({"pageLength": 1001});

    $("#msgDivVerCuentasCobrar").html("");


}

//EXTRAER Y SUBIR DIOT CXP
async function importarDiotCXP(yearLocal) {
    year=yearLocal
    try
    {
        let ids=await extraerIdDiotNoLigadasCXP()
        console.log(ids)
        if(ids==false)
        {
            imprimirErrorDiotCXP("No se pudo extraer el DIOT no ligado")
            return
        }

        let i;
        for(i=0;i<ids.length;i++)
        {
            let diot=await extraerDiotByIdCXP(ids[i].id)
            if(!diot)
                imprimirErrorDiotCXP("No se pudo extraer el pago con id: "+ids[i].id)
            else
                diot["year"]=year;


            let sd=await subirDiotCXP(diot)
            if(!sd)
                imprimirErrorDiotCXP("No se pudo subir el registro con id: "+ids[i].id)
            else
            {
                await actualizarStatusUuidRp(ids[i].uuid_rp,11,yearLocal)
                await actualizarStatusUuidRp(ids[i].uuid_rp,11,false)
                let j=i+1
                $("#progresoImportarDiotCXP" ).html("Diot Subido "+j+" de "+ids.length)
            }

        }
    }
    catch (e) {
        console.log("ERROR",e)
    }

}
async function extraerIdDiotNoLigadasCXP() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_id_diot_no_ligadas_cxp.php",
                dataType: 'json',
                data: {"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.ids)
                    }
                    else
                    {
                        if(result.status===1)
                            imprimirErrorDiotCXP("No hay diots para extraer")
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
async function extraerDiotByIdCXP(id) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_diot_by_id.php",
                dataType: 'json',
                data: {"id": id.toString(),"year": year.toString()},
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
async function subirDiotCXP(pago){
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/subir/subirDiot.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(pago)
                },
                success: function (result)
                {
                    if (result.status === 0)
                    {
                        resolve(true)
                    }
                    else
                    {
                        console.log(result)
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
function obtenerCuentasPagar($period, $year){
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/getCuentasCobrar.php",
        dataType: 'json',
        data: {"period": $period, "year": $year},

        success: function (result) {
            console.log(result)
            if (result.status === 0)
            {
                setTimeout(function ()
                {
                    $("#tablaVerEstadoCuentaBody").html("");
                    imprimirVerCuentasCobrar(result.listaCC);
                }, 500);
            }
            else
            {
                if(result.status===16)
                {
                    $("#tablaVerCuentasCobrarBody").html("");
                    mostrarMensaje("blue", "" + result.message, "msgDivVerCuentasCobrar");
                }

                else
                {
                    $("#tablaVerCuentasCobrarBody").html("");
                    mostrarMensaje("red", "Ocurrio un error al consultar los estados de cuenta: " + result.message, "msgDivVerCuentasCobrar");
                }
            }

        }
    });
}
function imprimirVerCuentasPagar(tabla){
    $("#tablaVerCuentasCobrarBody").html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        $("#tablaVerCuentasCobrarBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].number_folio + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + tabla[i].customer_name + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + tabla[i].invoice + "</td>" +
            "<td class='right-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].net_sales) + "</td>" +
            "<td class='right-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].tax_amount) + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].tipo + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].UUID + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].comentarios + "</td>" +
            "</tr>"
        );
    }
    $('#tablaVerCuentasCobrar').DataTable({"pageLength": 1001});

    $("#msgDivVerCuentasCobrar").html("");


}
async function actualizarStatusRp(id, status, year){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/update_status_rp.php",
            dataType: 'json',
            type: 'GET',
            data: {"id": id, "status":status, "year":year},

            success: function (result)
            {
                if (result.status === 0)
                {
                    resolve(true)
                }
                else
                {
                    resolve(false);
                }
            }
        });

    });
}
async function actualizarStatusUuidRp(uuid_rp, status, year){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/update_status_uuid_rp.php",
            dataType: 'json',
            type: 'GET',
            data: {"uuid_rp": uuid_rp, "status":status, "year":year},

            success: function (result)
            {
                if (result.status === 0)
                {
                    resolve(true)
                }
                else
                {
                    resolve(false);
                }
            }
        });

    });
}
async function removerStatusRp(id, status, year){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/remove_status_rp.php",
            dataType: 'json',
            type: 'GET',
            data: {"id": id, "status":status, "year":year},

            success: function (result)
            {
                if (result.status === 0)
                {
                    resolve(true)
                }
                else
                {
                    resolve(false);
                }
            }
        });

    });
}




//EXTRAER ESTADO DE CUENTA
async function importarEstadoCuenta(yearLocal) {
    year=yearLocal
    try
    {
        let ids=await extraerIdEstadoCuenta()
        console.log(ids)
        if(ids==false)
        {
            imprimirErrorEstadoCuenta("No se pudieron extraer los Estados de cuenta")
            return
        }
        let i;
        for(i=0;i<ids.length;i++)
        {
            let edc=await extraerEstadoCuentaById(ids[i])
            if(!edc)
                imprimirErrorEstadoCuenta("No se pudo extraer el registro con id: "+ids[i] )
            else
            {
                edc["year"]=year;
                let uuid=edc["depositos"].toString()+edc["fecha"].toString()+edc["id_compa"].toString()+edc["id_cuenta"].toString()+edc["id_edc"].toString()+edc["retiros"].toString()+edc["saldo"].toString()+edc["year"].toString()
                edc["uuid"]=uuid;
            }



            let sedc=await subirEstadoCuenta(edc)
            if(!sedc)
                imprimirErrorEstadoCuenta("No se pudo subir el registro con id: "+ids[i] )
            else
            {
                let j=i+1
                $("#progresoImportarEdc" ).html("Estados de cuenta subidos: "+j+" de "+ids.length)
            }

        }
    }
    catch (e) {
        console.log("ERROR",e)
    }

}
async function extraerIdEstadoCuenta() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_id_edc.php",
                dataType: 'json',
                data: {"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.ids)
                    }
                    else
                    {
                        console.log(result.status)
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
async function extraerEstadoCuentaById(id) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_edc_by_id.php",
                dataType: 'json',
                data: {"id": id.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.edc)
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
async function subirEstadoCuenta(cfdi){

    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/subir/subirEstadoCuenta.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(cfdi)
                },
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(true)
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
function obtenerEstadoCuenta($period, $year, $idCuenta){
    $.ajax({
        url: "/coesealy-coesealy-conciliacion-cobranza/backend/contabilidad/api/getECB.php",
        dataType: 'json',
        data: {"period": $period, "year": $year, "idCuenta":$idCuenta},

        success: function (result) {
            if (result.status === 0) {
                setTimeout(function () {
                    $("#tablaVerEstadoCuentaBody").html("");
                    imprimirVerEstadoCuenta(result.listaEC);
                }, 500);
            } else {
                if(result.status===16)
                {
                    $("#tablaVerEstadoCuentaBody").html("");
                    mostrarMensaje("blue", "" + result.message, "msgDivVerEstadoCuenta");
                }

                else
                {
                    $("#tablaVerEstadoCuentaBody").html("");
                    mostrarMensaje("red", "Ocurrio un error al consultar los estados de cuenta: " + result.message, "msgDivVerEstadoCuenta");
                }
            }

        }
    });
}
function imprimirVerEstadoCuenta(tabla){


    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;
        let idCompa="";
        if(tabla[0].idCompa==1)
            idCompa="SEALY COLCHONES"
        if(tabla[0].idCompa==2)
            idCompa="SEALY SERVICIOS"
        if(tabla[0].idCompa==3)
            idCompa="TEMPUR SEALY"
        $("#tablaVerEstadoCuentaBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].idCuenta + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + idCompa + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + tabla[i].fecha + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].descripcion + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].depositos + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].retiros + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].saldo + "</td>" +
            "</tr>"
        );
    }


    $("#msgDivVerEstadoCuenta").html("");
}

//EXTRAER Y SUBIR COBRANZA
async function importarCobranza(yearLocal) {
    year=yearLocal
    try
    {
        let jsonResolve=await extraerIdCobranzaNoLigada()
        let idsCobranza=jsonResolve.idsCobranza
        console.log(idsCobranza)
        if(idsCobranza==false)
        {
            imprimirErrorCobranza("No se pudieron extraer la cobranza no ligadas")
            return
        }

        console.log(idsCobranza)

        let i;

        for(i=0;i<idsCobranza.length;i++)
        {
            let cobranza=await extraerCobranzaById(idsCobranza[i].id)
            if(!cobranza)
                imprimirErrorCobranza("No se pudo extraer el cobro con id: "+idsCobranza[i].id)
            else
                cobranza["year"]=year;

            let sc=await subirCobranza(cobranza)
            if(!sc)
                imprimirErrorCobranza("No se pudo subir el cobro con id: "+idsCobranza[i].id)
            else
            {
                let j=i+1
                $("#progresoImportarCobranza" ).html("Cobranza Subidos "+j+" de "+idsCobranza.length)
            }

        }






    }
    catch (e) {
        console.log("ERROR",e)
    }

}
async function extraerIdCobranzaNoLigada() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_id_cobranza_no_ligada.php",
                dataType: 'json',
                data: {"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        let jsonResolve=
                            {
                                "idsCobranza":result.idsCobranza
                            }

                        resolve(jsonResolve)
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
async function extraerCobranzaById(id) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_cobranza_by_id.php",
                dataType: 'json',
                data: {"id": id.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.cobranza)
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
async function subirCobranza(cobranza){

    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/subir/subirCobranza.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(cobranza)
                },
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(true)
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
function obtenerReporteCobranza($period, $year){
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/getReporteCobranza.php",
        dataType: 'json',
        data: {"period": $period, "year": $year, "tipo":tipoBusqueda},

        success: function (result) {
            console.log(result)
            if (result.status === 0)
            {
                setTimeout(function ()
                {
                    $("#tablaVerEstadoCuentaBody").html("");
                    console.log(result.listaRC)
                    imprimirVerReporteCobranza(result.listaRC);
                }, 500);
            }
            else
            {
                if(result.status===16)
                {
                    $("#tablaVerCuentasCobrarBody").html("");
                    mostrarMensaje("blue", "" + result.message, "msgDivVerReporteCobranza");
                }

                else
                {
                    $("#tablaVerReporteCobranzaBody").html("");
                    mostrarMensaje("red", "Ocurrio un error al consultar los estados de cuenta: " + result.message, "msgDivVerReporteCobranza");
                }
            }

        }
    });
}
function imprimirVerReporteCobranza(tabla){
    $("#tablaVerReporteCobranzaBody").html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        $("#tablaVerReporteCobranzaBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].folio + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + tabla[i].voice_date + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + tabla[i].deposit_date + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].check_number + "</td>" +
            "<td class='right-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].invoice_amount) + "</td>" +
            "<td class='right-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].payment_amount) + "</td>" +
            "<td class='right-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].invoice_balance) + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].age_ays + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].gl_account + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].comentarios + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].id_cuenta + "</td>" +
            "</tr>"
        );
    }
    $('#tablaVerReporteCobranza').DataTable({"pageLength": 1001});

    $("#msgDivVerReporteCobranza").html("");


}
function tipo_busqueda(tipo) {
    tipoBusqueda=tipo
}

//EXTRAER Y SUBIR SALDOS
//CXC
async function importarSaldos(yearLocal) {
    year=yearLocal
    try
    {
        let jsonResolve=await extraerIdSaldosNoCompleto()
        let idsSaldos=jsonResolve.idsSaldos

        if(idsSaldos==false)
        {
            imprimirErrorSaldos("No se pudieron extraer los saldos")
            return
        }
        console.log(idsSaldos)

        let i;
        for(i=0;i<idsSaldos.length;i++)
        {
            let saldo=await extraerSaldoById(idsSaldos[i])
            if(!saldo)
                imprimirErrorSaldos("No se pudo extraer el saldo con id: "+idsSaldos[i])
            else
                saldo["year"]=year;

            let ss=await subirSaldo(saldo)
            if(!ss)
                imprimirErrorSaldos("No se pudo subir el saldo con id: "+idsSaldos[i])
            else
            {
                let j=i+1
                $("#progresoImportarSaldo" ).html("Saldos Subidos "+j+" de "+idsSaldos.length)
            }

        }

    }
    catch (e) {
        console.log("ERROR",e)
    }

}
async function extraerIdSaldosNoCompleto() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_id_saldos_no_completo.php",
                dataType: 'json',
                data: {"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        let jsonResolve=
                            {
                                "idsSaldos":result.ids
                            }

                        resolve(jsonResolve)
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
async function extraerSaldoById(id) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_saldo_by_id.php",
                dataType: 'json',
                data: {"id": id.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.saldo)
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
async function subirSaldo(saldo){
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/subir/subirSaldo.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(saldo)
                },
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(true)
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
//CXP
async function importarSaldosCxp(yearLocal) {
    year=yearLocal
    try
    {
        let jsonResolve=await extraerIdSaldosNoCompletoCxp()
        let idsSaldos=jsonResolve.idsSaldos

        if(idsSaldos==false)
        {
            imprimirErrorSaldos("No se pudieron extraer los saldos")
            return
        }

        let i;
        for(i=0;i<idsSaldos.length;i++)
        {
            let saldo=await extraerSaldoByUuidCxp(idsSaldos[i])
            if(!saldo)
                imprimirErrorSaldos("No se pudo extraer el saldo con id: "+idsSaldos[i])
            else
                saldo["year"]=year;

            let ss=await subirSaldoCxp(saldo)
            if(!ss)
                imprimirErrorSaldos("No se pudo subir el saldo con id: "+idsSaldos[i])
            else
            {
                await agregarStatusSaldoRp(idsSaldos[i],7,yearLocal)
                await agregarStatusSaldoRpUuidYear(idsSaldos[i]+"_"+yearLocal,7,false)
                let j=i+1
                $("#progresoImportarSaldo" ).html("Saldos Subidos "+j+" de "+idsSaldos.length)
            }

        }

    }
    catch (e) {
        console.log("ERROR",e)
    }

}
async function extraerIdSaldosNoCompletoCxp() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_id_saldos_no_completo_cxp.php",
                dataType: 'json',
                data: {"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        let jsonResolve=
                            {
                                "idsSaldos":result.ids
                            }

                        resolve(jsonResolve)
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
async function extraerSaldoByUuidCxp(uuid) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_saldo_by_uuid_cxp.php",
                dataType: 'json',
                data: {"uuid": uuid.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {

                        resolve(result.saldo)
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
async function subirSaldoCxp(saldo){
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/subir/subirSaldoRp.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(saldo)
                },
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(true)
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
async function agregarStatusSaldoRp(uuid, status, year){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/agregar_estatus_saldo_rp.php",
            dataType: 'json',
            type: 'GET',
            data: {"uuid": uuid, "status":status, "year":year},

            success: function (result)
            {
                if (result.status === 0)
                {
                    resolve(true)
                }
                else
                {
                    resolve(false);
                }
            }
        });

    });
}
async function agregarStatusSaldoRpUuidYear(uuid_year, status, year){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/agregar_estatus_saldo_rp_uuid_year.php",
            dataType: 'json',
            type: 'GET',
            data: {"uuid_year": uuid_year, "status":status, "year":year},

            success: function (result)
            {
                if (result.status === 0)
                {
                    resolve(true)
                }
                else
                {
                    resolve(false);
                }
            }
        });

    });
}
async function removerStatusSaldoRp(id, status, year){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/remove_status_rp.php",
            dataType: 'json',
            type: 'GET',
            data: {"id": id, "status":status, "year":year},

            success: function (result)
            {
                if (result.status === 0)
                {
                    resolve(true)
                }
                else
                {
                    resolve(false);
                }
            }
        });

    });
}


//IMPRIME UN ERROR EN LA CONSOLA
function imprimirErrorCfdi(err,color){
    if(!color)
        color="#FFFFFF"
    $("#tablaImportarCFDIBody").append(
        "<tr>" +
        "<td class='left-align' bgcolor='"+color+"'>" + err + "</td>" +
        "</tr>"
    );
}
function imprimirErrorFactura(err,color){
    if(!color)
        color="#FFFFFF"
    $("#tablaImportarFacturasBody").append(
        "<tr>" +
        "<td class='left-align' bgcolor='"+color+"'>" + err + "</td>" +
        "</tr>"
    );
}
function imprimirErrorDiotCXP(err,color){
    if(!color)
        color="#FFFFFF"
    $("#tablaImportarDiotCXPBody").append(
        "<tr>" +
        "<td class='left-align' bgcolor='"+color+"'>" + err + "</td>" +
        "</tr>"
    );
}
function imprimirErrorCobranza(err,color){
    if(!color)
        color="#FFFFFF"
    $("#tablaImportarCobranzaBody").append(
        "<tr>" +
        "<td class='left-align' bgcolor='"+color+"'>" + err + "</td>" +
        "</tr>"
    );
}
function imprimirErrorSaldos(err,color){
    if(!color)
        color="#FFFFFF"
    $("#tablaImportarSaldoBody").append(
        "<tr>" +
        "<td class='left-align' bgcolor='"+color+"'>" + err + "</td>" +
        "</tr>"
    );
}
function imprimirErrorEstadoCuenta(err,color){
    if(!color)
        color="#FFFFFF"
    $("#tablaImportarEdcBody").append(
        "<tr>" +
        "<td class='left-align' bgcolor='"+color+"'>" + err + "</td>" +
        "</tr>"
    );
}
//UTIL
function formatoNumerico(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function actualizar(){
    location.reload();
}