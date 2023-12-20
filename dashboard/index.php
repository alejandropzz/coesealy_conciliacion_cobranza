<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="Platinum Flowers Web Platform, Developed by Inncosys">
        <title>Contabilidad Seally</title>


        <script src="https://use.fontawesome.com/f3543d463c.js"></script>
        <link href="/coesealy-conciliacion-cobranza/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="/coesealy-conciliacion-cobranza/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
        <!-- Custome CSS-->    
        <link href="/coesealy-conciliacion-cobranza/css/inncosys.css" type="text/css" rel="stylesheet" media="screen,projection">


        <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->

        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/jquery-1.11.2.min.js"></script>

        <link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet"/>
        <link href="/coesealy-conciliacion-cobranza/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="/coesealy-conciliacion-cobranza/js/plugins/json/stylesheets/stylesheet.css" type="text/css" rel="stylesheet" >

        <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
        <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
    </head>

    <body>
        <!-- Start Page Loading -->
        <div id="loader-wrapper">
            <div id="loader"></div>        
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
        <!-- End Page Loading -->

        <!-- START HEADER -->
        <header id="header" class="page-topbar">
            <!-- start header nav-->
            <div class="navbar-fixed">
                <nav class="navbar-color amber lighten-1">
                    <div class="nav-wrapper">
                        <ul class="left">                      
                            <li><h1 class="logo-wrapper" style="margin-left: 40px;"><a href="/coesealy-conciliacion-cobranza/dashboard/" class="brand-logo darken-1"> COE Seally</a> </h1></li>
                        </ul>
                        <div class="header-search-wrapper hide-on-med-and-down">    
                        </div>
                        <ul class="right hide-on-med-and-down">
                            <li><a href="javascript:void(0);" class="waves-effect waves-block waves-light toggle-fullscreen"><i class="mdi-action-settings-overscan"></i></a>
                            </li>

                        </ul>

                    </div>
                </nav>
            </div>
            <!-- end header nav-->
        </header>

        <!-- START MAIN -->
        <div id="main">
            <!-- START WRAPPER -->
            <div class="wrapper">

                <!-- START LEFT SIDEBAR NAV-->
                <aside id="left-sidebar-nav">
                    <ul id="slide-out" class="side-nav fixed leftside-navigation">

                        <li class="user-details cyan darken-2">
                            <div class="row">

                                <div class="col col l12">
                                    <ul id="profile-dropdown" class="dropdown-content">                                      
                                        <li><a href="/coesealy-conciliacion-cobranza/backend/usuarios/api/logout.php" ><i class="mdi-hardware-keyboard-tab"></i>  Cerrar sesión</a>
                                        </li>
                                    </ul>
                                    <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown" id="nombrePerfil">
                                        <i class="mdi-navigation-arrow-drop-down right"></i><?php echo $_SESSION['name']; ?></a>
                                    <p class="user-roal" id="rolPerfil">Administrator</p>

                                </div>


                            </div>
                            <div class="row">

                                <div class="col col l12">
                                    <ul id="companies-dropdown" class="dropdown-content">                                      

                                    </ul>
                                    <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="companies-dropdown" id="nombreEmpresa">
                                        <i class="mdi-navigation-arrow-drop-down right"></i><?php if(isset($_SESSION['company'])){echo $_SESSION['company'];} ?></a>

                                <a href = "/coesealy-conciliacion-cobranza/dashboard/help" class="waves-effect waves-cyan white-text"><i class="mdi-action-help"></i>Ayuda</a>

                                </div>


                            </div>
                        </li>
                        <li>
                            <ul class="collapsible collapsible-accordion">


                                <li id="menuImportar" class="bold"><a  class="collapsible-header waves-effect waves-cyan"><i class="mdi-content-content-paste"></i>Importar</a>
                                    <div class="collapsible-body">

                                        <!--
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/subir-cuentas-cobrar" >Cargar Facturas</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/verCuentasCobrar" >Consultar Facturas</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/subir_reporte_cobranza" >Cargar Reporte cxc</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/ver_reporte_cobranza" >Consultar Reporte cxc</a></li>
                                        </ul>
                                        -->
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/import_cfdi" >Importar CFDIs CXC</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/import_cfdi_cxp" >Importar CFDIs CXP</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/import_diot" >Importar Diot</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/import_factura" >Importar Facturas</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/import_saldo" >Importar Saldos CXC</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/import_saldo_cxp" >Importar Saldos CXP</a></li>
                                        </ul>
                                        <!--
                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/import_edc" >Importar Estado Cuenta</a></li>
                                </ul>
                                -->
                            </div>
                        </li>
                        <li id="menuConsultar" class="bold"><a  class="collapsible-header waves-effect waves-cyan"><i class="mdi-content-content-paste"></i>Consultar</a>
                            <div class="collapsible-body">

                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/verCfdis" >Cfdis</a></li>
                                </ul>
                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/verCuentasCobrar" >Facturas</a></li>
                                </ul>
                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/ver_armado" >Armado Por Folio</a></li>
                                </ul>
                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/ver_armado_pendiente" >Armados Pendientes</a></li>
                                </ul>
                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/ver_armado_rp" >Armado RP</a></li>
                                </ul>
                                <!--
                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/ver_reporte_cobranza" >Consultar Cobranza</a></li>
                                </ul>
                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/ver_edc" >Consultar Estado Cuenta</a></li>
                                </ul>
                                -->
                            </div>
                        </li>
                        <li id="menuProcesar" class="bold"><a  class="collapsible-header waves-effect waves-cyan"><i class="mdi-content-content-paste"></i>Procesar</a>
                            <div class="collapsible-body">

                                <!--
                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/subir-cuentas-cobrar" >Cargar Facturas</a></li>
                                </ul>
                                <ul>
                                    <li><a  href="/coesealy-conciliacion-cobranza/dashboard/verCuentasCobrar" >Consultar Facturas</a></li>
                                </ul>
                                -->
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/ligar_cobranza" >Consolidar Saldos</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/liga_manual_cfdi_rp" >Crear Saldo RP Manual</a></li>
                                        </ul>
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/ligar_rp" >Consolidar RP</a></li>
                                        </ul>

                                        <!--
                                        <ul>
                                            <li><a  href="/coesealy-conciliacion-cobranza/dashboard/ver_reporte_cobranza" >Consultar Reporte cxc</a></li>
                                        </ul>
                                        -->

                                    </div>
                                </li>

                                <!-- <li id="mnuErrors" class="bold "><a href="/coesealy-conciliacion-cobranza/dashboard/errores" class="waves-effect waves-cyan"><i class="mdi-alert-error"></i> Errores</a> -->
                            </ul>
                        </li>


                    </ul>
                    <div id="btnSlide">
                        <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light black"><i class="mdi-navigation-menu"></i></a>
                    </div>

                </aside>
                <!-- END LEFT SIDEBAR NAV-->

                <!-- START CONTENT -->
                <section id="content">

                </section>
                <!-- END CONTENT -->

            </div>
            <!-- END WRAPPER -->

        </div>
        <!-- END MAIN -->


        <!-- START FOOTER -->
        <footer class="page-footer deep-orange">
            <div class="footer-copyright">
                <div class="container">
                    <span class="right"> Designed and Developed by <a class="grey-text text-lighten-4" href="http://acfi.com.mx/#home/">ACFI</a></span>
                </div>
            </div>
        </footer>
        <!-- END FOOTER -->

        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/materialize.min.js"></script>
        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>

        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/plugins.min.js"></script>
        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/plugins/json/renderjson.js"></script>
        <script type="text/javascript" src="/coesealy-conciliacion-cobranza/js/inncosys.js"></script>
        <script src="/coesealy-conciliacion-cobranza/js/signals.min.js" type="text/javascript"></script>
        <script src="/coesealy-conciliacion-cobranza/js/crossroads.min.js" type="text/javascript"></script>
        <script src="/coesealy-conciliacion-cobranza/js/plugins/jquery.table2excel.js" type="text/javascript"></script>
        <script src="/coesealy-conciliacion-cobranza/js/plugins/decimal.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(function () {
                $.ajax({
                    url: "/coesealy-conciliacion-cobranza/backend/contabilidad/api/getCompanies.php",
                    dataType: 'json',
                    beforeSend: function () {
                        $("#companie-dropdown").html("");
                    },
                    success: function (result) {
                        if (result.status === 0) {
                            result.lstCompanies.forEach(function (company) {
                                console.log(company)
                                $("#companies-dropdown").append("<li><a href='javascript:void(0)' onclick='seleccionarEmpresa(" + company.id + ",\"" + company.name + "\")'><i class='mdi-communication-business'></i>" +
                                        company.name + "</a></li>");
                            });
                        }
                    }
                });
            });
            function seleccionarEmpresa(id, name) {
                $.ajax({
                    url: "/coesealy-conciliacion-cobranza/backend/usuarios/api/selectCompany.php",
                    dataType: 'json',
                    type: 'POST',
                    data: {"id": id, "name": name},
                    success: function (result) {
                        if (result.status === 0) {
                            console.log("success seleccionar empresa");
                            location.reload();
                        }
                    }
                });
            }
            crossroads.addRoute('coesealy-conciliacion-cobranza/dashboard/', function (view) {
                cargarVista("default", null, true);
                $("#btnSlide").remove();
            });
            crossroads.addRoute('coesealy-conciliacion-cobranza/dashboard/{view}', function (view) {
                console.log("recibe del naegadorP cargar: " + view);
                cargarVista(view, null, true);
            });
            var tipoCFDI = "";
            crossroads.addRoute('coesealy-conciliacion-cobranza/dashboard/cfdi/{tipo}', function (tipo) {
                tipoCFDI = tipo
                console.log("recibe del naegadorP cargar: " + tipo);
                cargarVista("cfdi", null, true);
            });
            crossroads.addRoute('coesealy-conciliacion-cobranza/dashboard/cfdi/{tipo}/importar', function (tipo) {
                tipoCFDI = tipo
                console.log("recibe del naegadorP cargar: " + tipo);
                cargarVista("importar-cfdi", null, true);
            });

            crossroads.parse(document.location.pathname);
        </script>

    </body>
</html>