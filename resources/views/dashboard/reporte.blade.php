<html lang="es">
{{--Head--}}
<head>

    <title>Sistema</title>
    <link href="/css/app.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="/css/librerias/font-awesome/font-awesome.css" rel="stylesheet">
    @yield('extra-css')
</head>

{{-- Body --}}
<body>

@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- DataTables -->

    <script type="text/javascript">
        $(document).ready( function () {
            obtengoFacturacionAnual()
            obtengoVendedorasAnual()
        })
        function graficoFacturacion(json) {
            var facturacion = json;
            console.log(facturacion);
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(facturacion);
                var options = {
                    title: 'Facturacion Anual',
                    is3D: true,
                }
                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                chart.draw(data, options);
            }
        }
        function obtengoFacturacionAnual(){
            $.ajax({
                url: 'api/reportesDashboardVentas',
                dataType : "json",
                success : function(json) {
                    graficoFacturacion(json);
                }
            });
        }
        function graficoVendedoras(json) {
            var vendedora = json;
            console.log(vendedora);
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(vendedora);
                var options = {
                    title: 'Ranking Vendodora Anual',
                    is3D: true,
                }
                var chart = new google.visualization.PieChart(document.getElementById('piechart_vendedoras'));
                chart.draw(data, options);
            }
        }
        function obtengoVendedorasAnual(){
            $.ajax({
                url: 'api/reportesDashboardVendedoras',
                dataType : "json",
                success : function(json) {
                    graficoVendedoras(json);
                }
            });
        }
    </script>
    <body>
    <style type="text/css">
        #piechart_vendedoras{
            float:right;

        }
        #piechart_3d{
            float:left;
        }
    </style>
    <div class="padre">
        <div id="piechart_3d" style="width: 500px; height: 400px;"></div>
        <div id="piechart_vendedoras" style="width: 500px; height: 400px;"></div>

    </div>
    </body>

@stop



<div class="content">
    <li></li>
    <li></li>
    <li></li>
    @yield('contenido')
</div>

</body>

@include('partials.footer')
@yield('extra-javascript')


</html>