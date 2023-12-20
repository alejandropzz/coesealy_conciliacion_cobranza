//BY PZZs
var excelRows = [];
var totalValues   =[];
var idCuenta="";
var eps=0.1;
const rfc_sealy=["SCM991217AU7","SSM991217R47","SMM950911V10"]
let validadosCFDI=0;
let validadosGL=0;
let periodo;
let banderaError=false;
let indicesError=[]
let tipoBusqueda=1


//SE CREA EL EXCELROWS
$(document).on('change', ':file', function(){
    $("#tablaEstadoCuentaBody").html("");
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g,'/').replace(/.*\//,'');
    periodo =label.split("_")[2];
    if (label.split("_").length==4 && label.split("_")[0]=="Facturas" && label.split("_")[1]=="CXC") {
        input.trigger('fileselect', [numFiles, label]);
        var file = input.get(0).files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            arr = reader.result.split(/\r\n|\r|\n/g);
            var headersNeeded = [
                'Number Folio',
                'Number',
                'Customer Name',
                'Customer PO',
                'Invoice',
                'List Sales',
                'Discount',
                'Net Sales',
                'Tax Amount',
                'Freight',
                'Charges',
                'Billing',
                'Importe XML',
                'Comprobacion',
                'Gross Sales',
                'Diff Sales',
                'Financial',
                'diff',
                'Diferencia',
                'Tipo',
                'Type',
                'Estado del SAT',
                'In Transit',
                'UUID',
                'Fecha Timbrado'
            ];
            var headers = arr[0].split('\t');
            for (var i = 1; i < arr.length; i++) {
                var data = arr[i].split('\t');
                var obj = {};
                let finExcel=false;
                let finLectura=false
                for (var j = 0; j < data.length; j++) {
                    if (headers[j] && data[j])
                    {
                        if(data[j]==" -   ")
                            data[j]=""
                        if(data[j]=="-")
                            data[j]=""
                        if(data[j]=="#N/A")
                        {
                            if(headers[j]!="diff" && headers[j]!="Diferencia" && headers[j]!="Tipo" && headers[j]!="Type" && headers[j]!="Estado del SAT" && headers[j]!="In Transit" && headers[j]=="UUID"&& headers[j]=="Fecha Timbrado")
                            {
                                data[j]=0
                            }
                        }
                        obj[headers[j].trim()] = data[j].trim();
                        finLectura=true;
                    }
                    else
                    {
                        obj[headers[j].trim()] = "";
                        if(headers[j].trim()=="Invoice")
                        {
                            finExcel=true;
                        }
                    }
                }
                if (finLectura && !finExcel)
                {
                    var cleanedObj = {};
                    headersNeeded.forEach(function (header) {
                        cleanedObj[header.trim().replace(/ /g, '')] = obj[header.trim()];
                    });
                    var namedObj = {};
                    namedObj.billing=((cleanedObj['Billing']=="") ? 0 : parseFloat(cleanedObj['Billing']))
                    namedObj.charges=((cleanedObj['Charges']=="") ? 0 : parseFloat(cleanedObj['Charges']))
                    namedObj.comprobacion=((cleanedObj['Comprobacion']=="") ? 0 : parseFloat(cleanedObj['Comprobacion']))
                    namedObj.customer_name=cleanedObj['CustomerName']
                    namedObj.customer_po=cleanedObj['CustomerPO']
                    namedObj.diferencia=cleanedObj['Diferencia']
                    namedObj.diff=cleanedObj['diff']
                    namedObj.diff_sales=((cleanedObj['DiffSales']=="") ? 0 : parseFloat(cleanedObj['DiffSales']))
                    namedObj.discount=cleanedObj['Discount']
                    namedObj.estado_del_sat=cleanedObj['EstadodelSAT']
                    namedObj.fecha_timbrado=cleanedObj['FechaTimbrado']
                    namedObj.financial=((cleanedObj['Financial']=="") ? 0 : parseFloat(cleanedObj['Financial']))
                    namedObj.freight=cleanedObj['Freight']
                    namedObj.gross_sales=((cleanedObj['GrossSales']=="") ? 0 : parseFloat(cleanedObj['GrossSales']))
                    namedObj.id_gl={}
                    namedObj.importe_xml=((cleanedObj['ImporteXML']=="") ? 0 : parseFloat(cleanedObj['ImporteXML']))
                    namedObj.in_transit=cleanedObj['InTransit']
                    namedObj.invoice=cleanedObj['Invoice']
                    namedObj.list_sales=((cleanedObj['ListSales']=="") ? 0 : parseFloat(cleanedObj['ListSales']))
                    namedObj.net_sales=((cleanedObj['NetSales']=="") ? 0 : parseFloat(cleanedObj['NetSales']))
                    namedObj.number=((cleanedObj['Number']=="")? -1 : parseInt(cleanedObj['Number']))
                    namedObj.number_folio=((cleanedObj['NumberFolio']=="")? -1 : parseInt(cleanedObj['NumberFolio']))
                    namedObj.periodo=periodo
                    namedObj.tax_amount=((cleanedObj['TaxAmount']=="") ? 0 : parseFloat(cleanedObj['TaxAmount']))
                    namedObj.tipo=cleanedObj['Tipo']
                    namedObj.tipo_factura=""
                    namedObj.type=cleanedObj['Type']
                    namedObj.UUID=cleanedObj['UUID']
                    namedObj.registro_contable=0
                    namedObj.charges_type=0;
                    namedObj.erroneo=0;
                    namedObj.completo=0;
                    namedObj.comentarios="";



                    if(namedObj.UUID=="#N/A")
                        namedObj.registro_contable=1;

                    if(namedObj.charges!=0)
                        namedObj.charges_type=1

                    excelRows.push(namedObj);
                }
            }
            console.log(excelRows)
            let VD = iniciarValidacion();

        }
    }
    else
    {
        alert("ARCHIVO INCORRECTO. ")
        desactivarProcesar()
    }

    reader.readAsText(file,'Windows-1252');


});
//FUNCION QUE IMPRIME EN LA CONSOLA DEL SISTEMA SEALY
function imprimirError(err,color){
    if(!color)
        color="#FFFFFF"
    $("#tablaSubirCuentasCobrarBody").append(
        "<tr>" +
        "<td class='left-align' bgcolor='"+color+"'>" + err + "</td>" +
        "</tr>"
    );
}

