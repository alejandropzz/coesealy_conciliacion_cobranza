
//CONSTANTES
const  ERROR_FALTARON_PARAMETROS = 1;
const ERROR_INESPERADO_CATCH = 2;
const ERROR_DE_CONEXION_BD = 3;
const ERROR_DATOS_ERRONEOS = 4;
const ERROR_DATO_NO_INSERTADO_ACTUALIZADO_BD = 5;
const ERROR_PRIVILEGIOS = 6;
const WARN_LA_CONSULTA_NO_OBTUVO_RESULTADOS = 16;
//--
var DEBUG = true;
$(document).ready(
        function () {
            if (DEBUG === true) {
                logger.enableLogger();
            } else {
                logger.disableLogger();
            }
        }
);
var logger = function () {
    var oldConsoleLog = null;
    var pub = {};
    pub.enableLogger = function enableLogger() {
        if (oldConsoleLog === null)
            return;
        window['console']['log'] = oldConsoleLog;
    };
    pub.disableLogger = function disableLogger() {
        oldConsoleLog = console.log;
        window['console']['log'] = function () { };
    };
    return pub;
}();



//***PROTOTIPES***//
Date.prototype.yyyymmdd = function() {
  var mm = this.getMonth() + 1; // getMonth() is zero-based
  var dd = this.getDate();

  return [this.getFullYear(),
          (mm>9 ? '' : '0') + mm,
          (dd>9 ? '' : '0') + dd
         ].join('-');
};


$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    for (var i in o) {
        if (o[i] === null || o[i] === undefined || o[i] === "") {
            // test[i] === undefined is probably not very useful here
            delete o[i];
        }
    }
    return o;
};
String.prototype.hashCode = function () {
    var hash = 0, i, chr, len;
    if (this.length === 0)
        return hash;
    for (i = 0, len = this.length; i < len; i++) {
        chr = this.charCodeAt(i);
        hash = ((hash << 5) - hash) + chr;
        hash |= 0; // Convert to 32bit integer
    }
    return hash.toString(16);
};

String.prototype.leftPadder = function(size, sub){
    var self = this;
    var padderSize = size - this.length;    //Size must be greater than str length
    var padder = "";

    console.log(this);

    //Forms pad of the string
    for (let index = 0; index < padderSize; index++) {
        padder+=String(sub); //Force it to be a number in case it aint
    }

    return padder+this;
}

Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
 //***PROTOTIPES-END***//
 
 //***NAVEGATION***//
 window.onpopstate = function (event) {
    console.log("location: " + document.location + ", state: " + JSON.stringify(event.state));
    cargarVista(event.state.location, null, true);
};
function cargarVista(vista, objState, invalidatePushState) {

    if (!objState) {
        objState = {};
    }
    objState.location = vista;
    if (!invalidatePushState)
        history.pushState(objState, vista, vista);
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/views/" + vista + ".html",
        dataType: "text",
        async: true,
        cache: false,
        success: function (result) {

            $("#content").html(result);
        }, error: function (jqXHR, textStatus, errorThrown) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
                $("#content").html("<p>Pagina no encontrada</p>");
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Errore [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Errore.\n' + jqXHR.responseText;
            }
            console.log("error cargar vista: " + msg);
        }
    });

    return false;
}
function cargarPantallaCompleta() {
    var link = document.createElement("link");
    link.href = "/css/layouts/style-fullscreen.css";
    link.type = "text/css";
    link.rel = "stylesheet";
    link.media = "screen,projection";
    document.getElementsByTagName("head")[0].appendChild(link);
   
}
var $_GET = {};
document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
    function decode(s) {
        return decodeURIComponent(s.split("+").join(" "));
    }
    $_GET[decode(arguments[1])] = decode(arguments[2]);
});

//***NAVEGATION-END***//

function mostrarMensaje(color, mensaje, id) {
    $("#" + id).html(
            '<div id="card-alert" class="card ' + color + '">' +
            '<div class="card-content white-text">' +
            '<p>' + mensaje + '</p>' +
            '</div>' +
            '<button type="button" class="close white-text" onclick="$(\'#' + id + '\').html(\'\');">' +
            '<span aria-hidden="true">×</span>' +
            '</button>' +
            '</div>');
}
function indicateProgress(id) {
    $("#" + id).html(
            '<div class="preloader-wrapper small active">' +
            '<div class="spinner-layer spinner-green-only">' +
            '<div class="circle-clipper left">' +
            '<div class="circle"></div>' +
            '</div>' +
            '<div class="gap-patch">' +
            '<div class="circle"></div>' +
            '</div>' +
            '<div class="circle-clipper right">' +
            '<div class="circle"></div>' +
            '</div>' +
            '</div>' +
            '</div>');
}

function indicateProgressP(id, percentage) {
    $("#" + id).html(percentage + " Para seguir trabajando en el sistema abre una <a href='/' target='_blank'>nueva pestaña</a>");
}

function iniciarSesion(form) {
    if ($("#inEmail").val().length > 0 && $("#inPasswd").val().length > 0) {
        form.submit();
    } else {
        console.log("else sesion" + $("#inEmail").val().lengh + " - " + $("#inPasswd").val().lengh);
        mostrarMensaje("red", "Por favor ingrese su usuario y contraseña", "mensajeSesion");
    }
    return false;
}

function toggleVisibility(id){
    console.log(id);
    if($("#"+id).hasClass('hide'))
        $("#"+id).removeClass('hide');
    
    else
        $("#"+id).addClass('hide');
}
 
var hashCuenta = {};
function obtenerCatalogoCuentas() {
    $.ajax({
        url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/getAccounts.php",
        dataType: 'json',
        success: function (result) {
            if (result.status == 0) {
                result.lstAccounts.forEach(function (cuenta) {
                    hashCuenta[cuenta.lawsonAccount] = cuenta.type;
                });
                //console.log(hashCuenta);
                createAccountSelect();
            } else {

            }
        }
    });
}

function tableToExcel(id,tableName,file){
    $("#"+id).table2excel({
        exclude:".notinc",
        name: tableName,
        filename:file
    });
}

function createAccountSelect(){
    console.log(Object.keys(hashCuenta));
    Object.keys(hashCuenta).forEach(function(key){
        $("#accounts").append(
            "<option value='"+key+"'>"+key+"</option>"
        );
    });

    $("#accounts").material_select();
}