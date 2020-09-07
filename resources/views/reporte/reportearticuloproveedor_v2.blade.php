@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading" id="refresh"><i class="fa fa-cog">Lista de Articulos Fecha Del Reporte: {{$fecha}}
                            <input type="button" value="Refresh" class="btn btn-success" onclick="refresh()">
                        </i></div>
                    <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Pais</th>
                                    <th>Articulo</th>
                                    <th>Detalle</th>
                                    <th>Costo</th>
                                    <th>Ganancia</th>
                                    <th>Cantidad</th>
                                    <th>PrecioOrigen</th>
                                    <th>Moneda</th>
                                    <th>PrecioConvertido</th>
                                    <th>PrecioManual</th>
                                    <th>PrecioArgDolar</th>
                                    <th>PrecioArgenPesos</th>
                                    <th>PrecioVenta</th>
                                    <th>CotizacionDolar</th>

                                </tr>
                                </thead>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #myModal {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 11%;
            height: 20%;
            overflow-y: auto;
        }
    </style>
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <img src="refresh/load.gif" height="100" width="100">
        </div>
    </div>
@stop
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <!-- DataTables -->

    <script type="text/javascript">

        $(document).ready( function () {
            // Get the modal
            var modal = document.getElementById('myModal');
            // When the user clicks the button, open the modal
            modal.style.display = "block";
            $.ajax({
                'url': "/api/reporteArticuloProveedor",
                'method': "GET",
                'contentType' : 'application/json',
                success : function(json) {
                    $('#reporte').DataTable({
                                dom: 'Bfrtip',
                                "autoWidth": false,
                                buttons: [
                                    'excel'
                                ],
                                order: [0,'desc'],
                                "aaData": json,
                                "columns": [
                                    { "data": "Proveedor" },
                                    { "data": "Pais" },
                                    { "data": "Articulo" },
                                    { "data": "Detalle" },
                                    { "data": "Costo" },
                                    { "data": "Ganancia" },
                                    { "data": "Cantidad" },
                                    { "data": "PrecioOrigen" },
                                    { "data": "Moneda" },
                                    { "data": "PrecioConvertido" },
                                    { "data": "PrecioManual" },
                                    { "data": "PrecioArgDolar" },
                                    { "data": "PrecioArgenPesos" },
                                    { "data": "PrecioVenta" },
                                    { "data": "CotizacionDolar" }
                                ]
                            }
                    );
                    modal.style.display = "none";
                }
            })
        });

        function refresh(){
            $('#refresh').html('<img src="refresh/loading.gif" height="42" width="42"> Cargando...');
            $.ajax({
                url: 'api/refresh',
                dataType : "json",
                success : function(json) {
                    location.reload();
                }
            });
        }
    </script>
@stop