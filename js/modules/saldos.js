async function iniciarValidacion() {


    let folios= await extraerFoliosSaldos()

    if(folios!=false)
    {
        console.log(folios)
        let i=0;
        console.log(folios.length," Folios")
        $("#progresoArmado" ).html("Folios validados 0 de "+folios.length)
        for(i=0;i<folios.length;i++)
        //for(i=0;i<10;i++)
        {
            let grupo= await extraerGrupoFolio(folios[i])
            console.log("Grupo con todos los saldos para el folio dado")
            console.log(grupo)
            let validarB=await validarBloque(grupo)

            console.log("validarB")
            console.log(validarB)

            if(validarB.cfdi!=false && validarB.cfdi!=-1)
            {
                await updateIdCfdi(validarB.folio, validarB.total, validarB.cfdi,validarB.fecha_emision, validarB.year)
                if(validarB.estatus.indexOf(23)!=-1)
                    await updateEstatusCfdi(validarB.folio,1,validarB.cfdi )
                else
                    if(validarB.estatus.indexOf(29)!=-1)
                        await updateEstatusCfdi(validarB.folio,2,validarB.cfdi )
            }
            if(validarB.factura!=false && validarB.factura!=-1)
            {
                await updateIdFactura(validarB.folio,validarB.factura, validarB.fecha_emision, validarB.year)
                if(validarB.estatus.indexOf(23)!=-1)
                    await updateEstatusFactura(validarB.folio,1,validarB.factura )
                else
                    if(validarB.estatus.indexOf(29)!=-1)
                        await updateEstatusFactura(validarB.folio,2,validarB.cfdi )
            }
            if(validarB.estatus.length>0 || validarB.fecha_cierre!="0001-01-01")
                await updateEstatusSaldo(validarB.folio, validarB.estatus, validarB.fecha_cierre, validarB.year)

            let j=i+1
            $("#progresoArmado" ).html("Folios validados "+j+" de "+folios.length)
        }
        delete grupo;
        delete validarB;
        delete folios[i];
    }

    return new Promise((resolve, reject) =>
    {
        resolve(true)

    });

}
async function extraerFoliosSaldos() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/extraer_folios_saldos.php",
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
async function extraerGrupoFolio(folio) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/extraer_grupo_folio.php",
                dataType: 'json',
                data: {"folio": folio},
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
async function  validarBloque(grupo) {
    let banderaCfdiFactura=await validarCfdiFactura(grupo)
    console.log("banderaCfdiFactura")
    console.log(banderaCfdiFactura)

    if(banderaCfdiFactura.total!=false && banderaCfdiFactura.total!=-1)
    {

        let estatusVA=await validarActual(grupo, banderaCfdiFactura.total, banderaCfdiFactura.bConciliacion)
        console.log("estatusVA")
        console.log(estatusVA)

        banderaCfdiFactura.estatus=banderaCfdiFactura.estatus.concat(estatusVA["estatus"])
        banderaCfdiFactura.fecha_cierre=estatusVA.fecha_cierre
    }
    return new Promise((resolve, reject) =>
    {
        resolve(banderaCfdiFactura)

    });
}

