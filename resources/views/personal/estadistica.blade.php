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
                                    <table id="reporte" class="table table-striped table-bordered records_list">
                                        <thead>
                                        <tr>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <h4>Fichaje</h4>
                                                            <h5>Llegadas Tardes</h5>
                                                            <di id="ResultadoFichajes"></di>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                    </table>
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
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <h4>Ventas Totales</h4>
                                                    <di id="ResultadoVentasTotales"></di>
                                                    <h4>Ventas Promedio</h4>
                                                    <div id="ResultadoPromedioVentas"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                 </tr>
                                <tr>
                                    <td>
                                        <h4>Ventas Salon</h4>
                                        <div id="lineChart_VentasSalon"></div>
                                    </td>
                                </tr>
                            </table>
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <tr>
                                    <td>
                                        <h4>Pedidos Cancelados</h4>
                                        <div id="lineChart_PedidosCancelados"></div>
                                    </td>
                                </tr>
                            </table>
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <h4>Total Pedidos</h4>
                                                    <di id="ResultadoTotalesNoEncuesta"></di>
                                                    <h4>Promedio No Encuestado</h4>
                                                    <div id="ResultadoPromedioNoEncuesta"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4>Clintes No Encuestados</h4>
                                        <div id="lineChart_NoEncuestados"></div>
                                    </td>
                                </tr>
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
        var ventasSalon;
        var ventasSalonTotales;
        var pedidosCancelados;
        var pedidosSinEncuesta;
        var pedidosTotalesSinEncuesta;
        $(document).ready( function () {
            var img_foto = document.getElementById('img_foto')
            nombre = document.getElementById('nombre')
            obtengoPedidosAnual({{$id}})
            obtengoVentasSalon({{$id}})
            obtengoFoto({{$id}})
            obtengoDatosPersonales({{$id}})
            obtengoPedidosCancelados({{$id}})
            obtengoPedidosSinEncuesta({{$id}})
            obtengoControlFichaje({{$id}})
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
        function obtengoPedidosAnual(usuario_id){
            $.ajax({
                url: '/estadisticaPedidos?usuario_id=' + usuario_id,
                dataType : "json",
                success : function(json) {
                    graficoPedidos(json);
                }
            });
        }

        function obtengoVentasSalon(usuario_id){
            $.ajax({
                url: '/obtengoVentasSalon?usuario_id=' + usuario_id,
                dataType : "json",
                success : function(json) {
                    graficoVentasSalon(json);
                }
            });
        }
        function obtengoPedidosCancelados(usuario_id){
            $.ajax({
                url: '/obtengoPedidosCancelados?usuario_id=' + usuario_id,
                dataType : "json",
                success : function(json) {
                    graficoPedidosCancelados(json);
                }
            });
        }
        function obtengoPedidosSinEncuesta(usuario_id){
            $.ajax({
                url: '/obtengoCantidadNoEncuesta?usuario_id=' + usuario_id,
                dataType : "json",
                success : function(json) {
                    graficoPedidosSinEncuesta(json,usuario_id);
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

        function graficoVentasSalon(json){
            ventasSalon = json;
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(ventasSalon);
                var options = {
                    // title: 'Pedidos Realizados',
                    is3D: true,
                }
                var chart = new google.visualization.LineChart(document.getElementById('lineChart_VentasSalon'));
                chart.draw(data, options);
            }
            obtengoVentasTotales()
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
        function obtengoVentasTotales(){
            $.ajax({
                url: '/obtengoVentasSalonTotales',
                dataType: "json",
                success: function(json) {
                    ventasSalonTotales = json
                    var e = "</td>";
                    for (var i = 0; i < json.length; i++) {
                        e += "<td>" + "[" + json[i]['mes'] + " = " + json[i]['cantidad'] + "] " + "</td>";
                    }
                    document.getElementById("ResultadoVentasTotales").innerHTML = e;
                    obtengoPromedioVentas()
                }
            })
        }
        function obtengoPromedioVentas(){
            var e = "</td>";
        //      console.log(pedidos[0+1][0])
            for (var i = 0; i < ventasSalonTotales.length; i++){
                e += "<td>" + "[" + ventasSalon[i+1]['0'].substring(3,0) + " = " + (Math.round(ventasSalon[i+1]['1'] * 100 / ventasSalonTotales[i]['cantidad'])) + "%] " + "</td>";
            }
            document.getElementById("ResultadoPromedioVentas").innerHTML = e;
        }

        function obtengoTotalesNoEncuesta(usuario_id){
            $.ajax({
                url: '/obtengoCantidadTotalesParaNoEncuesta?usuario_id=' + usuario_id,
                dataType: "json",
                success: function(json) {
                    pedidosTotalesSinEncuesta = json
                    var e = "</td>";
                    for (var i = 0; i < json.length; i++) {
                        e += "<td>" + "[" + json[i]['mes'] + " = " + json[i]['cantidad'] + "] " + "</td>";
                    }
                    document.getElementById("ResultadoTotalesNoEncuesta").innerHTML = e;
                    obtengoPromedioNoEncuesta()
                }
            })
        }
        function obtengoPromedioNoEncuesta(){
            var e = "</td>";
            for (var i = 0; i < pedidosTotalesSinEncuesta.length; i++){
                e += "<td>" + "[" + pedidosSinEncuesta[i+1]['0'].substring(3,0) + " = " + (Math.round(pedidosSinEncuesta[i+1]['1'] * 100 / pedidosTotalesSinEncuesta[i]['cantidad'])) + "%] " + "</td>";
            }
            document.getElementById("ResultadoPromedioNoEncuesta").innerHTML = e;
        }

        function graficoPedidosCancelados(json){
            pedidosCancelados = json;
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(pedidosCancelados);
                var options = {
                    // title: 'Pedidos Realizados',
                    is3D: true,
                }
                var chart = new google.visualization.LineChart(document.getElementById('lineChart_PedidosCancelados'));
                chart.draw(data, options);
            }
        }
        function graficoPedidosSinEncuesta(json,usuario_id) {
            pedidosSinEncuesta = json;
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(pedidosSinEncuesta);
                var options = {
                    // title: 'Pedidos Realizados',
                    is3D: true,
                }
                var chart = new google.visualization.LineChart(document.getElementById('lineChart_NoEncuestados'));
                chart.draw(data, options);
            }
            obtengoTotalesNoEncuesta(usuario_id)
        }

        function obtengoControlFichaje(usuario_id){
            $.ajax({
                url: '/listaFichaje?usuario_id=' + usuario_id,
                dataType: "json",
                success: function(json) {
                    console.log(json)
                    var e = "</td>";
                    for (var i = 0; i < json.length; i++) {
                        e += "<td>"+ "<a onclick='prueba(\"" + json[i]['numMes'] + "," + usuario_id + "\")'>"  + "[" + json[i]['mes'] + " = " + json[i]['cantidad'] + "] " + "</td>";
                    }
                    document.getElementById("ResultadoFichajes").innerHTML = e;
                }
            })
        }
        function prueba(numMes,usuario_id){
            alert(numMes, usuario_id)
        }
    </script>
@stop