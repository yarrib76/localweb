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
                                            <a href="/facturados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
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
                                            <a href="/facturados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div id = "RecuadroPanel" class="panel panel-primary">
                                <div id = "Operativa" class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-12 text-left">
                                            <div class="huge">
                                                <h4>Venta Salon</h4> <h4>2</h4>
                                                <h4>Pededidos Facturados</h4> <h4>5</h4>
                                                <h4>Pedidos Pasados</h4> <h4>2</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="jobs-wrapper">
                                    <a href="#">
                                        <div class="panel-footer" data-panel="job-details">
                                            <span class="pull-left">Operativa</span>
                                            <a href="/facturados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
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
                                <div id = "RecuadroPanel" class="panel panel-primary">
                                    <div id = "Operativa" class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-12 text-left">
                                                <div class="huge">
                                                    <h4>Venta Salon</h4> <h4>2</h4>
                                                    <h4>Pededidos Facturados</h4> <h4>5</h4>
                                                    <h4>Pedidos Pasados</h4> <h4>2</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="jobs-wrapper">
                                        <a href="#">
                                            <div class="panel-footer" data-panel="job-details">
                                                <span class="pull-left">Operativa</span>
                                                <a href="/facturados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
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
    </div>
    <style>
        #Empaquetados{
            background: #36ffb1;
            color: #fff;
            width: 278px;
        }
        #CarritosAbandonados{
            background: #28b3ff;
            color: #fff;
            width: 278px;
        }
        #Operativa{
            background: #3526ff;
            color: #fff;
            width: 278px;
        }
        #jobs-wrapper {
            width: 278px;
        }
        #RecuadroPanel {
            width: 280px;
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
        #tres{ border:1px solid black;
            width:49.5%;
            display:inline-block;
            height:49.5%;
            background-color:yellow;
        }
        #cuatro{ border:1px solid black;
            width:49.5%;
            display:inline-block;
            height:49.5%;
            background-color:red;
        }
    </style>
@stop
<script type="text/javascript" src="../../js/charts/loader.js"></script>
@section('extra-javascript')
    <script type="text/javascript">
        var cantFactPorPedidos = 0
        var cantFactPorSalon = 0
        $(document).ready( function () {
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
        })
    </script>
@stop