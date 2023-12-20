//BY PZZs
//UPDATE `cfdis` SET `data_rp` = null, `id_bloque_cobranza` = null, `id_cobros` = null WHERE `cfdis`.`id` > 1
var excelRows = [];
var idCuenta="";
var epsCFDI=10;
var eps=0.1;
let periodo;
let bloquesValidadosError=0;
let bloquesValidadosCorrecto=0;
let EnonarGL=0
let EnonarEstado_cuenta=0
let Earitmetico=0
let Egl=0
let Ecfdi=0
let Esaldos=0
let Efactura=0
let Eclones=0
let tipoBusqueda=1
let ligados=0


async function extraerIdsGruposCobranza() {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/extraer_ids_grupos_cobranza.php",
                dataType: 'json',
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(result.idsCobranza)
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
async function extraerGrupoCobranza(uuid) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/extraer_grupo_cobranza.php",
                dataType: 'json',
                data: {"uuid": uuid},
                success: function (result) {

                    if (result.status === 0)
                    {
                        resolve(result.listaRC)
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

//FUNCION QUE GESTIONA LAS VALIDACIONES DE LOS BLOQUES
async function iniciarValidacion() {

    let ids= await extraerIdsGruposCobranza()
    if(ids!=false)
        {
            console.log(ids)
            let i=0;
            console.log(ids.length," BLOQUES")
            $("#progresoSubirReporteCobranza" ).html("Bloques validados 0 de "+ids.length)
            for(i=0;i<ids.length;i++)    {
            //for(i=0;i<3;i++)    {
                let grupo= await extraerGrupoCobranza(ids[i])
                console.log("Grupo Original")
                console.log(grupo)
                let validarB=await validarBloque(grupo,ids.length)
            }
        }
}

async function actualizarContadorValidados(correcto,error, totalBloques) {
    bloquesValidadosCorrecto=bloquesValidadosCorrecto+correcto
    bloquesValidadosError=bloquesValidadosError+error
    let actual=bloquesValidadosCorrecto+bloquesValidadosError
    $("#progresoSubirReporteCobranza" ).html("Bloques validados Correctamente: "+bloquesValidadosCorrecto+ "&nbsp &nbsp Bloques Erroneos: "+bloquesValidadosError+"&nbsp &nbsp Total: "+actual+"/"+totalBloques)
    return new Promise((resolve, reject) =>    {
        resolve(true)
    });
}
async function imprimirError(err,color){
    if(!color)
        color="#FFFFFF"
    $("#tablaSubirReporteCobranzaBody").append(
        "<tr>" +
        "<td class='left-align' bgcolor='"+color+"'>" + err + "</td>" +
        "</tr>"
    );
    return new Promise((resolve, reject) =>    {
        resolve(true)
    });
}

async function validarNonar(data) {
    return new Promise((resolve, reject) =>
    {

        if(data[0].folio=="*NONAR" && data[0].gl_account=="100000")
        {
            try{
                $.ajax({
                    url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/validarNonar.php",
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        "data":JSON.stringify(data[0])
                    },
                    success: function (result) {
                        let resultado=
                            {
                                "estado_cuenta":true,
                                "gl":true
                            }
                        if (result.status === 0)
                        {
                            data[0]["estado_cuenta"]=result.estado_cuenta
                            data[0]["GL"]=result.GL


                            /*
                            let i;
                            for (i=0;i<result.GL.length;i++)
                            {
                                data[0].id_gl.push(result.GL[i].id)
                            }
                            @PZZ
                             */

                            resolve(resultado)
                        }
                        else
                        {
                            if(result.estado_cuenta==null)
                                {
                                    let mensajeError="El registro *NONAR no tiene Estado de Cuenta. CHECK NUMBER: "+data[0].check_number+", FECHA: "+data[0].deposit_date+". "
                                    imprimirError(mensajeError)
                                    data[0].comentarios=data[0].comentarios+mensajeError
                                    resultado.estado_cuenta=false
                                }
                            else
                                data[0]["estado_cuenta"]=result.estado_cuenta
                            if(result.GL==null)
                                {

                                    let mensajeError="El registro *NONAR no tiene GL. CHECK NUMBER: "+data[0].check_number+", FECHA: "+data[0].deposit_date+". "
                                    imprimirError(mensajeError)
                                    data[0].comentarios=data[0].comentarios+mensajeError
                                    resultado.gl=false
                                }
                            else
                            {
                                data[0]["GL"]=result.GL
                                /*
                                let i;
                                for (i=0;i<result.GL.length;i++)
                                {
                                    data[0].id_gl.push(result.GL[i].id)
                                }
                               @PZZ
                                 */
                            }
                            resolve(resultado)
                        }
                    }
                });
            }
            catch (e)
            {
                console.log("ERROR"+e)
                resolve(false)
            }

        }
        else
        {
            if(data[0].folio=="*NONAR")
            {
                try {
                    $.ajax({
                        url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/validarNonarGL.php",
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            "data": JSON.stringify(data[0])
                        },
                        success: function (result) {
                            let resultado=
                                {
                                    "estado_cuenta":true,
                                    "gl":true
                                }
                            if (result.status === 0)
                            {
                                data[0]["GL"] = result.GL
                                /*
                                let i;
                                for (i=0;i<result.GL.length;i++)
                                {
                                    data[0].id_gl.push(result.GL[i].id)
                                }
                                @PZZ
                                 */
                                resolve(resultado)
                            }
                            else
                            {
                                let mensajeError="El registro *NONAR no tiene GL. CHECK NUMBER: "+data[0].check_number+", FECHA: "+data[0].deposit_date+". "
                                imprimirError(mensajeError)
                                data[0].comentarios=data[0].comentarios+mensajeError
                                resultado.gl=false
                                resolve(resultado)
                            }


                        }
                    });
                } catch (e) {
                    console.log("ERROR" + e)
                    resolve(false)
                }
            }
            else
                {
                    let resultado=
                        {
                            "estado_cuenta":true,
                            "gl":true
                        }
                    resolve(resultado)
                }
        }


        let resultado=
            {
                "estado_cuenta":true,
                "gl":true
            }
        resolve(resultado)
    });

}
//FUNCION QUE VALIDA SI UN GRUPO YA SE PROCESO Y SOLO ESPERA UN ABONO
async function validarGrupoLigado(data) {
    return new Promise((resolve, reject) => {
        try {
            let i
            let bandera=true
            for(i=0;i<data.length; i++)
            {
                if(data[i].ligado!=1)
                    bandera=false

            }
            resolve(bandera)
        } catch (e) {
            console.log("ERROR" + e)
            resolve(false)
        }
    });

}
//FUNCION QUE VALIDA LOS VALORES ARITMETICOS
async function validarSumaAritmetica(data) {
    return new Promise((resolve, reject) => {
        try {
            let contador=0
            let i
            let bandera=true
            for(i=0;i<data.length; i++)
            {
                if(!validarNumeroDecimal(data[i].payment_amount))
                    bandera=false
                contador=contador+parseFloat(data[i].payment_amount)
            }
            if(Math.abs(contador)<eps && bandera==true)
                resolve(true)
            else
                resolve(false)

        } catch (e) {
            console.log("ERROR" + e)
            resolve(false)
        }
    });

}
//FUNCION QUE VALIDA EL CFDI, GL, FACTURA Y REGISTROS ESPEJO
async function validarBloqueCFDI_GL_Factura(data) {
    try {

        let i;
        //EN ESTE CICLO SE EXTRAE EL GL, CFDI Y SALDO DE LA BASE DE DATOS

        for (i = 0; i < data.length; i++) {
            await getCFDI_Saldo_GL_Factura(data[i])
        }
        //SI TODO VA BIEN HASTA ESTE PUNTO, CADA COBRO DEBE TENER SU CFDI, GL, SALDO Y FACTURA
    }catch (e) {
        console.log(e)
        console.log(data)
    }

    console.log("DATA despues de extraccion")
    console.log(data)

    return new Promise((resolve, reject) =>
    {
        try {
            let i;
            let j = 0


            if (data[0].folio == "*NONAR") {
                j = 1
            }

            let clones = {}
            clones["headers"] = []

            for (i = j; i < data.length; i++)
            {
                data[i].validado_CFDI=false
                let llave = Math.abs(data[i].payment_amount).toString()
                if (clones["headers"].indexOf(llave)!=-1)
                {
                    if(data[i].CFDI==null || data[i].CFDI==false || data[i].factura==null || data[i].factura==false)
                        clones[llave]["no_CFDI"].push(i)
                    else
                        clones[llave]["CFDI"].push(i)
                }
                else
                {
                    clones["headers"].push(llave)
                    clones[llave] = {}
                    clones[llave]["CFDI"]=[]
                    clones[llave]["no_CFDI"]=[]
                    //if(data[i].CFDI==null || data[i].CFDI==false)
                    if(data[i].CFDI==null || data[i].CFDI==false  || data[i].factura==null || data[i].factura==false)
                        clones[llave]["no_CFDI"].push(i)
                    else
                        clones[llave]["CFDI"].push(i)

                }
            }

            console.log("CLONES: ")
            console.log(clones)


            let l;
            let banderaClones=true
            //ESTE CICLO VALIDA QUE LOS CLONES TENGA A SU ESPEJO CON CFDI Y
            for(l=0;l<clones["headers"].length;l++)
            {
                let llave=clones["headers"][l]

                let numero_clones_CFDI=clones[llave]["CFDI"].length
                let numero_clones_no_CFDI=clones[llave]["no_CFDI"].length
                let numero_total_clones=numero_clones_CFDI+numero_clones_no_CFDI

                if(numero_total_clones>1)
                {
                    if (numero_clones_CFDI >= numero_clones_no_CFDI)
                    {
                        for(i=0;i<numero_clones_no_CFDI;i++)
                        {
                            let index_CFDI=clones[llave]["CFDI"][i]
                            let index_no_CFDI=clones[llave]["no_CFDI"][i]

                            let folio_clon_CFDI=data[index_CFDI].folio

                            data[index_no_CFDI].folio_nota_credito=folio_clon_CFDI
                            data[index_no_CFDI].validado_CFDI=true
                            data[index_no_CFDI].CFDI=true
                        }
                    }
                    else
                    {
                        banderaClones=false
                    }
                }
            }

            //EN ESTE CICLO SE VALIDA QUE EXISTA EL CFDI, GL Y FACTURA DE CADA REGISTRO
            let banderaCFDI=true;
            let banderaGL=true;
            let banderaFactura=true;

            for (i = j; i < data.length; i++) {
                if ((data[i].CFDI == null || data[i].CFDI == false)&& (data[i].validado_CFDI == false))
                {
                    data[i].CFDI=false
                    banderaCFDI = false
                    let mensajeError="El Registro con Folio: " + data[i].folio + " No tiene CFDI. "
                    imprimirError(mensajeError)
                    data[i].comentarios=data[i].comentarios+mensajeError
                }

                if (data[i].GL == null || data[i].GL == false)
                {
                    data[i].GL=false
                    banderaGL = false
                    let mensajeError="El Registro con Folio: " + data[i].folio + " No tiene GL. "
                    imprimirError(mensajeError)
                    data[i].comentarios=data[i].comentarios+mensajeError
                }


                //if ((data[i].factura == null || data[i].factura == false)&& (data[i].validado_CFDI == false))
                if ((data[i].factura == null || data[i].factura == false))
                {
                    data[i].factura=false
                    banderaFactura = false
                    let mensajeError="El Registro con Folio: " + data[i].folio + " No tiene Factura. "
                    imprimirError(mensajeError)
                    data[i].comentarios=data[i].comentarios+mensajeError
                }
            }

            let result =
                {
                    "cfdi":banderaCFDI,
                    "gl":banderaGL,
                    "clones":banderaClones,
                    "factura":banderaFactura
                }

            resolve(result)

        }
        catch (e)
        {
            console.log("ERROR"+e)
            resolve(false)
        }
    });

}
//FUNCION QUE OBTIENE EL CFDI, GL Y FACTURA
async function getCFDI_Saldo_GL_Factura(data) {

    return new Promise((resolve, reject) =>
    {
        try {
            if (data.folio != "*NONAR") {
                $.ajax({
                    url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/getCFDI_Saldo_GL_Factura.php",
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        "data": JSON.stringify(data)
                    },
                    success: function (result) {

                        if (result.status === 0) {

                            let bandera=true

                            let saldo = result.saldo

                            if (saldo != null)
                            {
                                data["saldo"] = saldo
                                let k=0;
                                let id_saldos=[]
                                for(k=0;k<saldo.length;k++)
                                {
                                    let jsonIds=
                                        {
                                            "id_cobranza":JSON.parse(saldo[k].id_cobranza),
                                            "year":saldo[k].year

                                        }

                                    id_saldos.push(jsonIds)
                                }
                                data["id_saldos"]=id_saldos
                            }
                            else
                            {
                                data["saldo"]=false
                            }

                            if(result.CFDI==false || result.CFDI==null)
                            {
                                bandera=false
                                data["CFDI"]=false
                            }
                            else
                                data["CFDI"] = result.CFDI



                            if(result.GL==false || result.GL==null)
                            {
                                bandera=false
                                data["GL"]=false
                            }
                            else
                            {
                                data["GL"] = result.GL
                                /*
                                let i;
                                for(i=0;i<result.GL.length;i++)
                                {
                                    data["id_gl"].push(result.GL[i].id)
                                }
                                @PZZ
                                 */
                            }


                            if(result.factura==false || result.factura==null)
                            {
                                bandera=false
                                data["factura"]=false
                            }
                            else
                                data["factura"] = result.factura

                            resolve(bandera)
                        }
                        else {
                            resolve(false)
                        }


                    }
                });
            }
            else
            {
                resolve(true)
            }
        }
        catch (e)
        {
            console.log("ERROR"+e)
            resolve(false)
        }
    });
}




