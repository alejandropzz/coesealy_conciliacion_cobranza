
let listaCfdis=[]

async function consultar(folio) {
    if(validarNumeroDecimal(folio)==false)
        return new Promise((resolve, reject) =>
        {
            alert("Folio incorrecto")
        });
    else
    {
        let grupoSaldos=await extraerGrupoFolio(folio)
        if(grupoSaldos==false)
        {
            alert("No se encontraron registros para ese folio")
            return false;
        }

        let year=await getYearGrupo(grupoSaldos)
        let cfdi=[];
        let factura=[];
        let cobranza=[];

        if(year.cfdi!=false && year.cfdi!=-1)
            cfdi=await getCfdiByFolio(folio,year.cfdi)


        if(year.factura!=false && year.factura!=-1)
            factura=await getFacturaByFolio(folio,year.factura)

        if(year.cobranza!=false && year.cobranza!=-1)
            {
                console.log("COBRANZA")
                cobranza=await extraerGrupoCobranza(folio,year.cobranza)
            }



        if(cfdi.length>0)
            imprimirVerCFDI(cfdi)
        else
            mostrarMensaje("blue", "SIN RESULTADOS", "msgDivVerCfdi");


        if(factura.length>0)
            imprimirVerCuentasCobrar(factura)
        else
            mostrarMensaje("blue", "SIN RESULTADOS", "msgDivVerCuentasCobrar");

        if(cobranza.length>0)
            imprimirVerReporteCobranza(cobranza)
        else
            mostrarMensaje("blue", "SIN RESULTADOS", "msgDivVerReporteCobranza");



/*
        let cfdi=await getCfdiByFolio(folio)
        let factura= await getFacturaByFolio(folio)
        console.log(cfdi)
        console.log(factura)

 */
    }





}
async function extraerGrupoFolio(folio) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/extraer_grupo_folio.php",
                dataType: 'json',
                data: {"folio": folio.toString()},
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
async function extraerAllFolios() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/get_all_folios_saldos.php",
                dataType: 'json',
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(result.folios)
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
async function extraerFoliosPendiente(fecha) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/get_folios_pendiente.php",
                dataType: 'json',
                data: {"fecha": fecha.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(result.folios)
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
async function extraerGrupoCobranza(folio, year) {

    let cobranza=[]
    if(year.length>0 &&folio!=false && folio!=-1)
    {
        let i;
        for(i=0;i<year.length;i++)
        {
            let cobranzaTemp=await extraerCobranzaByFolio(folio,year[i])
            if(cobranzaTemp!=false&&cobranzaTemp!=-1)
                cobranza=cobranza.concat(cobranzaTemp)
        }
    }






    return new Promise((resolve, reject) =>
    {
        resolve(cobranza)
    });

}
async function getYearGrupo(grupo) {

    let i;
    let cfdi=[];
    let factura=[];

    let jsonRes=
        {
            "cobranza":[],
            "factura":false,
            "cfdi":false,
        }

    for (i=0;i<grupo.length;i++)
    {
        if(grupo[i].id_cfdi!=-1 && cfdi.indexOf(grupo[i].id_cfdi)==-1)
            cfdi.push(grupo[i].id_cfdi)
        if(grupo[i].id_factura!=-1 && factura.indexOf(grupo[i].id_factura)==-1)
            factura.push(grupo[i].id_factura)
        jsonRes.cobranza.push(grupo[i].year)
    }


    if(cfdi.length>1)
        jsonRes.cfdi=false
    if(factura.length>1)
        jsonRes.factura=false


    if(cfdi.length==1)
        jsonRes.cfdi=parseInt(cfdi[0])
    if(factura.length==1)
        jsonRes.factura=parseInt(factura[0])


    if(cfdi.length==0)
            jsonRes.cfdi=-1;
    if(factura.length==0)
            jsonRes.factura=-1;

    if(jsonRes.cobranza.length==0)
        jsonRes.cobranza=false

    return new Promise((resolve, reject) =>
    {
        resolve(jsonRes)
    });
}
async function getCfdiByFolio(folio,year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_cfdi_by_folio.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"year": year.toString()},
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
async function getYearCfdiSaldos(folio) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/get_year_cfdi.php",
                dataType: 'json',
                data: {"folio": folio.toString()},
                success: function (result) {

                    let lista=[]
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
async function getFacturaByFolio(folio, year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_factura_by_folio.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"year": year.toString()},
                success: function (result) {

                    let lista=[]
                    if (result.status === 0)
                    {
                        lista.push(result.factura)
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
async function getYearFacturaSaldos(folio) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/get_year_factura.php",
                dataType: 'json',
                data: {"folio": folio.toString()},
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
async function extraerCobranzaByFolio(folio, year){
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_cobranza_by_folio.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"year": year.toString()},
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

async function getSumaPorYear(folio,year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/getSumYear.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {
                        let suma=result.suma
                        if(suma!=null)
                            resolve(parseFloat(suma))
                        else resolve(0)
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
async function getSumaPorMes(folio,mes, year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/getSumMes.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"mes": mes.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {
                        let suma=result.suma
                        if(suma!=null)
                            resolve(parseFloat(suma))
                        else resolve(0)
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
async function getTotalSaldo(folio) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/getTotalSaldo.php",
                dataType: 'json',
                data: {"folio": folio.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(result.total)
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

async function consultarPendiente(year, mes) {
    listaCfdis=[]

    let i;

    let fecha=year+"-"+mes+"-"+"01"
    let folios=await extraerFoliosPendiente(fecha)

    console.log(folios)
    for(i=0;i<50;i++)
    //for(i=0;i<folios.length;i++)
    {



        let validarPendiente=await validarSaldoPendiente(folios[i].folio, mes, year)
        if(validarPendiente!==false)
        {
            let cfdi=(await getCfdiByFolio(folios[i].folio, folios[i].cfdi))[0]
            cfdi["saldo"]=validarPendiente
            listaCfdis.push(cfdi)
            await imprimirTablaCfdis(listaCfdis, mes, year)

        }



        let j=i+1
        $("#progresoCfdiPendiente" ).html("Obteniendo Cobranza pendiente: "+j+" de "+folios.length)
    }

    if(listaCfdis.length>0)
    {
        await imprimirTablaCfdis(listaCfdis, mes, year)
    }
}
async function validarSaldoPendiente(folio, mes, year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/validarPendiente.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"mes": mes.toString(),"year": year.toString()},
                success: function (result) {
                    
                    if (result.status === 0)
                    {
                        resolve(result.obj)
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
async function imprimirTablaCfdis(tabla, mes, year){
    $("#tablaCfdiPendienteBody").html("")

    for (let i = 0; i < tabla.length; i++) {

        let folioString=tabla[i].folio.toString();
        if(folioString.length>6)
        {
            let folioTemp=folioString.substr(folioString.length-6, folioString.length-1)
            tabla[i].folio=folioTemp
        }

        let j=i+1;
        let cfdi=tabla[i]

        var id = "";
        if (cfdi.id)
            id = cfdi.id;
        var va = cfdi.serie;

        if(!va)
            va  = "";


        $("#tablaCfdiPendienteBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#ABEBC6 '>" + j + "</td>" +
            "<td class='left-align' bgcolor='#ABEBC6 '> <button id = 'button" + tabla[i].folio + "' class='btn btn-outline-secondary custom' type='button' onclick='mostrarArmado(" + tabla[i].folio + ","+mes+","+year+")'> Mostrar Armado </button> </td>" +
            "<td class='left-align' bgcolor='#ABEBC6 '>" + id + "</td>" +
            "<td class='left-align' bgcolor='#ABEBC6 '>" + cfdi.folio + "</td>" +
            "<td class='left-align' bgcolor='#ABEBC6 '>" + cfdi.fecha_emision + "</td>" +
            "<td class='right-align' bgcolor='#ABEBC6 '>" +  parseFloat(cfdi.total).formatMoney(2) + "</td>" +
            "<td class='right-align' bgcolor='#ABEBC6 '>" +  parseFloat(cfdi.saldo).formatMoney(2) + "</td>" +
            "<td class='left-align' bgcolor='#ABEBC6 '>" + cfdi.uuid + "</td>"+

            "</tr>"+
            "<tr id = 'rowForm" + tabla[i].folio + "'>" +
            "<td></td>"+
            "<td></td>"+
            "<td></td>"+
            "<td></td>"+
            "<td></td>"+
            "<td></td>"+
            "<td></td>"+
            "<td></td>"+
            "</tr>"

        );
        $("#rowForm"+tabla[i].folio).hide()

    }


    $("#msgDivCfdiPendiente").html("");



    if (!$.fn.dataTable.isDataTable('#tablaCfdiPendiente')) {
        $('#tablaCfdiPendiente').DataTable({ "pageLength": 25,
            "paging":   true,
            "ordering": false,
            "info":     false,
            "searching": false
        });
    }


    return new Promise((resolve, reject) =>
    {
        resolve(true)
    });




}

async function mostrarArmado(folio, mes, year) {
    console.log(folio,mes ,year)
    await crearTablaArmadoPendiente(folio)


    if(validarNumeroDecimal(folio)==false)
        return new Promise((resolve, reject) =>
        {
            alert("Folio incorrecto")
            resolve(false)
        });
    else
    {
        let grupoSaldos=await extraerGrupoFolioPendiente(folio, year)
        if(grupoSaldos==false)
        {
            let cfdi=[];
            let factura=[];

            let yearCfdi=await getYearCfdiSaldos(folio)
            let yearFactura=await getYearFacturaSaldos(folio)

            console.log("no hay bloque saldos")
            console.log(cfdi)
            console.log(factura)

            if(yearCfdi!=false && yearCfdi!=-1)
                cfdi=await getCfdiByFolio(folio,yearCfdi)
            if(yearFactura!=false && yearFactura!=-1)
                factura=await getFacturaByFolio(folio,yearFactura)


            if(cfdi.length>0)
                imprimirVerCFDIPendiente(cfdi,folio)
            else
                mostrarMensaje("blue", "SIN RESULTADOS", "msgDivVerCfdi"+folio);


            if(factura.length>0)
                imprimirVerCuentasCobrarPendiente(factura,folio)
            else
                mostrarMensaje("blue", "SIN RESULTADOS", "msgDivVerCuentasCobrar"+folio);


            mostrarMensaje("blue", "SIN RESULTADOS", "msgDivVerReporteCobranza"+folio);

        }
        else
        {
            let data=await getYearGrupo(grupoSaldos)
            let cfdi=[];
            let factura=[];
            let cobranza=[];

            if(data.cfdi!=false && data.cfdi!=-1)
                cfdi=await getCfdiByFolio(folio,data.cfdi)
            if(data.factura!=false && data.factura!=-1)
                factura=await getFacturaByFolio(folio,data.factura)

            if(data.cobranza!=false && data.cobranza!=-1)
            {
                console.log("COBRANZA")
                cobranza=await extraerGrupoCobranzaPendiente(folio, mes, year, data.cobranza)}



            if(cfdi.length>0)
                imprimirVerCFDIPendiente(cfdi,folio)
            else
                mostrarMensaje("blue", "SIN RESULTADOS", "msgDivVerCfdi"+folio);


            if(factura.length>0)
                imprimirVerCuentasCobrarPendiente(factura,folio)
            else
                mostrarMensaje("blue", "SIN RESULTADOS", "msgDivVerCuentasCobrar"+folio);

            if(cobranza.length>0)
                imprimirVerReporteCobranzaPendiente(cobranza,folio)
            else
                mostrarMensaje("blue", "SIN RESULTADOS", "msgDivVerReporteCobranza"+folio);
        }





    }


    return new Promise((resolve, reject) =>
    {
        resolve(true)
    });
}
async function extraerGrupoFolioPendiente(folio, year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/extraer_grupo_folio_pendiente.php",
                dataType: 'json',
                data: {"folio": folio.toString(), "year": year.toString()},
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
async function extraerGrupoCobranzaPendiente(folio, mes, year, years) {

    let cobranza=[]
    if(years.length>0 &&folio!=false && folio!=-1)
    {
        let i;
        for(i=0;i<years.length;i++)
        {
            if(years[i].toString()!=year)
            {
                let cobranzaTemp=await extraerCobranzaByFolio(folio,years[i])
                if(cobranzaTemp!=false&&cobranzaTemp!=-1)
                    cobranza=cobranza.concat(cobranzaTemp)
            }
            else
            {
                let cobranzaTemp=await extraerCobranzaByFolioPendiente(folio,years[i], mes)
                if(cobranzaTemp!=false&&cobranzaTemp!=-1)
                    cobranza=cobranza.concat(cobranzaTemp)
            }

        }
    }






    return new Promise((resolve, reject) =>
    {
        resolve(cobranza)
    });

}
async function extraerCobranzaByFolioPendiente(folio, year, mes){
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_cobranza_by_folio_pendiente.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"mes": mes.toString(),"year": year.toString()},
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

async function crearTablaArmadoPendiente(folio) {
    document.getElementById("button" + folio).disabled = true;


    $("#rowForm"+folio).show()
    $("#rowForm" + folio).html("");

    $("#rowForm" + folio).append(

        "<td colspan='10' style='text-align: background-color: #eeeeee; padding:20px;'>" +
            "<label id='labelCfdi"+folio+"'>Cfdi:</label>" +
        "    <div class='row'>" +
        "            <style>" +
        "                table{" +
        "                    font-size: 10pt;" +
        "                    line-height: 1;" +
        "" +
        "                }" +
        "                td{" +
        "                    padding: 2px !important;" +
        "                }" +
        "            </style>" +
        "            <table class='table' id='tablaVerCfdi"+folio+"'>" +
        "                <thead>" +
        "                <tr>" +
        "                    <th class='left-align'>#</th>" +
        "                    <th class='right-align'>ID</th>" +
        "                    <th>Fecha</th>" +
        "                    <th class='right-align'>Folio</th>" +
        "                    <th>Serie</th>" +
        "                    <th>Nombre Emisor</th>" +
        "                    <th>Nombre Receptor</th>" +
        "                    <th class='right-align'>Total</th>" +
        "                    <th>UUID</th>" +
        "                </tr>" +
        "                </thead>" +
        "                <tbody id='tablaVerCfdiBody"+folio+"'>" +
        "                </tbody>" +
        "            </table>" +
        "            <div id='msgDivVerCfdi"+folio+"'></div>" +
        "" +
        "    </div>" +
        "" +
        "    <label id='labelFactura"+folio+"'>Factura:</label>" +
        "    <div class='row'>" +
        "            <style>" +
        "                table{" +
        "                    font-size: 10pt;" +
        "                    line-height: 1;" +
        "" +
        "                }" +
        "                td{" +
        "                    padding: 2px !important;" +
        "                }" +
        "            </style>" +
        "            <table class='striped' id='tablaVerCuentasCobrar"+folio+"'>" +
        "                <thead>" +
        "                <tr>" +
        "                    <th class='left-align'>#</th>" +
        "                    <th class='right-align'>Number Folio</th>" +
        "                    <th class='left-align'>Customer Name</th>" +
        "                    <th class='left-align'>Date</th>" +
        "                    <th class='right-align'>Receivable</th>" +
        "                    <th class='left-align'>Tipo</th>" +
        "                    <th class='left-align'>UUID</th>" +
        "                    <th class='left-align'>Comentarios</th>" +
        "" +
        "                </tr>" +
        "                </thead>" +
        "                <tbody id='tablaVerCuentasCobrarBody"+folio+"'>" +
        "" +
        "                </tbody>" +
        "            </table>" +
        "            <div id='msgDivVerCuentasCobrar"+folio+"'></div>" +
        "" +
        "    </div>" +
        "" +
        "    <label id='labelCobranza"+folio+"'>Cobranza:</label>" +
        "    <div class='row'>" +
        "        <div class='col s12 white' style='min-height: 50px;' id='polizaContainerverReporteCobranza"+folio+"'>" +
        "            <style>" +
        "                table{" +
        "                    font-size: 10pt;" +
        "                    line-height: 1;" +
        "" +
        "                }" +
        "                td{" +
        "                    padding: 2px !important;" +
        "                }" +
        "            </style>" +
        "            <table class='striped' id='tablaVerReporteCobranza"+folio+"'>" +
        "                <thead>" +
        "                <tr>" +
        "                    <th class='left-align'>#</th>" +
        "                    <th class='right-align'>Folio</th>" +
        "                    <th class='left-align'>Invoice Date</th>" +
        "                    <th class='left-align'>Deposit Date</th>" +
        "                    <th class='left-align'>Check Number</th>" +
        "                    <th class='right-align'>Invoice Amount</th>" +
        "                    <th class='right-align'>Payment Amount</th>" +
        "                    <th class='right-align'>Invoice Balance</th>" +
        "                    <th class='right-align'>Age Days</th>" +
        "                    <th class='left-align'>Gl account</th>" +
        "                    <th class='left-align'>Comentarios</th>" +
        "                    <th class='right-align'>Cuenta</th>" +
        "                </tr>" +
        "                </thead>" +
        "                <tbody id='tablaVerReporteCobranzaBody"+folio+"'>" +
        "" +
        "                </tbody>" +
        "            </table>" +
        "            <div id='msgDivVerReporteCobranza"+folio+"'></div>" +
        "        </div>" +
        "<center>"+
            "<input  id = 'button_cerrar_" + folio + "' class='btn btn-outline-secondary custom' type='button' value = 'Cerrar' type = 'submit' onclick='cerrarVentana(`" + folio + "`)'>  </input>" +
        "</center>"+
        "    </div>"
    );
    return new Promise((resolve, reject) =>
    {
        resolve(true)
    });




}
function cerrarVentana(id) {
    $("#rowForm" + id).html("");
    document.getElementById("button" + id).disabled = false
}

//PARA LLENAR LAS TABLAS DEL ARMADO
function imprimirVerReporteCobranza(tabla){
    $("#tablaVerReporteCobranzaBody").html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        $("#tablaVerReporteCobranzaBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].folio + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].voice_date + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].deposit_date + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].check_number + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + formatoNumerico(tabla[i].invoice_amount) + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + formatoNumerico(tabla[i].payment_amount) + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + formatoNumerico(tabla[i].invoice_balance) + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].age_ays + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].gl_account + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].comments + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].id_cuenta + "</td>" +
            "</tr>"
        );
    }
    $("#msgDivVerReporteCobranza").html("");


}
function imprimirVerCuentasCobrar(tabla){
    $("#tablaVerCuentasCobrarBody").html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        let receivable=tabla[i].billing;

        $("#tablaVerCuentasCobrarBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].number_folio + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].customer_name + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].invoice + "</td>" +
            "<td class='right-align' bgcolor='#D1F2EB'>" + formatoNumerico(receivable) + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].tipo + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].UUID + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].comentarios + "</td>" +
            "</tr>"
        );
    }

    $("#msgDivVerCuentasCobrar").html("");


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

//PARA LLENAR LAS TABLAS DEL ARMADO PENDIENTE DINAMICAMENTE
function imprimirVerReporteCobranzaPendiente(tabla, folio){
    $("#tablaVerReporteCobranzaBody"+folio).html("")
    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;
        $("#tablaVerReporteCobranzaBody"+folio).append(
            "<tr>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + j + "</td>" +
            "<td class='right-align' bgcolor='#D1F2EB'>" + tabla[i].folio + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].voice_date + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].deposit_date + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].check_number + "</td>" +
            "<td class='right-align' bgcolor='#D1F2EB'>" + formatoNumerico(tabla[i].invoice_amount) + "</td>" +
            "<td class='right-align' bgcolor='#D1F2EB'>" + formatoNumerico(tabla[i].payment_amount) + "</td>" +
            "<td class='right-align' bgcolor='#D1F2EB'>" + formatoNumerico(tabla[i].invoice_balance) + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].age_ays + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].gl_account + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].comments + "</td>" +
            "<td class='right-align' bgcolor='#D1F2EB'>" + tabla[i].id_cuenta + "</td>" +
            "</tr>"
        );
    }
    $("#msgDivVerReporteCobranza"+folio).html("");


}
function imprimirVerCuentasCobrarPendiente(tabla, folio){
    $("#tablaVerCuentasCobrarBody"+folio).html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        let receivable=tabla[i].billing;

        $("#tablaVerCuentasCobrarBody"+folio).append(
            "<tr>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].number_folio + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].customer_name + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + tabla[i].invoice + "</td>" +
            "<td class='right-align' bgcolor='#D1F2EB'>" + formatoNumerico(receivable) + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].tipo + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].UUID + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + tabla[i].comentarios + "</td>" +
            "</tr>"
        );
    }

    $("#msgDivVerCuentasCobrar"+folio).html("");


}
function imprimirVerCFDIPendiente(tabla, folio){
    $("#tablaVerCfdiBody"+folio).html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;
        let cfdi=tabla[i]

        var id = "";
        if (cfdi.id)
            id = cfdi.id;
        var va = cfdi.serie;

        if(!va)
            va  = "";


        $("#tablaVerCfdiBody"+folio).append(
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
    $("#msgDivVerCfdi"+folio).html("");


}

function esconderTablasPendiente() {

}

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