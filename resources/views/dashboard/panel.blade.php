@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div id="uno">
            <div class="panel-body">
                <table id="reporte" class="table table-striped table-bordered records_list">
                    <tbody>
                    <tr>
                        <td>
                            <div id = "RecuadroPanel" class="panel panel-primary">
                                <div id = "Empaquetados" class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-12 text-left">
                                            <div class="huge">
                                                <h4>Vencidos</h4> <h4 id="empaquetados_Vencidos"></h4>
                                                <h4>Pendientes</h4> <h4 id="empaquetados_Pendientes"></h4>
                                                <h4>Sin Transporte</h4> <h4 id="empaquetados_Sin_Transporte"></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="jobs-wrapper">
                                    <a href="#">
                                        <div class="panel-footer" data-panel="job-details">
                                            <span class="pull-left">Empaquetados</span>
                                            <a href="/empaquetados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div id = "RecuadroPanel" class="panel panel-primary">
                                <div id = "CarritosAbandonados" class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-12 text-left">
                                            <div class="huge">
                                                <h4>Sin Asignar</h4> <h4 id="carritosAbandonadosSinAsignar"></h4>
                                                <h4>Pendientes</h4> <h4 id="carritosAbandonadosPendientes"></h4>
                                                <h4>Sin Notas</h4> <h4 id="carritosAbandonadosSinNotas"></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="jobs-wrapper">
                                    <a href="#">
                                        <div class="panel-footer" data-panel="job-details">
                                            <span class="pull-left">Carritos Abandonados</span>
                                            <a href="/carritosAbandonados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div id = "RecuadroPanel" class="panel panel-primary">
                                <div id = "Operativa" class="panel-heading">
                                    <div id="example-table"></div>
                                </div>
                                <div id="jobs-wrapper">
                                    <a href="#">
                                        <div class="panel-footer" data-panel="job-details">
                                            <span class="pull-left">Pedidos</span>
                                            <a href="/reportevendedoras" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                     </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="dos">
            <div class="panel-body">
                <table id="reporte" class="table table-striped table-bordered records_list">
                    <tbody>
                        <tr>
                            <td>
                                <h2 style="text-align: center">Contadores Operativos</h2>
                                <div id="chart_div" style="width: 400px; height: 120px;"></div>
                            </td>
                            <td>
                                <h2 style="text-align: center">Pedidos Pendientes</h2>
                                <h1 id="pedidosPendientes"></h1>
                            </td>
                            <td>
                                <h2 style="text-align: center">Pedidos Pasados</h2>
                                <h1 id="pedidosPasados"></h1>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="tres">
            <div class="panel-body">
                <table id="reporteFidel" class="table table-striped table-bordered records_list">
                    <tbody>
                    <tr>
                        <td>
                            <div id = "RecuadroPanelClienteFidel" class="panel panel-primary">
                                <div id = "ClientesFidel" class="panel-heading">
                                    <div id="cliente_fidel_table"></div>
                                </div>
                                <div id="jobs-wrapperClienteFidel">
                                    <a href="#">
                                        <div class="panel-footer" data-panel="job-details">
                                            <span class="pull-left">Fidelizacion</span>
                                            <a href="/clientesFidelizacion" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
            </div>
        </div>
    </div>
    <style>
        #Empaquetados{
            background: #36ffb1;
            color: #05353a;
            width: 350px;
        }
        #CarritosAbandonados{
            background: #28b3ff;
            color: #05353a;
            width: 350px;
        }
        #Operativa{
            background: #3526ff;
            color: #fff;
            width: 350px;
        }
        #ClientesFidel{
            background: #3526ff;
            color: #fff;
            width: 432px;
        }
        #empaquetados_Vencidos{
            color: #ff1a0e;
        }
        #carritosAbandonadosSinAsignar{
            color: #ff1a0e;
        }
        #jobs-wrapper {
            width: 350px;
        }
        #jobs-wrapperClienteFidel {
            width: 431px;
        }
        #RecuadroPanel {
            width: 353px;
        }
        #RecuadroPanelClienteFidel{
            width: 433px;
        }
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: silver;
        }
        #uno{ border:1px solid black;
            width:100%;
            display:inline-block;
            margin:auto;
            height:45.5%;
            background-color: #caffca;
        }
        #dos{ border:1px solid black;
            width:100%;
            display:inline-block;
            height:45.5%;
            background-color:green;
        }
        #pedidosPendientes {
            font-weight: bold;
            color: rgba(28, 64, 33, 0.98);
            font-size: 140px;
            text-align: center;
        }
        #pedidosPasados {
            font-weight: bold;
            color: rgba(64, 9, 21, 0.98);
            font-size: 140px;
            text-align: center;
        }
        #tres{ border:1px solid black;
            width:49.5%;
            display:inline-block;
            height:49.5%;
            background-color: #d2ff95;
        }
        #cuatro{ border:1px solid black;
            width:49.5%;
            display:inline-block;
            height:49.5%;
            background-color:red;
        }
    </style>
