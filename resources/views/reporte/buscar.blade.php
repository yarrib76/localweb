@extends('layouts.master')
@section('contenido')
    <div class="container">
        <h4>Ingrese Fecha</h4>
        <input type="text" id="fecha" name="fecha" value="{{$anio}} " />
        <select name="listaFecha" onChange="combo(this, 'fecha')">
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
                    <div class="panel-heading"><i class="fa fa-cog">Lista de Articulos</i></div>
                    <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Articulo</th>
                                    <th>Detalle</th>
                                    <th>Cantidad</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($articulos as $articuloBusqueda)
                                    <tr>
                                        <td>{{$articuloBusqueda->Articulo}}</td>
                                        <td>{{$articuloBusqueda->Detalle}}</td>
                                        <td>{{$articuloBusqueda->Cantidad}}</td>
                                        <td><input type="button" value="Graficar" class="btn btn-info" onclick="obtengoArticulo({{$articuloBusqueda}});"> </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- DataTables -->

    <script type="text/javascript">
        $(document).ready( function () {
            $('#reporte').DataTable({

                        "lengthMenu": [ [8,  16, 32, -1], [8, 16, 32, "Todos"] ],
                        language: {
                            search: "Buscar:",
                            "thousands": ",",
                            processing:     "Traitement en cours...",
                            lengthMenu:    "Mostrar _MENU_ articulos",
                            info:           "Mostrando del  _START_ al _END_ de _TOTAL_ articulos",
                            infoEmpty:      "0 articulos",
                            infoFiltered:   "(Filtrando _MAX_ articulos en total)",
                            infoPostFix:    "",
                            loadingRecords: "Chargement en cours...",
                            zeroRecords:    "No se encontraron articulos para esa busqueda",
                            emptyTable:     "No existen articulos",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Proximo",
                                last:       "Ultimo"
                            }
                        }
                    }

            );
        } );

    </script>
    <script type="text/javascript">
        function grafico(json, detalle) {
            console.log(json)
            var articulo = json;
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(line_chart);
            function line_chart() {
                var data = google.visualization.arrayToDataTable(articulo);
                var options = {
                    title: 'Grafico de tendencia: ' + detalle,
                    pointSize: 7,
                    dataOpacity: 0.3,
                    curveType: 'function',
                    legend: 'none',
                    hAxis: {
                        title: 'Mes'
                    },
                    vAxis: {
                        title: 'Cantidad Vendida'
                    }
                };
                var chart = new google.visualization.LineChart(document.getElementById('linechart'));
                chart.draw(data, options);
            }
        }
        function graficoVendedora(json) {
            var vendedora = json;
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(vendedora);
                var options = {
                    title: 'Ranking Vendedoras Del Articulo',
                    is3D: true,
                }
                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                chart.draw(data, options);
            }
        }

        function obtengoArticulo(nroarticulo, anio){
            $.ajax({
                url: 'api/grafico?nroarticulo=' + nroarticulo['Articulo'] + "&anio=" + fecha.value,
                dataType : "json",
                success : function(json) {
                    grafico(json, nroarticulo['Detalle']);
                }
            });
            console.log(fecha.value)
            obtengoArticuloVendedora(nroarticulo,anio)
        }
        function obtengoArticuloVendedora(nroarticulo, anio){
            $.ajax({
                url: 'api/graficoVendedora?nroarticulo=' + nroarticulo['Articulo'] + "&anio=" + fecha.value,
                dataType : "json",
                success : function(json) {
                    graficoVendedora(json);
                }
            });
        }
        function combo(listaFecha, fecha) {
            fecha = document.getElementById(fecha);
            var idx = listaFecha.selectedIndex;
            var content = listaFecha.options[idx].innerHTML;
            fecha.value = content;
        }
    </script>
    <body>
    <style type="text/css">
        #linechart{
            float:left;
        }
        #piechart_3d{
            float:right;

        }
    </style>
    <div class="padre">
        <div id="linechart" style="width: 600px; height: 400px" ></div>
        <div id="piechart_3d" style="width: 600px; height: 400px;"></div>
    </div>
    </body>

@stop