@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Panel de Usuarios A&ntildeo {{$anioActual}} <button onclick="cargaModalObjetivos({{$id}})" style="color: #0000FF"> OBJETIVOS </button> </div>
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
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <h4>Fidelizacion de Clientes</h4>
                                                    <div id="piechart_fidelizacion" style="width: 400px; height: 300px;"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal">
        <!-- Modal Ingreso -->
        <div id="modal-content" class="modal-content">
            <span id="close" class="close">&times;</span>
            <div id="example-table"></div>
        </div>
    </div>

    <style>
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        /* Modal Content */
        .modal-content {
            background-color: rgba(243, 255, 242, 0.91);
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 27%;
            overflow-y: auto;
            border-radius: 10%;
        }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

@stop
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">

    <link rel="stylesheet" href="../../js/tabulador/tabulator.css">

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script>
    <script type="text/javascript" src="../../js/tabulador/xlsx.full.min.js"></script>
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
        var fidelizacion;
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
            obtengoFidelizacion({{$id}})
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
                // Agrega un controlador de eventos para el evento 'select'
                google.visualization.events.addListener(chart, 'select', handleClick);
                // Función controladora de eventos para el clic en el gráfico
                function handleClick() {
                    // Obtén la selección actual del gráfico
                    var selection = chart.getSelection();

                    // Verifica si hay alguna selección
                    if (selection.length > 0) {
                        // Llama a tu función personalizada pasando la selección u otro dato relevante
                        var mes = (data.getFormattedValue(selection[0].row, 0))
                        var usuario_id = '{{$id}}'
                        var url = "../reportePedidosCancelados?usuario_id=" + usuario_id + "&&mes=" + mes
                        // Abrir la URL en un nuevo tab del navegador
                        window.open(url, "_blank");
                    }
                }
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
                    var e = "</td>";
                    for (var i = 0; i < json.length; i++) {
                        e += "<td>"+ "<a onclick='listaMensual(\"" + json[i]['numMes']  + "\", \"" + usuario_id + "\")'>"  + "[" + json[i]['mes'] + " = " + json[i]['cantidad'] + "] " + "</td>";
                    }
                    document.getElementById("ResultadoFichajes").innerHTML = e;
                }
            })
        }
        function obtengoFidelizacion(usuario_id){
            $.ajax({
                url: '/obtengoFidelClientes?usuario_id=' + usuario_id,
                dataType : "json",
                success : function(json) {
                    graficoFielizacion(json,usuario_id);
                }
            });
        }
        function graficoFielizacion(json,usuario_id) {
            fidelizacion = json;
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(fidelizacion);
                var options = {
                    // title: 'Pedidos Realizados',
                    is3D: true,
                }
                var chart = new google.visualization.PieChart(document.getElementById('piechart_fidelizacion'));
                chart.draw(data, options);
            }
            obtengoTotalesNoEncuesta(usuario_id)
        }
        /*Funciones para Tabulator En Modal*/
        function listaMensual(numMes,usuario_id){
            llenarTabla(numMes,usuario_id)
            var modal = document.getElementById('myModal');

            // Get the <span> element that closes the modal
            var span = document.getElementById("close");

            // When the user clicks the button, open the modal
            modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            /*
            $.ajax({
                url: '/listaMensual?usuario_id=' + usuario_id + '&numMes=' + numMes,
                dataType: 'json',
                success: function(json) {
                    console.log(json)
                }
            })
            */
        }

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
            height: "210px",
            columns: [
                {title: "Mes", field: "mes", sortable: true, width: 140},
                {title: "Ingreso", field: "horarioIngreso", sortable: true, width: 85,formatter: function color(cell) {
                    console.log(cell.getRow().getData())
                    if (cell.getRow().getData()['fichaje'] == 1) {
                        cell.getElement().css({"background-color": "red"});
                    }
                    if (cell.getRow().getData()['fichaje'] == 2){
                        cell.getElement().css({"background-color": "yellow"});
                    }
                    return cell.getRow().getData()['horarioIngreso'];
                }
                },
                {title: "Egreso", field: "horarioEgreso", sortable: true, width: 85},
            ],

        });
        function llenarTabla(numMes, usuario_id) {
            $("#example-table").tabulator("setData", '/listaMensual?usuario_id=' + usuario_id + '&numMes=' + numMes);
        }

    </script>
    <!-- Incluir archivo reporteobjetivo --!>
    @include('personal.objetivos.reporteobjetivo')
@stop