//FUNCION QUE GESTIONA LAS VALIDACIONES
async function iniciarValidacion() {
    try
    {
        let IVCFDI=await iniciarValidacionCFDI()
        let IVGL=await iniciarValidacionGL()
        alert("TODOS LOS REGISTROS HAN SIDO VALIDADOS CORRECTAMENTE, PUEDES PROCESAR EL ARCHIVO")
        console.log(indicesError)
        console.log(excelRows)

    }catch (e) {
        console.log("ERRROR EN iniciarValidacion: "+e)
    }
}

//FUNCION QUE GESTIONA LAS VALIDACIONES DEL CFDI
async function iniciarValidacionCFDI() {
    let i;
    let registrosNoValidados=0
    for(i=0;i<excelRows.length;i++) {
        if (excelRows[i].charges_type != 1 && excelRows[i].registro_contable != 1)
        {
            await validarDatosExcel(i)
            await validarCFDI(i)
            console.log("\n")
            console.log(i)
            console.log("\n")
            await sleep(15)
        }
        else
        {
            let mensajeError="EL REGISTRO CON FOLIO: "+excelRows[i].number_folio+" NO FUE VALIDADO(CFDI) POR SER CHARGES Y/O REGISTRO CONTABLE. "
            excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
            registrosNoValidados++
            console.log(mensajeError)
        }
        let j=i+1
        $("#progresoSubirCuentasCobrar" ).html(j+" CFDIs Validados de: "+excelRows.length);
    }
    console.log("TOTAL DE REGISTROS NO VALIDADOS(CFDI): "+registrosNoValidados)
    return new Promise((resolve, reject) =>    {
        resolve(true)
    });
}
//VALIDACIONES DEL EXCEL(ARITMETICAS)
async function validarDatosExcel(i) {
    return new Promise((resolve, reject) => {
        try {
            if(excelRows[i].charges_type!=1)
            {
                let eps=0.1;

                let billingM=0
                let comprobacionM=0

                let net_sales=parseFloat(excelRows[i].net_sales)
                let tax_amount=parseFloat(excelRows[i].tax_amount)
                let freight=parseFloat(excelRows[i].freight)
                let charges=parseFloat(excelRows[i].charges)
                let billing=parseFloat(excelRows[i].billing)
                let comprobacion=parseFloat(excelRows[i].comprobacion)
                let importeXML=    parseFloat(excelRows[i].importe_xml)
                let gross_sales=parseFloat(excelRows[i].gross_sales)


                if(excelRows[i].tipo=="NotaCredito")
                {
                    net_sales=Math.abs(net_sales)
                    tax_amount=Math.abs(tax_amount)
                    freight=Math.abs(freight)
                    charges=Math.abs(charges)
                    billing=Math.abs(billing)
                    comprobacion=Math.abs(comprobacion)
                    importeXML=Math.abs(importeXML)
                    gross_sales=Math.abs(gross_sales)

                    billingM=net_sales+tax_amount+freight+charges;
                    comprobacionM=tax_amount+freight+charges;
                }
                else
                {
                    billingM=net_sales+tax_amount+freight+charges;
                    comprobacionM=tax_amount+freight+charges;
                }



                if(!validarFormatoFecha(excelRows[i].invoice))
                {
                    let j=i+2
                    let mensajeError="El registro con folio: "+excelRows[i].number_folio+" de la linea: "+j+ " tiene un error al validar el formato invoive: "+excelRows[i].invoice+". "
                    imprimirError(mensajeError)
                    excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                    excelRows[i].erroneo=1
                    indicesError.push(i)
                    //desactivarProcesar()
                }

                if(Math.abs(billingM-billing)>eps)
                {
                    let j=i+2
                    let mensajeError="El registro con folio: "+excelRows[i].number_folio+" de la linea: "+j+ " tiene un error al validar net_sales+tax_amount+freight+charges("+billing+")=billing("+billing+"). "
                    imprimirError(mensajeError)
                    excelRows[i].erroneo=1
                    excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                    indicesError.push(i)
                    //desactivarProcesar()
                }
            }




            resolve(true)
        } catch (e) {
            console.log("ERROR" + e)
            resolve(false)
        }
    });

}
//VALIDACIONES DEL CFDI
async function validarCFDI(i) {
    return new Promise((resolve, reject) =>    {
        try {
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/getCFDI_iva_total.php",
                dataType: 'json',
                data: {"uuid": excelRows[i].UUID, "index": i, "folio": "20" + excelRows[i].number_folio},
                success: function (result) {

                    if (result.status === 0)
                    {
                        let data = JSON.parse(result.obj)
                        let eps2 = 0.1;
                        let usd=false
                        if (data.moneda == "USD")
                        {
                            eps2 = 5;
                            usd=true
                        }

                        let subtotal = parseFloat(data.subtotal)
                        let iva = parseFloat(data.iva)
                        let folio = data.folio
                        let rfc_emisor = data.rfc_emisor
                        let index = parseInt(result.index);

                        let l;
                        let bandera = false
                        for (l = 0; l < rfc_sealy.length; l++) {
                            if (rfc_emisor == rfc_sealy[l]) {
                                bandera = true
                            }
                        }

                        net_sales = parseFloat(excelRows[index].net_sales)
                        tax_amount = parseFloat(excelRows[index].tax_amount)

                        if (excelRows[index].tipo == "NotaCredito") {
                            net_sales = Math.abs(net_sales)
                            tax_amount = Math.abs(tax_amount)
                        }

                        if (bandera == true) {
                            excelRows[index].tipo_factura = "CxC"
                            if (Math.abs(net_sales - subtotal) > eps2) {
                                let j = index + 2
                                let mensajeError="El registro con folio: " + excelRows[index].number_folio + " de la linea: " + j + " tiene un error al validar excel.net_sales(" + net_sales + ")=cfdi.subtotal(" + subtotal + "). "
                                imprimirError(mensajeError)
                                excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                                excelRows[i].erroneo=1

                                indicesError.push(i)
                                //desactivarProcesar()
                            }
                            if ((Math.abs(tax_amount - iva) > eps2) && usd==false) {
                                let j = index + 2

                                let mensajeError="El registro con folio: " + excelRows[index].number_folio + " de la linea: " + j + " tiene un error al validar excel.tax_amount(" + tax_amount + ")=cfdi.iva(" + iva + "). "
                                imprimirError(mensajeError)
                                excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                                excelRows[i].erroneo=1
                                indicesError.push(i)
                                //desactivarProcesar()
                            }
                        } else {
                            excelRows[index].tipo_factura = "CxP"
                            if (Math.abs(net_sales - subtotal) > eps2) {
                                let j = index + 2

                                let mensajeError="El registro con folio: " + excelRows[index].number_folio + " de la linea: " + j + " tiene un error al validar excel.net_sales(" + net_sales + ")=cfdi.subtotal(" + subtotal + "). "
                                imprimirError(mensajeError)
                                excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                                excelRows[i].erroneo=1
                                indicesError.push(i)
                                //desactivarProcesar()
                            }
                            if ((Math.abs(tax_amount - iva) > eps2) && usd==false) {
                                let j = index + 2
                                let mensajeError="El registro con folio: " + excelRows[index].number_folio + " de la linea: " + j + " tiene un error al validar excel.tax_amount(" + tax_amount + ")=cfdi.iva(" + iva + "). "
                                imprimirError(mensajeError)
                                excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                                excelRows[i].erroneo=1
                                indicesError.push(i)
                                //desactivarProcesar()
                            }
                        }
                        if (folio.indexOf(excelRows[index].number_folio + "") == -1) {
                            let j = index + 2
                            let mensajeError="El registro con UUID: " + excelRows[index].UUID + " de la linea: " + j + " tiene un error al validar excel.folio(" + excelRows[index].number_folio + ")=cfdi.folio(" + folio + "). "
                            imprimirError(mensajeError)
                            excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                            excelRows[i].erroneo=1
                            indicesError.push(i)
                            //desactivarProcesar()
                        }

                    } else {
                        let index = parseInt(result.index)
                        let j = index + 2
                        let mensajeError="El registro con UUID: " + excelRows[index].UUID + " de la linea: " + j + " no fue encontrado en cfdis. "
                        imprimirError(mensajeError)
                        excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                        excelRows[i].erroneo=1
                        indicesError.push(i)
                        //desactivarProcesar()
                    }
                    validadosCFDI++;
                }
            });
            resolve(true)
        }catch (e) {
            resolve(false)
            console.log("ERROR"+e)
        }
    });
}