@stop

@section('extra-javascript')
    <link rel="stylesheet" href="../../js/tabulador/tabulator.css">
    <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script>
    <script type="text/javascript" src="../../js/charts/loader.js"></script>

    <script type="text/javascript">
        idleTimer = null;
        idleState = false;
        idleWait = 20000;

        (function ($) {

            $(document).ready(function () {
                recargaDatos()
                llenarTabla()
                $('*').bind('mousemove keydown scroll', function () {

                    clearTimeout(idleTimer);

                    if (idleState == true) {

                        // Reactivated event
                    }

                    idleState = false;

                    idleTimer = setTimeout(function () {

                        // Idle Event
                        recargaDatos()

                        idleState = true; }, idleWait);
                });

                $("body").trigger("mousemove");

            });
        }) (jQuery)
        function recargaDatos(){
            $.ajax({
                url: "/consultaempaquetados",
                dataType: "json",
                success: function (json) {
                    document.getElementById('empaquetados_Vencidos').textContent = (json['empaquetadosVencidos']);
                    document.getElementById('empaquetados_Pendientes').textContent = (json['empaquetadosPendientes']);
                    document.getElementById('empaquetados_Sin_Transporte').textContent = (json['empaquetadosSinTransporte']);
                }
            });
            $.ajax({
                url: "/consultacarritosabandonados",
                dataType: "json",
                success: function (json) {
                    document.getElementById('carritosAbandonadosSinAsignar').textContent = (json['carritosAgandonadosSinAsignar']);
                    document.getElementById('carritosAbandonadosPendientes').textContent = (json['carritosAgandonadosPendientes']);
                    document.getElementById('carritosAbandonadosSinNotas').textContent = (json['carritosAbandonadosSinNotas']);
                }
            });
            $.ajax({
                url: '/relojesoperativos',
                'method': "GET",
                'contentType': 'application/json',
                success: function (json) {
                    google.charts.load('current', {'packages':['gauge']});
                    google.charts.setOnLoadCallback(drawChart);
                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['Label', 'Value'],
                            ['Salon', json['cantidadVentasSalon'][0]['cantidad']],
                            ['Facturados', json['cantidadPedidosFacturados'][0]['cantidad']],
                            ['Pasados', json['cantPedidosPasados'][0]['cantPedidos']]
                        ]);

                        var options = {
                            width: 500, height: 150,
                            redFrom: 0,redTo: 15,
                            yellowFrom:16, yellowTo: 25,
                            greenFrom:26, greenTo: 100,
                            minorTicks: 5
                        };
                        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));
                        chart.draw(data, options);
                        document.getElementById('pedidosPasados').textContent = (json['cantPedidosPasados'][0]['cantPedidos']);
                    }
                },
            })
            $.ajax({
                url: "api/pedidosPendientes",
                dataType: "json",
                success: function (json) {
                    document.getElementById('pedidosPendientes').textContent = (json[0]['pedidosPendientes']);
                }
            });
        }
        //custom max min header filter
        var minMaxFilterEditor = function(cell, onRendered, success, cancel, editorParams){

            var end;

            var container = document.createElement("span");

            //create and style inputs
            var start = document.createElement("input");
            start.setAttribute("type", "number");
            start.setAttribute("placeholder", "Min");
            start.setAttribute("min", 0);
            start.setAttribute("max", 100);
            start.style.padding = "4px";
            start.style.width = "50%";
            start.style.boxSizing = "border-box";

            start.value = cell.getValue();

            function buildValues(){
                success({
                    start:start.value,
                    end:end.value,
                });
            }

            function keypress(e){
                if(e.keyCode == 13){
                    buildValues();
                }

                if(e.keyCode == 27){
                    cancel();
                }
            }

            end = start.cloneNode();

            start.addEventListener("change", buildValues);
            start.addEventListener("blur", buildValues);
            start.addEventListener("keydown", keypress);

            end.addEventListener("change", buildValues);
            end.addEventListener("blur", buildValues);
            end.addEventListener("keydown", keypress);


            container.appendChild(start);
            container.appendChild(end);

            return container;
        }

        //custom max min filter function
        function minMaxFilterFunction(headerValue, rowValue, rowData, filterParams){
            //headerValue - the value of the header filter element
            //rowValue - the value of the column in this row
            //rowData - the data for the row being filtered
            //filterParams - params object passed to the headerFilterFuncParams property

            if(rowValue){
                if(headerValue.start != ""){
                    if(headerValue.end != ""){
                        return rowValue >= headerValue.start && rowValue <= headerValue.end;
                    }else{
                        return rowValue >= headerValue.start;
                    }
                }else{
                    if(headerValue.end != ""){
                        return rowValue <= headerValue.end;
                    }
                }
            }

            return true; //must return a boolean, true if it passes the filter.
        }

        $("#example-table").tabulator({
            height: "190px",
            initialSort:[
                {column:"EnProceso", dir:"desc"}, //sort by this first
            ],
            columns: [
                {title: "Vendedora", field: "vendedoraConsulta", sortable: true, width: 110},
                {title: "Proceso", field: "EnProceso", sortable: true, width: 90,formatter: function color(cell) {
                    if (cell.getRow().getData()['VencidosEnPreceso'] == 0 && cell.getRow().getData()['NotasVencidosEnPreceso'] == 0) {
                        cell.getElement().css({"background-color": "red"});
                    }
                    return cell.getRow().getData()['EnProceso'];
                }
                },
                {title: "Facturar", field: "ParaFacturar", sortable: true, width: 100, formatter: function color(cell) {
                    if (cell.getRow().getData()['VencidosParaFacturar'] == 0 && cell.getRow().getData()['NotasVencidosParaFacturar'] == 0) {
                        cell.getElement().css({"background-color": "red"});
                    }
                    return cell.getRow().getData()['ParaFacturar'];
                }
                },
            ],

        });
        $("#cliente_fidel_table").tabulator({
            height: "190px",
            initialSort:[
                {column:"Proceso", dir:"desc"}, //sort by this first
            ],
            columns: [
                {title: "Vendedora", field: "Vende", sortable: true, width: 110},
                {title: "Proceso", field: "Proceso", sortable: true, width: 90,bottomCalc:"sum"},
                {title: "Alertados", field: "Alertados", sortable: true, width: 100},
                {title: "Vencidos", field: "Vencidos", sortable: true, width: 100}
            ],

        });

        function llenarTabla() {
            $("#example-table").tabulator("setData", '/tablaPedidos');
            $("#cliente_fidel_table").tabulator("setData", '/tablaClienteFidel');
        }
    </script>
@stop