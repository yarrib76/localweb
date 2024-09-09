<div id="mySidebar" class="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle Navigation</span>
        </button>
        <!-- Resto de tu contenido del navbar-header -->
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <!-- Todo el contenido de tu menú aquí -->
        @if (Auth::check())
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle fa fa-btn" data-toggle="dropdown">
                        Reportes <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/reporteArticulo?anio="> Graficos </a></li>
                        <li><a href="/reporteArticuloProveedor"> Articulos/Proveedor </a></li>
                        <li><a href="/transferenciasarticulos"> Transferencias </a></li>
                        <li><a href="/reportesArticulosWeb"> ArticulosWeb </a></li>
                        <li><a href="/artimasVendidos"> ArticulosMasVendidos </a></li>
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Pedidos</a>
                            <ul class="dropdown-menu">
                                <li><a href="/panelPedidos">Todos</a></li>
                                <li class="dropdown-submenu">
                                    <a tabindex="-1" href="#">Envios</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/miCorreo">Exportar</a></li>
                                        <li><a href="/indexImport">ImportarSucursales</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="/reportevendedoras"> Vendedoras </a></li>
                        <li><a href="/notasadmin">Administrar Notas</a></li>
                        <li><a href="/panelcontrol"> DashBoard </a></li>
                        <li><a href="/abmpersonal"> Personal </a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle fa fa-btn" data-toggle="dropdown">
                        Articulos <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                        <li><a href="/articulos"> ABM</a></li>
                        <li><a href="/sincroArticulos"> SincronizacionLocales</a></li>
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Ordenes de Compra</a>
                            <ul class="dropdown-menu">
                                <li><a href="/ordenesCompras">Todas</a></li>
                                <li><a href="/ordenescomprascontrol">Control Ordenes</a></li>
                            </ul>
                        </li>
                        <li><a href="/compraauto"> Compra Automatica</a></li>
                        <li class="divider"></li>
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Modificacion General</a>
                            <ul class="dropdown-menu">
                                <li><a href="/editargeneral"> Por Proveedor</a></li>
                                <li><a href="/importExport"> Importacion EXCEL-CSV</a></li>
                                <li><a href="/cambioPrecios"> Cambio Precio Sistema</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle fa fa-btn" data-toggle="dropdown">
                        Caja <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/facturadorWeb"> Facturas/Pedidos </a></li>
                        <li><a href="/cierreDiario"> Cierres </a></li>
                        <li><a href="/controlcierre"> Verificar Cierres </a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle fa fa-btn" data-toggle="dropdown">
                        Clientes <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/clientes"> ABM</a></li>
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Fidelizacion</a>
                            <ul class="dropdown-menu">
                                <li><a href="/clientesFidelizacion"> Clientes </a></li>
                                <li><a href="/clientesFidelizacion/setParametros"> Configuracion </a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle fa fa-btn" data-toggle="dropdown">
                        BI <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                        <li class="divider"></li>
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Clientes</a>
                            <ul class="dropdown-menu">
                                <li><a href="/api/biclientes">Ranking Ventas </a></li>
                                <li><a href="/api/biseguimiento">Seguimiento Clientes </a></li>
                            </ul>
                        </li>
                        <li><a href="/mapa"> Mapa Region </a></li>
                        <li><a href="/articulosclientes"> Articulos-Clientes </a></li>
                        <li><a href="/artremanentes"> Articulos Remanentes </a></li>
                        <li><a href="/consultavendedoras"> Vendedoras </a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle fa fa-btn" data-toggle="dropdown">
                        Contabilidad <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/reportefacturas"> Facturas</a></li>
                        <li><a href="/reporteFinanciero"> Reporte Financiero</a></li>
                        <li><a href="/reportesalonpedidos"> Reporte Ventas Pedidos</a></li>
                        <li><a href="/reporteinversion"> Reporte Inversion</a></li>
                    </ul>
                </li>
                <!--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle fa fa-btn" data-toggle="dropdown">
                        Promociones <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/panelpromocion"> Panel </a></li>
                    </ul>
                </li>
                !-->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle fa fa-btn" data-toggle="dropdown">
                        E-Comerce <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                        <li class="divider"></li>
                        <li><a href="/consultaecomerce"> Panel </a></li>
                        <li><a href="/autosinc"> Replica Automatica </a></li>
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Ordenes Tienda Nube</a>
                            <ul class="dropdown-menu">
                                <li><a href="/ordenestiendanube"> Replica Ordenes TN </a></li>
                                <li><a href="/asignaciongeneral"> Asignacion Pedidos </a></li>
                            </ul>
                        </li>
                        <li><a href="/tiendanubeGetArticulosInbox"> Exportar Articulos A Excel </a></li>
                        <li><a href="/carritosAbandonados"> Gestion Carritos Abandonados </a></li>
                    </ul>
                </li>

                <li class="dropdown ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <span id="alertCount" class="badgeAlertas"  onclick="notificaciones()">0</span><!-- Número de notificaciones -->
                    </a>
                </li>
            </ul>
            <style>
                .badgeAlertas {
                    position: absolute;
                    top: 10px; /* Ajusta la posición vertical del contador */
                    right: -10px; /* Ajusta la posición horizontal del contador */
                    background-color: red;
                    color: white;
                    border-radius: 50%;
                    font-size: 12px;
                    padding: 1px 4px;
                }
            </style>
            @include('partials.header.notificaciones')
        @endif
    </div>
</div>

<!-- Botón para abrir la sidebar -->
<button class="openbtn" onclick="openNav()">?</button>

<style>
    /* El contenedor de la sidebar */
    .sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #111;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }

    /* Estilo de los enlaces dentro del sidebar */
    .sidebar a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
        transition: 0.3s;
    }

    /* Cambiar color al pasar sobre el enlace */
    .sidebar a:hover {
        color: #f1f1f1;
    }

    /* Botón de cierre de la sidebar */
    .sidebar .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    /* Botón de apertura del sidebar */
    .openbtn {
        font-size: 20px;
        cursor: pointer;
        background-color: #111;
        color: white;
        padding: 10px 15px;
        border: none;
    }

    .openbtn:hover {
        background-color: #444;
    }

    /* Mueve el contenido de la página hacia la derecha cuando se abre la sidebar */
    #main {
        transition: margin-left .5s;
        padding: 16px;
    }

</style>

<script type="text/javascript">
    function openNav() {
        document.getElementById("mySidebar").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
    }

</script>