//FUNCION QUE GESTIONA LAS VALIDACIONES DEL GL
async function iniciarValidacionGL() {
    try {
        let i=0;
        let registrosNoValidados=0
        for(i=0;i<excelRows.length;i++)
        {
            let j=i+1
            if(excelRows[i].charges_type!=1)
            {
                let data=""
                let VGL=false
                data=await extraerGL(i)

                if(data!=false)
                {
                    VGL=await validarGL(data,i)
                    if(VGL==false)
                        {
                            imprimirError("ERROR AL VALIDAR EL GL CON FOLIO: "+excelRows[i].number_folio+" EN LA LINEA: "+j+"DOUBLE")
                        }
                    else
                        {
                            console.log("VALIDACION CORRECTA DEL GL CON FOLIO: "+excelRows[i].number_folio+" EN LA LINEA: "+j)
                            console.log("IDs TRUE")
                            console.log(VGL)
                            excelRows[i].id_gl=VGL
                            //excelRows[i].periodo=excelRows[i].fecha_timbrado.split("-")[1]
                            console.log("\n\n")
                        }
                }
                else
                    {
                        let mensajeError="ERROR AL EXTRAER EL GL CON FOLIO: "+excelRows[i].number_folio+" EN LA LINEA: "+j+". "
                        imprimirError(mensajeError)
                        excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                        excelRows[i].erroneo=1
                    }
            }
            else
            {

                let mensajeError="EL REGISTRO CON FOLIO: "+excelRows[i].number_folio+" EN LA LINEA: "+j+" NO FUE VALIDADO(GL) POR SER CHARGES. "
                imprimirError(mensajeError)
                excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                registrosNoValidados++;
            }

            $("#progresoSubirCuentasCobrar" ).html(j+" GLs Validados de: "+excelRows.length);
        }
        console.log("TOTAL DE REGISTROS NO VALIDADOS(GL): "+registrosNoValidados)
        return new Promise((resolve, reject) =>    {
            resolve(true)
        });
    }
    catch (e) {
        console.log("ERROR AL ITERAR EN EL GL: "+e)
    }




}
//FUNCION QUE EXTRAE EL GL DE LA BASE DE DATOS
async function extraerGL(i) {
    return new Promise((resolve, reject) =>    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/getGL_desc_debit_credit_number_type.php",
                dataType: 'json',
                //data: {"period": periodo, "index":i, "folio":"% "+excelRows[i].number_folio+" %","folio2":"% "+excelRows[i].number_folio+"0%"},
                data: {"period": periodo, "index":i, "folio":"%"+periodo+" "+excelRows[i].number_folio+"%"},
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(JSON.parse(result.obj))
                    }
                    else
                    {
                        let index=parseInt(result.index)
                        let j=index+2
                        let mensajeError="ERROR AL INTENTAR EXTRAER EL GL CON FOLIO: "+excelRows[i].number_folio+" DE LA LINEA "+j+". "
                        imprimirError(mensajeError)
                        excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                        excelRows[i].erroneo=1
                        indicesError.push(index)
                        resolve(false)
                    }

                    validadosGL++;
                    let index=parseInt(result.index)
                    console.log("SE COMPLETO LA EXTRACION DEL GL CON FOLIO: "+excelRows[index].number_folio)

                }
            });

        }
        catch (e) {
            console.log("ERROR"+e)
            resolve(false)
        }
    });



}
//FUNCION QUE VALIDA EL GL
async function validarGL(data, index) {
    return new Promise((resolve, reject) =>    {
        try {


            let i;
            let numbers = []
            numbers.push("ALL")
            let ids = []
            ids["ALL"] = []
            let creditIVA = [], debitIVA = [], debit = [], credit = []
            creditIVA["ALL"] = 0
            credit["ALL"] = 0

            debitIVA["ALL"] = 0
            debit["ALL"] = 0

            for (i = 0; i < data.length; i++) {
                ids["ALL"].push(data[i].id)
                if (numbers.indexOf(data[i].number) == -1) {
                    numbers.push(data[i].number)
                    ids[data[i].number] = []
                    ids[data[i].number].push(data[i].id)

                    creditIVA[data[i].number] = 0
                    credit[data[i].number] = 0
                    debitIVA[data[i].number] = 0
                    debit[data[i].number] = 0
                } else {
                    ids[data[i].number].push(data[i].id)
                }
            }


            let eps = 0.5


            for (i = 0; i < data.length; i++) {
                let gl = data[i]
                let description = gl.description

                //NO ES DE TIPO IVA
                if (description.indexOf("TAX IVA") != -1) {
                    debitIVA["ALL"] = debitIVA["ALL"] + parseFloat(gl.debit_amount)
                    creditIVA["ALL"] = creditIVA["ALL"] + parseFloat(gl.credit_amount)

                    debitIVA[data[i].number] = debitIVA[data[i].number] + parseFloat(gl.debit_amount)
                    creditIVA[data[i].number] = creditIVA[data[i].number] + parseFloat(gl.credit_amount)
                } else {
                    debit["ALL"] = debit["ALL"] + parseFloat(gl.debit_amount)
                    credit["ALL"] = credit["ALL"] + parseFloat(gl.credit_amount)

                    debit[data[i].number] = debit[data[i].number] + parseFloat(gl.debit_amount)
                    credit[data[i].number] = credit[data[i].number] + parseFloat(gl.credit_amount)
                }
            }

            let numberT = ""

            let net_sales = excelRows[index].net_sales
            let tax_amount = excelRows[index].tax_amount

            if (excelRows[index].tipo == "NotaCredito") {
                net_sales = Math.abs(net_sales)
                tax_amount = Math.abs(tax_amount)
            }



            //FALTA VERIFICAR TODAS LAS COMBINACIONES ENTRE SI PARA VER CUAL ES LA BUENA

            for (i = 0; i < numbers.length; i++)
            {
                let debit_credit=Math.abs(Math.abs(debit[numbers[i]]) - Math.abs(credit[numbers[i]]))
                let debitIVA_creditIVA=Math.abs(Math.abs(debitIVA[numbers[i]]) - Math.abs(creditIVA[numbers[i]]))
                if (debit_credit < eps && debitIVA_creditIVA < eps)
                {
                    let debit_netSales=Math.abs(Math.abs(debit[numbers[i]]) - Math.abs(net_sales))
                    let debitIVA_taxAmount=Math.abs(Math.abs(debitIVA[numbers[i]]) - Math.abs(tax_amount))
                    if (debit_netSales < eps && debitIVA_taxAmount < eps)
                    {
                        if (numbers[i] != "ALL")
                            numberT = numbers[i]
                    }
                }
            }
            if (numberT == "")
                if (Math.abs(Math.abs(debit["ALL"]) - Math.abs(credit["ALL"])) < eps && Math.abs(Math.abs(debitIVA["ALL"]) - Math.abs(creditIVA["ALL"])) < eps)
                    if (Math.abs(Math.abs(debit["ALL"]) - Math.abs(net_sales)) < eps && Math.abs(Math.abs(debitIVA["ALL"]) - Math.abs(tax_amount)) < eps)
                        numberT = "ALL"



            console.log("folio", excelRows[index].number_folio)
            console.log("creditIVA", creditIVA)
            console.log("credit", credit)
            console.log("debitIVA", debitIVA)
            console.log("debit", debit)
            console.log("IDS: ", ids)


            if (numberT != "")
            {
                console.log("IDS TRUE: ", ids[numberT])
                excelRows[i].id_gl=ids[numberT]
                //excelRows[i].periodo=excelRows[i].fecha_timbrado.split("-")[1]

                resolve(ids[numberT])
            }
            else
            {

                excelRows[i].id_gl=[-1]
                excelRows[i].periodo=-1

                let j=i+2
                let mensajeError="ERROR AL VALIDAR EL GL CON FOLIO: "+excelRows[i].number_folio+" EN LA LINEA: "+j+". "
                imprimirError(mensajeError)
                excelRows[i].comentarios=excelRows[i].comentarios+mensajeError
                excelRows[i].erroneo=1
                indicesError.push(i)

                resolve(false)
            }
        }
        catch (e) {
            console.log("HUBO UN ERROR AL VALIDAR EL GL: "+e)
            console.log("GL ERRONEO"+data)
            resolve(false)
        }

    });
}