async function validarCfdiFactura(grupo) {

    let folio=grupo[0].folio
    let i;
    let cfdi=[];
    let factura=[];
    let total=[]
    let fechaEmision=[]

    let jsonRes=
        {
            "folio":folio,
            "factura":false,
            "cfdi":false,
            "total":false,
            "estatus":[],
            "year":[],
            "fecha_emision":false,
            "fecha_cierre":"0001-01-01",
            "bConciliacion":false
        }

    for (i=0;i<grupo.length;i++)
    {
        if(grupo[i].id_cfdi!=-1 && cfdi.indexOf(grupo[i].id_cfdi)==-1)
            cfdi.push(grupo[i].id_cfdi)
        if(grupo[i].id_factura!=-1 && factura.indexOf(grupo[i].id_factura)==-1)
            factura.push(grupo[i].id_factura)
        if(grupo[i].total!=-1 && total.indexOf(grupo[i].total)==-1)
            total.push(grupo[i].total)
        if(grupo[i].fecha_emision!="0001-01-01" && fechaEmision.indexOf(grupo[i].fecha_emision)==-1 && grupo[i].fecha_emision!="0000-00-00")
            fechaEmision.push(grupo[i].fecha_emision)
        jsonRes.year.push(grupo[i].year)
    }

    if(total.length>1)
        jsonRes.estatus.push(31)

    if(cfdi.length>1)
        jsonRes.estatus.push(37)
    else
    if(factura.length>1)
        jsonRes.estatus.push(37)

    if(fechaEmision.length>1)
        jsonRes.estatus.push(41)


    if(total.length==1)
        jsonRes.total=parseFloat(total[0])

    if(cfdi.length==1)
    {
        jsonRes.cfdi=parseInt(cfdi[0])
        if(grupo.length>1)
            jsonRes.estatus.push(13)
    }

    if(factura.length==1)
    {
        jsonRes.factura=parseInt(factura[0])
        if(grupo.length>1)
            jsonRes.estatus.push(17)
    }

    if(fechaEmision.length==1)
        jsonRes.fecha_emision=fechaEmision[0]



    if(cfdi.length==0)
    {
        let cfdiRes=await getCfdiByFolio(folio);
        if(cfdiRes!=false && cfdiRes!=-1)
        {
            jsonRes.cfdi=parseInt(cfdiRes["year"]);
            jsonRes.total=parseFloat(cfdiRes["total"]);
            jsonRes.estatus.push(13)
            jsonRes.bConciliacion=true

            if(fechaEmision.length==0)
            {
                jsonRes.fecha_emision=cfdiRes["fecha_emision"]
                fechaEmision.push(cfdiRes["fecha_emision"])
            }
        }
    }
    if(factura.length==0)
    {
        let facturaRes=await getFacturaByFolio(folio);
        if(facturaRes!=false && facturaRes!=-1)
        {
            jsonRes.factura=parseInt(facturaRes["year"]);
            jsonRes.estatus.push(17)
            if(fechaEmision.length==0)
            {
                jsonRes.fecha_emision=facturaRes["invoice"]
            }
        }
    }


    return new Promise((resolve, reject) =>
    {
        resolve(jsonRes)
    });
}
async function validarActual(grupo, total, bConciliacion) {

        let folio=grupo[0].folio
        let eps=10
        let i;
        let actual=0;
        let banderoExcesoMonto=false;
        let ultimoCobroMultianual="0001-01-01"
        let ultimoCobroExcesoMonto=false

        for (i=0;i<grupo.length;i++)
        {

            actual=actual+grupo[i].actual

            if((new Date(ultimoCobroMultianual)).getTime()<(new Date(grupo[i].ultimo_cobro)).getTime())
                   ultimoCobroMultianual=grupo[i].ultimo_cobro


            if(actual>=total && banderoExcesoMonto==false)
            {
                banderoExcesoMonto=grupo[i].year
                let id_cobranza=JSON.parse(grupo[i].id_cobranza)
                if(id_cobranza.length==1 && ultimoCobroExcesoMonto==false)
                    ultimoCobroExcesoMonto=grupo[i].ultimo_cobro
                else
                {
                    if(ultimoCobroExcesoMonto==false)
                    {
                        let listaCobranza=await extraerCobranzaByFolio(folio, banderoExcesoMonto)
                        let actualAnterior=actual-grupo[i].actual
                        let j;
                        for(j=0;j<listaCobranza.length;j++)
                        {
                            actualAnterior=actualAnterior+listaCobranza[j].payment_amount
                            if(actualAnterior>=total && ultimoCobroExcesoMonto==false)
                                ultimoCobroExcesoMonto=listaCobranza[j].deposit_date
                        }
                    }
                }


            }
        }

        let jsonRes=
            {
                "actual":actual,
                "fecha_cierre":"0001-01-01",
                "estatus":[]
            }


        //SE ACOMPLETO EL MONTO TOTAL
        if(actual+eps>=total && actual-eps<=total)
        {
            if(grupo.length>1 || bConciliacion==true)
                jsonRes.estatus.push(23)
            jsonRes.fecha_cierre=ultimoCobroMultianual
        }
        else
        {
            //SE PASO DEL MONTO TOTAL
            if(actual-eps>total)
            {
                if(grupo.length>1 || bConciliacion==true)
                    jsonRes.estatus.push(29)
                if(ultimoCobroExcesoMonto!=false)
                    jsonRes.fecha_cierre=ultimoCobroExcesoMonto
            }
            else
            {
                //FALTA PARA ALCANZAR EL MONTO TOTAL
                if(actual+eps<total)
                {
                    if(grupo.length>1 || bConciliacion==true)
                        jsonRes.estatus.push(19)
                }
            }


        }
    return new Promise((resolve, reject) =>
    {
        resolve(jsonRes)
    });
}

