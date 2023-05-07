@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Panel de Usuarios</div>
                        <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <td>
                                        <img id="img_foto" width="80" height="80">
                                        <input type="text" id="nombre" style="border: hidden; font-size:25px">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <h4>Pedidos Totales</h4>
                                                    <div id="Resultado"></div>
                                                    <h4>Promedio</h4>
                                                    <div id="ResultadoPromedio"></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <h4>Pedidos Realizados</h4>
                                        <div id="lineChart_pedidos"></div>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    </style>
@stop
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- DataTables -->

    <script type="text/javascript">
        var nombre;
        var pedidos;
        var totalPedidos;
        $(document).ready( function () {
            var img_foto = document.getElementById('img_foto')
            nombre = document.getElementById('nombre')
            obtengoPedidosAnual({{$id}})
            obtengoFoto({{$id}})
            obtengoDatosPersonales({{$id}})
        });
        function obtengoFoto(usuario_id){
            $.ajax({
                url: '/obtengoFoto?usuario_id=' + usuario_id,
                dataType : "json",
                success : function(json) {
                    img_foto.src = '/imagenes/' + json[0]['foto']
                }
            });
        }
        function obtengoPedidosAnual(usuaio_id){
            $.ajax({
                url: '/estadisticaPedidos?usuario_id=' + usuaio_id,
                dataType : "json",
                success : function(json) {
                    graficoPedidos(json);
                }
            });
        }
        function graficoPedidos(json) {
            pedidos = json;
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(pedidos);
                var options = {
                   // title: 'Pedidos Realizados',
                    is3D: true,
                }
                var chart = new google.visualization.LineChart(document.getElementById('lineChart_pedidos'));
                chart.draw(data, options);
            }
            obtengoCantPedidos()
        }
        function obtengoDatosPersonales(usuario_id){
            $.ajax({
                url: '/obtengoDatosPersonales?usuario_id=' + usuario_id,
                dataType : "json",
                success : function(json) {
                    nombre.value =  json[0]['nombre'] + " " + json[0]['apellido']
                }
            });
        }
        function obtengoCantPedidos(){
            $.ajax({
                url: '/obtengoCantPedidos',
                dataType: "json",
                success: function(json) {
                    totalPedidos = json
                    var e = "</td>";
                    for (var i = 0; i < json.length; i++) {
                        e += "<td>" + "[" + json[i]['mes'] + " = " + json[i]['cantidad'] + "] " + "</td>";
                    }
                    document.getElementById("Resultado").innerHTML = e;
                    obtengoPromedioPedidos()
                }
            })
        }
        function obtengoPromedioPedidos(){
            var e = "</td>";
            console.log(pedidos[0+1][0])
            for (var i = 0; i < totalPedidos.length; i++){
                e += "<td>" + "[" + pedidos[i+1]['0'].substring(3,0) + " = " + (Math.round(pedidos[i+1]['1'] * 100 / totalPedidos[i]['cantidad'])) + "%] " + "</td>";
            }
            document.getElementById("ResultadoPromedio").innerHTML = e;

        }
    </script>
@stop