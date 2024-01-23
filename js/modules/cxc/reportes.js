



async function getYears() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/comun/getYears.php",
                dataType: 'json',
                data: {},
                success: function (result) {
                    resolve(result)
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
async function armado_folio(folio)
{
    let yearsResult=await getYears()
    if(yearsResult.status)
    {
        let years=yearsResult.years

        let cfdi={}
        let factura={}
        let saldos=[]
        let cobranza=[]
        for(let i=0;i<years.length;i++)
        {
            let cfdiT=await getCfdiByFolio(folio,years[i].year)
            let facturaT=await getFacturaByFolio(folio,years[i].year)
            let saldosT=await getSaldoByFolio(folio,years[i].year)
            let cobranzaT=await getCobranzaByFolio(folio,years[i].year)

            if(cfdiT)
                cfdi=cfdiT
            if(facturaT)
                factura=facturaT
            if(saldosT) {
                saldosT.year=years[i].year;
                saldos.push(saldosT)
            }
            if(cobranzaT)
                cobranza=cobranza.concat(cobranzaT);

        }

        imprimirVerCFDI(cfdi)
        imprimirVerCuentasCobrar(factura)
        imprimirVerReporteCobranza(cobranza)
        console.log(cobranza)
    }
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
                    if (result.status === 0)
                        resolve(result.factura)
                    else
                        resolve(false)
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
async function getSaldoByFolio(folio, year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_saldo_by_folio.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"year": year.toString()},
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
async function getCobranzaByFolio(folio, year){
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

                        console.log("result.cobranza")
                        console.log(result.cobranza)
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
function imprimirVerCuentasCobrar(cobranza){

    if(!cobranza)
        return;

    $("#tablaVerCuentasCobrarBody").html("")
        let j=1;
        let receivable=cobranza.billing;
        $("#tablaVerCuentasCobrarBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cobranza.number_folio + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + cobranza.customer_name + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB' >" + cobranza.invoice + "</td>" +
            "<td class='right-align' bgcolor='#D1F2EB'>" + formatoNumerico(receivable) + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cobranza.tipo + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cobranza.UUID + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cobranza.comentarios + "</td>" +
            "</tr>"
        );
    $("#msgDivVerCuentasCobrar").html("");


}
function imprimirVerCFDI(cfdi){

    if(!cfdi)
        return;
    $("#tablaVerCfdiBody").html("")

        let j=1;
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
            "<td class='right-align' bgcolor='#D1F2EB'>" +  parseFloat(cfdi.total).formatMoney(2) + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.fecha_emision + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.folio + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + va + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.nombre_emisor + "</td>" +
            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.nombre_receptor + "</td>" +

            "<td class='left-align' bgcolor='#D1F2EB'>" + cfdi.uuid + "</td>"+
            "</tr>"
        );
    $("#msgDivVerCfdi").html("");


}

function actualizar(){
    location.reload();
}
function formatoNumerico(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}