async function getCfdiByFolio(folio) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/get_cfdi.php",
                dataType: 'json',
                data: {"folio": folio.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(result.cfdi)
                    }
                    else
                    {
                        resolve(-1)
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
async function getFacturaByFolio(folio) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/get_factura.php",
                dataType: 'json',
                data: {"folio": folio.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(result.factura)
                    }
                    else
                    {
                        resolve(-1)
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

async function updateEstatusSaldo(folio, estatus, fecha_cierre, year)
{
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/update_estatus_saldo.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"estatus": JSON.stringify(estatus), "fecha_cierre": JSON.stringify(fecha_cierre),"year": JSON.stringify(year)},
                success: function (result) {

                    if (result.status === 0)
                    {
                        console.log("ACTUALIZACION DE ESTADOS SALDO CORRECTA")
                        resolve(true)
                    }
                    else
                    {
                        console.log("ERROR ACTUALIZACION DE ESTADOS SALDO")
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

async function updateIdFactura(folio, id_factura, fecha_emision, year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/update_id_factura.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"id_factura": id_factura.toString(),"fecha_emision": fecha_emision.toString(),"year": JSON.stringify(year)},
                success: function (result) {

                    if (result.status === 0)
                    {
                        console.log("ACTUALIZACION DE FACTURA CORRECTA")
                        resolve(true)
                    }
                    else
                    {
                        console.log("ERROR ACTUALIZACION DE FACTURA")
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
async function updateIdCfdi(folio, total, id_cfdi, fecha_emision, year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/update_id_cfdi.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"total": total.toString(),"id_cfdi": id_cfdi.toString(),"fecha_emision": fecha_emision.toString(),"year": JSON.stringify(year)},
                success: function (result) {

                    if (result.status === 0)
                    {
                        console.log("ACTUALIZACION DE CFDI CORRECTA")
                        resolve(true)
                    }
                    else
                    {
                        console.log("ERROR ACTUALIZACION DE CFDI    ")
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

async function updateEstatusFactura(folio, estatus, year){
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/update_estatus_factura.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"estatus": estatus.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {
                        console.log("ACTUALIZACION DE ESTADOS FACTURA CORRECTA")
                        resolve(true)
                    }
                    else
                    {
                        console.log("ERROR ACTUALIZACION DE ESTADOS FACTURA")
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
async function updateEstatusCfdi(folio, estatus, year){
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/armado/update_estatus_cfdi.php",
                dataType: 'json',
                data: {"folio": folio.toString(),"estatus": estatus.toString(),"year": year.toString()},
                success: function (result) {

                    if (result.status === 0)
                    {
                        console.log("ACTUALIZACION DE ESTADOS CFDI CORRECTA")
                        resolve(true)
                    }
                    else
                    {
                        console.log("ERROR ACTUALIZACION DE CFDI FACTURA")
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


function actualizar(){
    location.reload();
}