//FUNCION QUE GESTIONA LAS VALIDACIONES DE LOS REGISTROS DE CADA BLOQUE
async function validarBloque(data,totalBloques) {

    let grupoLigado= await validarGrupoLigado(data)

    if(grupoLigado==true){
        ligados=ligados+1
        await actualizarContadorValidados(1,0,totalBloques)
        return new Promise((resolve, reject) =>    {
            resolve(true)
        });
    }


    let sumaAritmetica=await validarSumaAritmetica(data)
    if(sumaAritmetica!=true){
        Earitmetico=Earitmetico+1
        await imprimirError("El grupo con check_number: "+data[0].check_number+" No paso la validacion aritmetica")
        await actualizarContadorValidados(0,1,totalBloques)
        return new Promise((resolve, reject) =>    {
            resolve(true)
        });
    }

    let banderaError=false

    let nonar=await validarNonar(data)

    if(nonar.estado_cuenta!=true || nonar.gl!=true){
        banderaError=true
        if(nonar.estado_cuenta!=true)
            EnonarEstado_cuenta=EnonarEstado_cuenta+1
        if(nonar.gl!=true)
            EnonarGL=EnonarGL+1


    }


    let CFDI_GL_Factura_Clones=await validarBloqueCFDI_GL_Factura(data)

    if(CFDI_GL_Factura_Clones.banderaCFDI!=true || CFDI_GL_Factura_Clones.banderaGL!=true || CFDI_GL_Factura_Clones.banderaClones!=true || CFDI_GL_Factura_Clones.banderaFactura!=true){
        banderaError=true
        if(CFDI_GL_Factura_Clones.banderaCFDI!=true)
            Ecfdi=Ecfdi+1
        if(CFDI_GL_Factura_Clones.banderaGL!=true)
            Egl=Egl+1
        if(CFDI_GL_Factura_Clones.banderaClones!=true)
            Eclones=Eclones+1
        if(CFDI_GL_Factura_Clones.banderaFactura!=true)
            Efactura=Efactura+1
    }
    if(banderaError==true)
    {
        await actualizarContadorValidados(0,1,totalBloques)

    }
    else
    {
        console.log("UN GRUPO VALIDO")
        console.log(data)
    }




    console.log("Data despues de la validacion")
    console.log(data)







    /*
                console.log("inicio validarErrorSaldo")
                let Vsaldos=await validarErrorSaldo(data)
                if(Vsaldos!=true)
                {
                    cadenaError=cadenaError+", Validacion de saldos"
                    Esaldos=Esaldos+1
                }
                console.log("fin validarErrorSaldo: "+Vsaldos)

                console.log(data)
                if(sumaAritmetica==true && nonar.gl ==true && nonar.estado_cuenta ==true && CFDI_GL_Factura_Clones.cfdi==true && CFDI_GL_Factura_Clones.gl==true && CFDI_GL_Factura_Clones.factura==true && CFDI_GL_Factura_Clones.clones==true&& Vsaldos==true)
                {
                    await setStatusErroneo(data,0)


                    let subirB=await subirBloque(data)
                    if (subirB==true)
                    {


                        let saldo=await ligarCobro_CFDI(data)
                        if(saldo==true)
                        {
                            bloquesValidadosCorrecto=bloquesValidadosCorrecto+1
                            let actual=bloquesValidadosCorrecto+bloquesValidadosError
                            $("#progresoSubirReporteCobranza" ).html("Bloques validados Correctamente: "+bloquesValidadosCorrecto+ "&nbsp &nbsp Bloques Erroneos: "+bloquesValidadosError+"&nbsp &nbsp Total: "+actual+"/"+totalBloques)
                        }
                        else
                        {
                            console.log("SALDO---p>")
                            console.log(saldo,"pzz")
                            cadenaError=cadenaError+"No se pudieron realizar las Ligas con el CFDI"
                            bandera=false
                        }

                    }
                    else
                    {
                        cadenaError=cadenaError+"No se pudieron cargar los registros a la Base de datos"
                        bandera=false
                    }


                }
                else
                {

                    //actualizar
                    //no hay nada que actualizar, ya estan marcados como error
                    /*
                    await setStatusErroneo(data,1)
                    let subirB=await subirBloque(data)
                    bandera=false

                }

            }








            if(bandera==false)
            {
                bloquesValidadosError=bloquesValidadosError+1
                let actual=bloquesValidadosCorrecto+bloquesValidadosError
                $("#progresoSubirReporteCobranza" ).html("Bloques validados Correctamente: "+bloquesValidadosCorrecto+ "&nbsp &nbsp Bloques Erroneos: "+bloquesValidadosError+"&nbsp &nbsp Total: "+actual+"/"+totalBloques)
                $("#erroresReporteCobranza" ).html("Errores: &nbsp &nbsp   Aritmetico: "+Earitmetico+ "&nbsp &nbsp *NONAR(GL): "+EnonarGL+"&nbsp &nbsp *NONAR(Estado de cuenta): "+EnonarEstado_cuenta+"&nbsp &nbsp CFDI: "+Ecfdi+"&nbsp &nbsp GL: "+Egl+"&nbsp &nbsp Saldos: "+Esaldos+"&nbsp &nbsp Factura: "+Efactura+"&nbsp &nbsp Clones: "+Eclones)


                imprimirError("ERROR AL VALIDAR EL BLOQUE CON: "+cadenaError)
                let i;
                for (i=0;i<data.length;i++)
                {
                    imprimirError("Folio: "+data[i].folio+"&nbsp &nbsp &nbsp"+"Deposit date: "+data[i].deposit_date+"&nbsp &nbsp &nbsp"+"Check number: "+data[i].check_number+"&nbsp &nbsp &nbsp"+"Payment amount: "+data[i].payment_amount, "#FDEDEC")
                }
                imprimirError("...")
            }

        }

    */


    return new Promise((resolve, reject) =>    {
        resolve(true)
    });





}





