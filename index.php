<!DOCTYPE html>
<html lang="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="Skeiron TMS is a fully customized, mobile and logistics oriented web-based platform.">
        <meta name="keywords" content="skeiron, skeiron tms, logistics, tms, transportation system">

        <title>Contabilidad Electronica Sealy</title>


        <!-- CORE CSS-->

        <link href="css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="css/layouts/page-center.css" type="text/css" rel="stylesheet" media="screen,projection">

        <!-- Inncosys CSS-->

        <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
        <link href="/coesealy-conciliacion-cobranza/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">


    </head>

    <body class=" grey lighten-4">
        <!-- Start Page Loading -->
        <div id="loader-wrapper">
            <div id="loader"></div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
        <!-- End Page Loading -->



        <div id="login-page" class="row ">
            <div class="col s12 z-depth-4 card-panel  grey lighten-5">
                <form class="login-form" action="/coesealy-conciliacion-cobranza/backend/usuarios/api/login.php" method="POST" id="frmLogin">
                    <div class="row">
                        <div class="input-field col s12 center">
                            <img src="img/logo.png" alt="" class="logo" responsive-img valign>
                            <p class="center login-form-text">Iniciar Sesión</p>
                        </div>
                    </div>

                    <div class="row margin">
                        <div class="input-field col s12">
                            <i class="mdi-social-person-outline prefix"></i>
                            <input id="inEmail"  name="inEmail"  type="text">
                            <label for="inEmail" class="center-align">Correo:</label>
                        </div>
                    </div>
                    <div class="row margin">
                        <div class="input-field col s12">
                            <i class="mdi-action-lock-outline prefix"></i>
                            <input id="inPasswd" name="inPasswd" type="password">
                            <label for="inPasswd">Contraseña:</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <a href="javascript:void(0);" class="btn waves-effect waves-light col s12" onclick="iniciarSesion($('#frmLogin'))">Entrar</a>
                        </div>

                    </div>
                    <div class="row">

                        <div class="input-field col s12 m12 l12">
                            <p class="margin right-align medium-small">
                                <a href="recuperar-password.html">¿Olvidaste tu contraseña?</a>
                            </p>
                        </div>
                    </div>
                    <div id="mensajeSesion"></div>

                </form>
            </div>
        </div>



        <!-- ================================================
              Scripts
              ================================================ -->
        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/materialize.min.js"></script>
        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>


        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/plugins.min.js"></script>
        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/inncosys.js"></script>

        <script type="text/javascript">
                    if ($_GET['result']) {

                        if ($_GET['result'] == ERROR_FALTARON_PARAMETROS) {
                            mostrarMensaje("red", "Por favor ingrese usuario y contraseña", "mensajeSesion");
                        } else if ($_GET['result'] == ERROR_INESPERADO_CATCH) {
                            mostrarMensaje("red", "Ocurrio un error inesperado", "mensajeSesion");
                        } else if ($_GET['result'] == ERROR_PRIVILEGIOS) {
                            mostrarMensaje("red", "Acceso Denegado", "mensajeSesion");
                        }
                    }
        </script>

    </body>

</html>