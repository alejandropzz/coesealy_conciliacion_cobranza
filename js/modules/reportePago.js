//DESACTUALIZADO

async function iniciarLiga() {
    let ids=await extraerIdDiotNoLigadasCXP(null)
    console.log(ids)

    let i;
    for(i=0;i<ids.length;i++)
    {
        let diot=await extraerDiotByIdCXP(ids[i].id, null)
        if(!diot)
            imprimirError("No se pudo extraer el Diot con id: "+ids[i].id)
        else
        {
            let uuid=diot["uuid_factura"]
            if(!cfdi)
                imprimirError("No se pudo extraer el UUID del Diot con id: "+ids[i].id)
            else
                {
                let cfdi=await getCfdiByUuid(uuid,null)
                if(!cfdi)
                    imprimirError("No se pudo extraer el CFDI del Diot con id: "+ids[i].id)
                else
                {
                    console.log(cfdi)
                    let uuid_liga=generateUUID()
                    let yearDiot=diot["year"]

                    let ligaCFDIR=await ligarCfdiByUuid(uuid,uuid_liga,yearDiot)


                }

            }

        }

    }

}


async function extraerIdDiotNoLigadasCXP(year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_id_diot_no_ligadas_cxp.php",
                dataType: 'json',
                data: {"year": year},
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
async function extraerDiotByIdCXP(id, year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_diot_by_id.php",
                dataType: 'json',
                data: {"id": id.toString(),"year": year},
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
async function ligarCfdiByUuid(uuid_cfdi, uuid_liga, year_rp) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reportePago/ligar_cfdi_by_uuid.php",
                dataType: 'json',
                data: {"uuid_cfdi": uuid_cfdi.toString(),"uuid_liga": uuid_liga.toString(),"year_rp":year_rp.toString()},
                success: function (result) {
                    if (result.status === 0)
                        resolve(true)

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
async function getCfdiByUuid(uuid, year) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/importar/extraer_cfdi_by_uuid.php",
                dataType: 'json',
                data: {"uuid": uuid.toString(), year},
                success: function (result) {
                    if (result.status === 0)
                        resolve(result.cfdi)

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

async function imprimirError(err,color){
    if(!color)
        color="#FFFFFF"
    $("#tablaLigarRpBody").append(
        "<tr>" +
        "<td class='left-align' bgcolor='"+color+"'>" + err + "</td>" +
        "</tr>"
    );
    return new Promise((resolve, reject) =>    {
        resolve(true)
    });
}
function actualizar(){
    location.reload();
}
function generateUUID() { // Public Domain/MIT
    var d = new Date().getTime();//Timestamp
    var d2 = (performance && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16;//random number between 0 and 16
        if(d > 0){//Use timestamp until depleted
            r = (d + r)%16 | 0;
            d = Math.floor(d/16);
        } else {//Use microseconds since page-load if supported
            r = (d2 + r)%16 | 0;
            d2 = Math.floor(d2/16);
        }
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
}