//FUNCION QUE DETERMINA SI HAY UN ERROR EN ERROR CON EL IMPORTE ACUMULADO(SALDOS) Y EL IMPORTE DEL CFDI
async function validarErrorSaldo(data)
{

    let exist=await existReporteCobranza(data[0].uuid)
    let bandera=true
    try
    {
        if(exist==true)
            bandera=true
        else
        {
            let i;


            let j = 0
            if (data[0].folio == "*NONAR") {
                j = 1
            }

            //EN ESTE CICLO SE VALIDA QUE LOS VALORES SEAN MENOR O IGUAL AL VALOR DEL CFDI
            for (i = j; i < data.length; i++) {

                //if(data[i].CFDI!=null && data[i].GL!=null)
                if(data[i].CFDI!=null)
                {
                    if (data[i].saldo != null)
                    {

                        let k=0;
                        let saldo=0;
                        for(k=0;k<data[i].saldo.length;k++)
                        {
                            saldo=saldo+parseFloat(data[i].saldo[k].actual)
                        }

                        let cfdi = parseFloat(data[i].CFDI.total)
                        let payment_amount = data[i].payment_amount

                        if (Math.abs(saldo) + Math.abs(payment_amount) > cfdi + epsCFDI) {

                            let mensajeError=("El Registro con Folio: "+data[i].folio+" tiene el siguiente error: Saldo+Payment Amount>cfdi.total.  Saldo:"+Math.abs(saldo)+", Payment Amount:"+Math.abs(payment_amount)+", cfdi.total: "+cfdi+". ")
                            data[i].comentarios=data[i].comentarios+mensajeError
                            imprimirError(mensajeError)
                            //PZZ
                            //MARCAR LOS REGISTROS QUE ESTEN EN LOS IDS id_saldos COMO ERRONEOS
                            //PONER UN COMENTARIO QUE DIGA, EL IMPORTE USANDO LOS SALDOS ES INCORRECTO
                            let idsCobranza=data[i].id_saldos
                            await setErrorImporte(data[i].folio, idsCobranza)
                            console.log("ERROR IMPORTE DETECTADO")


                            bandera = false

                        }

                    }
                    else
                    {
                        let cfdi = parseFloat(data[i].CFDI.total)
                        let payment_amount = data[i].payment_amount

                        if (Math.abs(payment_amount) > cfdi + epsCFDI)
                        {
                            let mensajeError="El Registro con Folio: "+data[i].folio+" tiene el siguiente error: Payment Amount>cfdi.total.  Payment Amount:"+Math.abs(payment_amount)+", cfdi.total: "+cfdi+". "
                            data[i].comentarios=data[i].comentarios+mensajeError
                            imprimirError(mensajeError)
                            bandera = false
                        }

                    }
                }
            }



        }


    }
    catch (e)
    {
        console.log(e)
        console.log(data)
        bandera=false
    }


    return new Promise((resolve, reject) =>
    {

        resolve(bandera)
    });
}

