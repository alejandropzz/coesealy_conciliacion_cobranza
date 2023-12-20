function getAuditTracking(period, year){
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/backend/administration/api/getAudit.php",
        dataType: "json",
        data: {"period":period,"year":year},
        beforeSend: function () {
            indicateProgress("msgDivAuditing");
        },
        success: function(result){
            console.log(result);
            if(result.status === 0)
                printResults(result);
            else 
                mostrarMensaje("red","Errore al obtener los resultados", "msgDivAuditing");
        },error: function(errorSet){
            console.log(errorSet);
            mostrarMensaje("red","Errore al obtener los resultados","msgDivAuditing");
        }
    });
}

function printResults(result) {
    result.auditTrackRecords.forEach(function(audit) {
        var auditP, auditY;

        if(!audit.period)
            audit.period="";
        if(!audit.year)
            audit.year="";

        $("#tblBodyAuditing").append(
            "<tr>"+
                "<td>"+audit.user+"</td>"+
                "<td>"+audit.status+"</td>"+
                "<td>"+audit.action+"</td>"+
                "<td>"+audit.transactionData+"</td>"+
                "<td>"+audit.detail+"</td>"+
                "<td>"+audit.date+"</td>"+
                "<td>"+audit.period+"</td>"+
                "<td>"+audit.year+"</td>"+
            "</tr>"
        );
    });

    $('#tblAuditing').DataTable({"pageLength": result.auditTrackRecords.length});

    $("#msgDivAuditing").html("");
}