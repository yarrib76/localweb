<html>
<head>
    <table id="reporte" class="table table-striped table-bordered records_list">
        <thead>
        <tr>
            <th>Articulo</th>
            <th>Detalle</th>
            <th>Cantidad</th>
        </tr>
        </thead>
        <tbody>
        @foreach($articulos as $articuloBusqueda)
            <tr>
                <td>{{$articuloBusqueda->Articulo}}</td>
                <td>{{$articuloBusqueda->Detalle}}</td>
                <td>{{$articuloBusqueda->Cantidad}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        var articulo = <?php echo $articulo; ?>;
        console.log(articulo);
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(line_chart);
        function line_chart() {
            var data = google.visualization.arrayToDataTable(articulo);
            var options = {
                title: 'Grafico de tendencia (Donatella)',
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
    </script>
</head>
<body>
<div id="linechart" style="width: 900px; height: 500px"></div>
</body>
</html>