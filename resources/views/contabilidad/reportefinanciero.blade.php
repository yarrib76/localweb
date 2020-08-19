@extends('layouts.master')
@section('contenido')
    <div class="container">
        <h4>Ingrese Fecha</h4>
        <input type="text" id="fecha" name="fecha" value="{{$año}} " />
        <select name="listaFecha" onChange="combo(this, 'fecha')">
            <option value="2016">2013</option>
            <option value="2016">2014</option>
            <option value="2016">2015</option>
            <option value="2016">2016</option>
            <option value="2017">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <option value="2022">2022</option>
            <option value="2023">2023</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
            <option value="2027">2027</option>
            <option value="2028">2028</option>
            <option value="2029">2029</option>
            <option value="2030">2030</option>
        </select>
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Reporte Financiero Anual</div>
                    <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Mes</th>
                                    <th>Ganancia</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datos as $dato)
                                    <tr>
                                        <td data-order = "{{$dato->Fecha}}">{{$dato->Mes}}</td>
                                        <td>{{$dato->Ganancia}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        <div id="piechart_ganancias" style="width: 500px; height: 400px";></div>
                        <div id="piechart_facturacion" style="width: 500px; height: 400px ";></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h4> </h4>
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="reporteVendedores" class="table table table-scroll table-striped">
                    <thead>
                    <tr>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <style type="text/css">
        body {
            color: #5d5d5d;
            font-family: Helvetica, Arial, sans-serif;
        }
        /* The Modal (background) */
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
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 70%;
            overflow-y: auto;
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

        .well {
            background: none;
            height: 420px;
        }

        .table-scroll tbody {
            position: absolute;
            overflow-y: scroll;
            height: 350px;
        }

        .table-scroll tr {
            width: 100%;
            table-layout: fixed;
            display: inline-table;
        }

        .table-scroll thead > tr > th {
            border: none;
        }
    </style>
@stop
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- DataTables -->

    <script type="text/javascript">

        $(document).ready( function () {
            $('#reporte').DataTable({
                        dom: 'Bfrtip',
                        "pageLength": 12,
                        "autoWidth": false,
                        buttons: [
                            'excel'
                        ]
                    }

            );
            obtengoGraficoGanancia()
            obtengoGraficoFacturacion()
        } );
        function combo(listaFecha, fecha) {
            fecha = document.getElementById(fecha);
            var idx = listaFecha.selectedIndex;
            var content = listaFecha.options[idx].innerHTML;
            fecha.value = content;
            window.location.replace("../reporteFinanciero?anio=" + fecha.value);
        }
        function obtengoGraficoGanancia(){
            var año  = document.getElementById('fecha').value
            $.ajax({
                url: '/reporteFinancieroGraficoGanancia?anio=' + año,
                dataType : "json",
                success : function(json) {
                    graficoGanancia(json,año);
                }
            });
        }
        function obtengoGraficoFacturacion(){
            var año  = document.getElementById('fecha').value
            $.ajax({
                url: '/reporteFinancieroGraficoFacturacion?anio=' + año,
                dataType : "json",
                success : function(json) {
                    graficoFacturacion(json,año);
                }
            });
        }
        function graficoGanancia(json,año) {
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(json);
                var options = {
                    title: 'Grafico Ganancia Anual ' + año,
                    is3D: true,
                }
                var chart = new google.visualization.PieChart(document.getElementById('piechart_ganancias'));
                chart.draw(data, options);
            }
        }
        function graficoFacturacion(json,año) {
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(json);
                var options = {
                    title: 'Grafico Facturacion Anual ' + año,
                    is3D: true,
                }
                function selectHandler() {
                    var selectedItem = chart.getSelection()[0];
                    if (selectedItem) {
                        var mesSelect = json[selectedItem.row + 1][0]
                        reporteVendedores(mesSelect)
                    }
                }
                var chart = new google.visualization.PieChart(document.getElementById('piechart_facturacion'));
                google.visualization.events.addListener(chart, 'select', selectHandler);
                chart.draw(data, options);
            }
        }

        function reporteVendedores(mes){
            var año  = document.getElementById('fecha').value
            var table = $("#reporteVendedores");
            table.children().remove()
            table.append("<thead><tr><th>Vendedora</th><th>Total</th><th>Porcentaje</th></tr></thead>")
            table.append("<tbody>")
            $.ajax({
                url: '/reporteFinancieroFacturacionVendedores?mes=' + mes + '&anio=' + año,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['Vendedora']+"</td><td>"+json['Total']+"</td><td>"+json['Porcentaje']+"</td>"+"</tr>");
                    });
                    table.append("</tbody>")
                    $(".modal-content h4").html('Mes: ' + mes.toUpperCase());
                }
            });
            // Get the modal
            var modal = document.getElementById('myModal');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

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
        }
    </script>
    <style type="text/css">
        #piechart_ganancias{
            float:right;

        }
        #piechart_facturacion{
            float:left;
        }
    </style>
@stop