//FUNCION QUE DESACTIVA EL BOTON DE PROCESAR
function desactivarProcesar() {
    document.getElementById('botonProcesar').disabled = true;
    banderaError=true;
    //console.log(excelRows[0])

}
//FUNCION QUE VALIDA EL FORMATO DE FECHA
function validarFormatoFecha(fecha) {
    var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
    if ((fecha.match(RegExPattern)) && (fecha!='')) {
        return existeFecha(fecha);
    } else {
        return false;
    }
}
function existeFecha (fecha) {
    var fechaf = fecha.split("/");
    var m = fechaf[0];
    var d = fechaf[1];
    var y = fechaf[2];
    return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();
}
//ACTUALIZA LA PANTALLA
function actualizar(){
    location.reload();
}

//INICIA A SUBIR LOS REGISTROS
function procesar() {

    console.log("INDICES ERROR: ",indicesError)
    console.log(excelRows)
    $("#progresoSubirCuentasCobrar" ).html("");
    let i=0;
    let block=50;
    let timeValue=3000
    subirFactura(i, true, -1)
    let idInterval4=setInterval(function ()
    {
        i=i+1
        subirFactura(i*block, true, idInterval4)
    }, timeValue);

}
//GESTIONA EL TIPO DE BUSQUEDA
function tipo_busqueda(tipo) {
    tipoBusqueda=tipo
}

