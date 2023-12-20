let yearLocal=false
let eps=10;

let cfdiSeleccionado=null;
let pagosSeleccionados=null

function getCfdisNoLigadosPeriodo(mes, year) {
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/get_cfdis_no_ligados_periodo_year.php",
        dataType: 'json',
        type: 'POST',
        data: {"mes": mes.toString(), "year": year.toString()},
        success: function (result)
        {
            if (result.status === 0)
            {
                    imprimirCfdis(result.listaCFDI);
            }
            else
            {
                if(result.status===16)
                {
                    alert("NO HAY CFDIs SIN LIGAR")
                    actualizar()
                }

                else
                {
                    alert("OCURRIO UN ERROR")
                    actualizar()
                }
            }


        }
    });

}
function seleccionarCfdi(radio) {

    let cfdi=JSON.parse(radio.value)
    cfdiSeleccionado=cfdi
    imprimirCfdiSeleccionado()


}
function getPagosNoLigados() {
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/get_pagos_no_ligados.php",
        dataType: 'json',
        type: 'POST',
        success: function (result)
        {
            if (result.status === 0)
            {
                imprimirPagos(result.listaRP);
            }
            else
            {
                if(result.status===16)
                {
                    alert("NO HAY PAGOS SIN LIGAR")
                    actualizar()
                }

                else
                {
                    alert("OCURRIO UN ERROR")
                    actualizar()
                }
            }


        }
    });

}
function seleccionarPago(check) {
    let checkHtml=document.getElementById(check.id)
    let pago=JSON.parse(check.value)
    let id_local=pago.id_local

    if(pagosSeleccionados==null)
        pagosSeleccionados={}

    if(checkHtml.checked)
    {
        if(pagosSeleccionados.hasOwnProperty(id_local)==false)
        {
            pagosSeleccionados[id_local]={}
            pagosSeleccionados[id_local]=pago
        }
    }
    else
    {
        if(pagosSeleccionados.hasOwnProperty(id_local)==true)
            delete pagosSeleccionados[id_local]

    }
    imprimirPagosSeleccionados()

}
async function ligarPagosCfdi() {
    let banderaError=false
    if(cfdiSeleccionado!=null && pagosSeleccionados!=null)
    {

        let llaves=Object.keys(pagosSeleccionados)
        if(llaves.length>0)
        {
            console.log(cfdiSeleccionado)
            let objeto_year=await crearObjetoYear(pagosSeleccionados,llaves,cfdiSeleccionado)
            console.log("objeto_year")
            console.log(objeto_year)
            let years=objeto_year.years;

            let y;
            for(y=0;y<years.length;y++)
            {
                let year_actual=years[y]
                let ids_actuales=[]
                let j;

                let totalPagos=0
                for(j=0;j<objeto_year[year_actual].diot.length;j++)
                {
                    ids_actuales.push(objeto_year[year_actual].diot[j].id_rp)
                    totalPagos=totalPagos+parseFloat(objeto_year[year_actual].diot[j].importe_de_factura)
                }

                let saldo={
                    id:null,
                    uuid:cfdiSeleccionado.uuid,
                    actual:totalPagos,
                    total:cfdiSeleccionado.total,
                    year_cfdi:cfdiSeleccionado.year,
                    id_rp:ids_actuales,
                    fecha_emision:cfdiSeleccionado.fecha_emision,
                    fecha_cierre:"0001-01-01",
                    ultimo_pago:"0001-01-01",
                    status:-1,
                    year:year_actual,
                    uuid_year:cfdiSeleccionado.uuid+"_"+cfdiSeleccionado.year
                }


                for(j=0;j<objeto_year[year_actual].diot.length;j++)
                {
                    let fecha_actual = new Date(objeto_year[year_actual].diot[j].fecha_de_pago)
                    let ultimo_pago= new Date(saldo.ultimo_pago)
                    if(ultimo_pago<fecha_actual)
                        saldo.ultimo_pago=objeto_year[year_actual].diot[j].fecha_de_pago
                }
                if(Math.abs(saldo.total-saldo.actual)<eps)
                {
                    saldo.fecha_cierre=saldo.ultimo_pago;
                    saldo.status=2
                }
                else
                {
                    if(saldo.actual<saldo.total)
                        saldo.status=5
                    else
                        saldo.status=3
                }
                //FALTA ACTUALIZAR A MULTI ANUAL
                saldo.status=saldo.status*7
                let resultNS=await nuevoSaldo(saldo)
                let resultNSA=await nuevoSaldoAnual(saldo, year_actual)
                if(resultNS==true && resultNSA==true)
                {
                    let status_cfdi=2;
                    if(saldo.status==2)
                        status_cfdi=5;
                    let resultAC=await actualizarStatusCfdi(saldo.uuid,status_cfdi, cfdiSeleccionado.year)
                    let bCfdi=await borrarCfdi(saldo.uuid)
                    if(resultAC==false || bCfdi==false)
                        banderaError=true;


                    let i;
                    for(i=0;i<objeto_year[year_actual].diot.length;i++)
                    {
                        let resultAR=await removeStatusRp(objeto_year[year_actual].diot[i].uuid_rp,13)
                        let bDiot=await borrarDiot(objeto_year[year_actual].diot[i].uuid_rp)
                        if(bDiot==false || resultAR==false)
                            banderaError=true;
                    }
                }
                else
                    banderaError=true




            }
            if(banderaError==false)
                alert("Liga completada")
            else
                alert("Error al ligar")
            //alert("Total Pagos: "+formatoNumerico(totalPagos)+"  Total Cfdi: "+formatoNumerico(totalCfdi))
        }
        else
        {
            alert("Selecciona al menos un pago")

        }
    }

}
async function nuevoSaldo(saldo){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/nuevoSaldoRp.php",
            dataType: 'json',
            type: 'POST',
            data: {"data": JSON.stringify(saldo)},

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
async function nuevoSaldoAnual(saldo, year){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/nuevoSaldoRpAnual.php",
            dataType: 'json',
            type: 'POST',
            data: {"data": JSON.stringify(saldo), "year":JSON.stringify(year)},

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
async function actualizarStatusCfdi(uuid, status, year){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/agregar_status_cfdi_by_uuid.php",
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
async function borrarCfdi(uuid){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/borrar_cfdi.php",
            dataType: 'json',
            type: 'GET',
            data: {"uuid_cfdi": uuid},

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
async function borrarDiot(uuid_rp){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/borrar_diot.php",
            dataType: 'json',
            type: 'GET',
            data: {"uuid_rp": uuid_rp},

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
async function actualizarStatusRp(id, status){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/update_status_rp.php",
            dataType: 'json',
            type: 'GET',
            data: {"id": id, "status":status},

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
async function removeStatusRp(uuid_rp, status){
    return new Promise(function(resolve, reject) {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/remove_status_rp.php",
            dataType: 'json',
            type: 'GET',
            data: {"uuid_rp": uuid_rp, "status":status},

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
async function crearObjetoYear(diot, llaves, cfdi) {
    let obj={}
    obj["years"]=[]
    let i;
    for(i=0;i<llaves.length;i++)
    {
        let current_diot=diot[llaves[i]]

        if(current_diot.year)
        {
            if(obj.hasOwnProperty(current_diot.year))
            {
                obj[current_diot.year].diot.push(current_diot)
            }
            else
            {
                obj["years"].push(current_diot.year)
                obj[current_diot.year]=  {diot:[], cfdi:false}
                obj[current_diot.year].diot.push(current_diot)
            }
        }
    }

    if(obj.hasOwnProperty(cfdi.year))
    {
        obj[cfdi.year].cfdi=cfdi
    }
    else
    {
        obj["years"].push(cfdi.year)
        obj[cfdi.year]=  {diot:[], cfdi:false}
        obj[cfdi.year].cfdi=cfdi
    }

    return new Promise(function(resolve, reject) {
      resolve(obj)
    })

}

//IMPRIMIR
function imprimirCfdis(tabla){
    $("#tablaCfdisBody").html("");

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        let cfdiActual=JSON.stringify(tabla[i])

        let checked=""

        if(cfdiSeleccionado!=null)
        if(cfdiSeleccionado.uuid==tabla[i].uuid)
            checked="checked"
        $("#tablaCfdisBody").append(
            "<tr>"+
                "<td>"+
                    "<input type='radio' name='radiosCFDI' onclick='seleccionarCfdi(this)' value='"+cfdiActual+"' id='check_cfdi_"+tabla[i].uuid+"' "+checked+" />"+
                    "<label for='check_cfdi_"+tabla[i].uuid+"'></label>"+
                "</td>"+
                "<td>" + j + "</td>" +
                "<td>" + tabla[i].fecha_emision + "</td>" +
                "<td>" + tabla[i].rfc_emisor + "</td>" +
                "<td>" + formatoNumerico(tabla[i].total) + "</td>" +
                "<td>" + tabla[i].uuid + "</td>" +
            "</tr>"
        );
    }
}
function imprimirCfdiSeleccionado(){
    let tabla=[]
    tabla.push(cfdiSeleccionado)
    $("#tablaCfdiSeleccionadoBody").html("");

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        let cfdiActual=JSON.stringify(tabla[i])
        $("#tablaCfdiSeleccionadoBody").append(
            "<tr>"+
            "<td>" + j + "</td>" +
            "<td>" + tabla[i].fecha_emision + "</td>" +
            "<td>" + tabla[i].rfc_emisor + "</td>" +
            "<td>" + formatoNumerico(tabla[i].total) + "</td>" +
            "<td>" + tabla[i].uuid + "</td>" +
            "</tr>"
        );
    }
}
function imprimirPagos(tabla){
    $("#tablaPagosBody").html("");

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;
        let pagoActual=JSON.stringify(tabla[i])

        let checked=""
        if(pagosSeleccionados!=null)
            if(pagosSeleccionados.hasOwnProperty(tabla[i].id_local)==true)
                checked="checked"

        $("#tablaPagosBody").append(
            "<tr>" +
                "<td>"+
                    "<input type='checkbox' onclick='seleccionarPago(this)' value='"+pagoActual+"' id='check_pago_"+tabla[i].id_local+"'  "+checked+" />"+
                    "<label for='check_pago_"+tabla[i].id_local+"'></label>"+
                "</td>"+
                "<td>" + j + "</td>" +
                "<td>" + tabla[i].fecha_de_pago + "</td>" +
                "<td>" + tabla[i].no_proveedor + "</td>" +
                "<td>" + tabla[i].rfc + "</td>" +
                "<td>" + tabla[i].nombre_del_proveedor + "</td>" +
                "<td>" + tabla[i].numero_de_factura + "</td>" +
                "<td>" + formatoNumerico(tabla[i].importe_de_factura) + "</td>" +
                "<td>" + formatoNumerico(tabla[i].importe_de_pago) + "</td>" +
                "<td>" + formatoNumerico(tabla[i].traspasos) + "</td>" +
                "<td>" + formatoNumerico(tabla[i].base_de_iva) + "</td>" +
                "<td>" + formatoNumerico(tabla[i].iva_16) + "</td>" +
                "<td>" + tabla[i].uuid_factura + "</td>" +
            "</tr>"
        );


    }
}
function imprimirPagosSeleccionados(){


    let tabla=[]
    let llaves=Object.keys(pagosSeleccionados)
    let k;
    for(k=0;k<llaves.length;k++)
    {
        tabla.push(pagosSeleccionados[llaves[k]])
    }


    $("#tablaPagoSeleccionadoBody").html("");

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;
        $("#tablaPagoSeleccionadoBody").append(
            "<tr>" +
            "<td>" + j + "</td>" +
            "<td>" + tabla[i].fecha_de_pago + "</td>" +
            "<td>" + tabla[i].no_proveedor + "</td>" +
            "<td>" + tabla[i].rfc + "</td>" +
            "<td>" + tabla[i].nombre_del_proveedor + "</td>" +
            "<td>" + tabla[i].numero_de_factura + "</td>" +
            "<td>" + formatoNumerico(tabla[i].importe_de_factura) + "</td>" +
            "<td>" + formatoNumerico(tabla[i].importe_de_pago) + "</td>" +
            "<td>" + formatoNumerico(tabla[i].traspasos) + "</td>" +
            "<td>" + formatoNumerico(tabla[i].base_de_iva) + "</td>" +
            "<td>" + formatoNumerico(tabla[i].iva_16) + "</td>" +
            "<td>" + tabla[i].uuid_factura + "</td>" +
            "</tr>"
        );


    }
}
//UTIL
function ocultarPagos() {
    $("#tablaPagosBody").html("");
}
function ocultarCfdis() {
    $("#tablaCfdisBody").html("");
}

function formatoNumerico(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function actualizar(){
    location.reload();
}