//LE ASIGNA UN ESTADO A CADA REGISTRO DEL BLOQUE QUE SE MANDA
async function setStatusErroneo(data,status) {
    try
    {
        let i
        for(i=0;i<data.length;i++)
        {
            data[i].erroneo=status
        }
    }
    catch (e)
    {
        console.log("ERROR"+e)

    }
    return new Promise((resolve, reject) =>    {
        resolve(true)
    });
}
//LE ASIGNA UN COMENTARIO A CADA REGISTRO DEL BLOQUE QUE SE MANDA
async function setComentario(data,mensaje) {
    try
    {
        let i
        for(i=0;i<data.length;i++)
        {
            data[i].comentarios=data[i].comentarios+mensaje
        }
    }
    catch (e)
    {
        console.log("ERROR"+e)

    }
    return new Promise((resolve, reject) =>    {
        resolve(true)
    });
}
//FUNCION QUE GESTIONA LA SUBIDA DEL BLOQUE
async function subirBloque(data) {
    let bandera=true
    try
    {
        let i

        for(i=0;i<data.length;i++)
        {
            let subirC=await subirCobro(data[i])
            if(subirC==false)
                bandera=false
        }
    }
    catch (e)
    {
        console.log("ERROR"+e)

    }
    return new Promise((resolve, reject) =>    {
        resolve(bandera)
    });
}
//FUNCION QUE SUBE UN REGISTRO
async function subirCobro(data){

    data["id_gl"]=JSON.stringify(data["id_gl"])
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/subirCobro.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(data)
                },
                success: function (result) {

                    if (result.status === 0)
                    {
                        data.id_cobranza=parseInt(result.id)
                        resolve(parseInt(result.id))
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
//FUNCION QUE GESTIONA LA LIGA DE LOS ID CON EL CFDI
async function ligarCobro_CFDI(data) {
    try
    {
        let ligarCFDI_response=true
        let saldo1=true

        let fechaCobro=null
        let idBloqueCobranza={}

        let i=0;
        let bandera = true
        let j = 0

        for (i = 0; i < data.length; i++) {
            data[i].id_saldos.push(data[i].id_cobranza)
            idBloqueCobranza[data[i].id_cobranza]=data[i].folio
        }

        j=0
        if (data[0].folio == "*NONAR") {
            data[0].id_bloqueCobranza=idBloqueCobranza
            if(data[0].estado_cuenta!=null )
                if(data[0].estado_cuenta.hasOwnProperty("fecha"))
                    fechaCobro=data[0].estado_cuenta.fecha
            j = 1

            await registroLigado(data[0].uuid)
        }



        for (i = j; i < data.length; i++)
        {
                let ligado=await registroLigado(data[i].uuid)
                if(ligado==false && data[i].validado_CFDI==false)
                {
                    let idsBloquesCobranza=[]   //ESTE ARRAY ES SOLO PARA CFDI, CONSERVA EL idbloqueCobranza DE TODOS LOS REGISTROS
                    data[i].id_bloqueCobranza=idBloqueCobranza
                    if (data[i].saldo != null)
                    {
                        let saldo =  Math.abs(parseFloat(data[i].saldo.actual))
                        let cfdi = Math.abs(parseFloat(data[i].CFDI.total))
                        let payment_amount = Math.abs(data[i].payment_amount)

                        let valor=Math.abs(saldo) + Math.abs(payment_amount)

                        //SE COMPLETO EL COBRO USANDO LOS SALDOS
                        if ((valor >= cfdi-epsCFDI) && (valor<=cfdi+epsCFDI))
                        {
                            let borrar_saldo=
                                {
                                    id:data[i].saldo.id,
                                    folio: data[i].folio,
                                }
                            if(data[i].CFDI.id_bloque_cobranza!=null)
                                idsBloquesCobranza=JSON.parse(data[i].CFDI.id_bloque_cobranza)
                            idsBloquesCobranza.push(idBloqueCobranza)
                            let ligar_cfdi=
                                {
                                    id_bloque_cobranza:JSON.stringify(idsBloquesCobranza),
                                    id_cobros:JSON.stringify(data[i].id_saldos),
                                    fecha_cobro:fechaCobro,
                                    id_cfdi:data[i].CFDI.id

                                }

                            //ligar CFDI CON id_bloque y id_saldos
                            saldo1=await borrarSaldo(borrar_saldo)
                            ligarCFDI_response=await ligarCFDI(ligar_cfdi)
                            let cobroCompleto=await setCobroCompleto(data[i].folio,data[i].id_saldos)

                        }
                        //SE ABONA AL SALDO
                        else
                        {
                            let actualizar_saldo=
                                {
                                    id:data[i].saldo.id,
                                    folio: data[i].folio,
                                    actual: saldo+payment_amount,
                                    total: cfdi,
                                    id_cobranza:JSON.stringify(data[i].id_saldos)
                                }


                            if(data[i].CFDI.id_bloque_cobranza!=null)
                                idsBloquesCobranza=JSON.parse(data[i].CFDI.id_bloque_cobranza)
                            idsBloquesCobranza.push(idBloqueCobranza)
                            let ligar_cfdi=
                                {
                                    id_bloque_cobranza:JSON.stringify(idsBloquesCobranza),
                                    id_cobros:JSON.stringify(data[i].id_saldos),
                                    fecha_cobro:fechaCobro,
                                    id_cfdi:data[i].CFDI.id

                                }
                            //ligar CFDI CON id_bloque y id_saldos
                            saldo1=await actualizarSaldo(actualizar_saldo)
                            ligarCFDI_response=await ligarCFDI(ligar_cfdi)
                        }

                    }
                    else
                    {
                        let cfdi = Math.abs(parseFloat(data[i].CFDI.total))
                        let payment_amount = Math.abs(data[i].payment_amount)

                        let valor=Math.abs(payment_amount)

                        //SE COMPLETO EL MONTO CON UNA SOLA COBRANZA
                        if ((valor >= cfdi-epsCFDI) && (valor<=cfdi+epsCFDI))
                        {

                            if(data[i].CFDI.id_bloque_cobranza!=null)
                                idsBloquesCobranza=JSON.parse(data[i].CFDI.id_bloque_cobranza)
                            idsBloquesCobranza.push(idBloqueCobranza)
                            let ligar_cfdi=
                                {
                                    id_bloque_cobranza:JSON.stringify(idsBloquesCobranza),
                                    id_cobros:JSON.stringify(data[i].id_saldos),
                                    fecha_cobro:fechaCobro,
                                    id_cfdi:data[i].CFDI.id

                                }
                            //ligar CFDI CON id_bloque y id_saldos
                            ligarCFDI_response=await ligarCFDI(ligar_cfdi)
                            let cobroCompleto=await setCobroCompleto(data[i].folio,data[i].id_saldos)

                        }
                        else
                        {
                            //crear saldo con
                            let new_saldo=
                                {
                                    folio: data[i].folio,
                                    actual: payment_amount,
                                    total: cfdi,
                                    id_cobranza:JSON.stringify(data[i].id_saldos)
                                }
                            if(data[i].CFDI.id_bloque_cobranza!=null)
                                idsBloquesCobranza=JSON.parse(data[i].CFDI.id_bloque_cobranza)
                            idsBloquesCobranza.push(idBloqueCobranza)
                            let ligar_cfdi=
                                {
                                    id_bloque_cobranza:JSON.stringify(idsBloquesCobranza),
                                    id_cobros:JSON.stringify(data[i].id_saldos),
                                    fecha_cobro:fechaCobro,
                                    id_cfdi:data[i].CFDI.id

                                }
                            saldo1=await crearSaldo(new_saldo)
                            ligarCFDI_response=await ligarCFDI(ligar_cfdi)
                        }
                    }
                }
                else
                {
                    console.log("YA SE LIGO EL REGISTRO")
                    console.log("------------------------")
                    console.log(data[i])
                    console.log("------------------------")
                }
        }



        return new Promise((resolve, reject) =>
        {
            if(ligarCFDI_response==true && saldo1==true && bandera==true)
                resolve(true)
            else
                resolve(false)
        });




    }catch (e) {
        console.log(e)
        console.log(data)
    }



}
//FUNCION QUE DETERMINA SI UN REGISTRO YA SE SUBIO
async function existReporteCobranza(uuid){


    return new Promise((resolve, reject) =>
    {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/existReporteCobranza.php",
            dataType: 'json',
            data: {"uuid": uuid},

            success: function (result) {
                console.log(result)
                if (result.status === 0)
                {
                    //ES UN REGISTRO QUE YA SE SUBIO
                    resolve(true)

                }
                else
                {
                    //NO SE HA SUBIDO EL REGISTRO
                    resolve(false)
                }

            }
        });
    });


}
//VERIFICA SI UN REGISTRO ESTA LIGADO Y LO MARCA COMO LIGADO
async function registroLigado(uuid){


    return new Promise((resolve, reject) =>
    {
        $.ajax({
            url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/registroLigado.php",
            dataType: 'json',
            data: {"uuid": uuid},

            success: function (result) {
                console.log(result)
                if (result.status === 0)
                {
                    //ES UN REGISTRO QUE AUN NO SE HA LIGADO
                    resolve(false)

                }
                else
                {
                    //ES UN REGISTRO QUE YA SE LIGO
                    resolve(true)
                }

            }
        });
    });


}
//ACTUALIZA LA TABLA SALDO
async function actualizarSaldo(data) {
        return new Promise((resolve, reject) =>
        {
            try{
                $.ajax({
                    url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/actualizarSaldo.php",
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        "data":JSON.stringify(data)
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
//CREA UN NUEVO REGISTRO EN LA TABLA SALDO
async function crearSaldo(data) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/crearSaldo.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(data)
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
//BORRA UN REGISTRO DE LA TABLA SALDOS
async function borrarSaldo(data) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/borrarSaldo.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(data)
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
//FUNCION QUE LIGA EL CFDI CON LOS IDS
async function ligarCFDI(data) {
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/ligarCFDI.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    "data":JSON.stringify(data)
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
//FUNCION QUE ASIGNA COMO COMPLETO A LAS FACTURAS Y COBRANZA
async function setCobroCompleto(folio,idsCobros) {

    let idsSQL=await arrayToSQL(idsCobros)
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/setCobroCompleto.php",
                dataType: 'json',
                data: {"folio": folio, "ids": idsSQL},
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
//FUNCION QUE MARCA COMO ERRONEOS LOS REGISTROS QUE PASARON EL IMPORTE DEL CFDI
async function setErrorImporte(folio,idsCobros) {

    let idsSQL=await arrayToSQL(idsCobros)
    return new Promise((resolve, reject) =>
    {
        try{
            $.ajax({
                url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/reporteCobranza/setErrorImporte.php",
                dataType: 'json',
                data: {"folio": folio, "ids": idsSQL},
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
//PARCEA UN ARRAY PARA USAR EN SQL
async function arrayToSQL(array) {

    return new Promise((resolve, reject) =>
    {
    let temp=JSON.stringify(array)
    temp=temp.replace('[','(');
    temp=temp.replace(']',')');

    resolve(temp)
    });


}
//VALIDA EL FORMATO NUMERO DECIMAL
function validarNumeroDecimal(valor){
    var RE = /^[+-]?[0-9]{1,15}(?:.[0-9]{1,2})?$/;

    if (RE.test(valor))
        return true;
    else
    {
        return false;}

}
//ACTUALIZA LA PANTALLA
function actualizar(){
    location.reload();
}
//DELAY
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
//OBTIENE EL REPORTE DE COBRANZA
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
//GESTIONA EL TIPO DE BUSQUEDA
function tipo_busqueda(tipo) {
    tipoBusqueda=tipo
}
//GENERA EL FORMATO DE CONTADURIA
function formatoNumerico(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
//IMPRIME LA BUSQUEDA DE LA DB
function imprimirVerReporteCobranza(tabla)
{
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
            "<td class='left-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].invoice_amount) + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].payment_amount) + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + formatoNumerico(tabla[i].invoice_balance) + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].age_ays + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].gl_account + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].comments + "</td>" +
            "<td class='left-align' bgcolor='#BEFBB9'>" + tabla[i].id_cuenta + "</td>" +
            "</tr>"
        );
    }
    $('#tablaVerReporteCobranza').DataTable({"pageLength": 1001});

    $("#msgDivVerReporteCobranza").html("");


}