//SUBE UN REGISTRO
function subirFactura(index, bandera, idInterval) {

    if(index%50==0 && !bandera)
        return


    if(excelRows.length==0)
        $("#progresoSubirCuentasCobrar" ).html(" NO HAY DATOS" );

    if(index>=excelRows.length)
    {
        $("#progresoSubirCuentasCobrar").html(" Datos guardados correctamente");
        if(index>50)
            clearInterval(idInterval)
    }
    else
    {
        //console.log("index: "+index, excelRows[index])
        excelRows[index].id_gl=JSON.stringify(excelRows[index].id_gl)
        let fechaP=excelRows[index].invoice.split("/")
        excelRows[index].invoice=fechaP[2]+"-"+fechaP[0]+"-"+fechaP[1]

        $.ajax({
            url:"/coesealy-conciliacion-cobranza/backend/contabilidad/api/insertarFacturaCuentasCobrar.php",
            dataType: 'json',
            type: 'POST',
            data:
                {
                    'index':index,
                    'data':JSON.stringify(excelRows[index])
                },
            success: function(result)
            {
                if(result.status == 0)
                {

                    let indice=result.index
                    $("#progresoSubirCuentasCobrar" ).html("Subiendo " + (indice+1) + " de " + excelRows.length);
                    subirFactura(indice+1, false,idInterval)
                    //console.log(indice, result.obj)
                }
                else
                {
                    //console.log(result)
                    let indice=result.index
                    let i=indice+1
                    imprimirError("Error al subir el registro con folio: "+excelRows[i].number_folio+" en el renglon: "+ (i))
                    //subirFacturaError(indice, false,idInterval)
                    subirFactura(indice+1, false,idInterval)
                }

            },
            error: function(error)
            {
                console.log(error);

            }
        })
    }
}

//consultar CuentasCobrar
function obtenerCuentasCobrar($period, $year){
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/getCuentasCobrar.php",
        dataType: 'json',
        data: {"period": $period, "year": $year, "tipo":tipoBusqueda},

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
//LE DA EL FORMATO DE CONTADURIA A LOS NÚMEROS
function formatoNumerico(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
//IMPRIME LAS FACTURAS CONSULTADAS
function imprimirVerCuentasCobrar(tabla)
{
    $("#tablaVerCuentasCobrarBody").html("")

    for (let i = 0; i < tabla.length; i++) {
        let j=i+1;

        $("#tablaVerCuentasCobrarBody").append(
            "<tr>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + j + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].number_folio + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + tabla[i].customer_name + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9' >" + tabla[i].invoice + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].net_sales) + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].tax_amount) + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].tipo + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].UUID + "</td>" +
            "</tr>"
        );
    }
    $('#tablaVerCuentasCobrar').DataTable({"pageLength": 1001});

    $("#msgDivVerCuentasCobrar").html("");


}

//DELAY
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}