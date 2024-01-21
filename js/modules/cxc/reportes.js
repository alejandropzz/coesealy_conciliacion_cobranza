



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
        years=yearsResult.years

        years.push({year:"2017"})

        let cfdi={}

        for(let i=0;i<years.length;i++)
        {
            let cfdiT=await getCfdiByFolio(folio,years[i].year)
            if(cfdiT.length>0)
                cfdi=cfdiT
        }
        console.log(cfdi)
        console.log("\n")
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