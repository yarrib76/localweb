@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Articulos</i></div>
                    <div class="panel-body">
                        <div class="col-sm-3">
                            Fecha Inicio
                            <input type="date" class="form-control" placeholder="Fecha" id="FechaInicio" required="required">
                        </div>
                        <div class="col-sm-3">
                            Fecha Fin
                            <input type="date" class="form-control" placeholder="Fecha" id="FechaFin" required="required">
                        </div>
                        <button onclick="verificar()" class="buttonViamore">Ejecutar</button>
                        <div class="col-sm-12"> </div>
                        <div class="col-sm-6">
                            <h4>Facturados en el Salon</h4>
                            <input type="number" id="TotalSalon" name="TotalSalon" disabled = true" class="form-control">
                            <table id="ventasSalonFacturado" class="table table-striped table-bordered records_list">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-sm-6">
                            <h4>Facturados por Pedidos</h4>
                                <input type="number" id="TotalPedidos" class="form-control" name="TotalPedidos" disabled = true >
                                <table id="ventasPedidosFacturados" class="table table-striped table-bordered records_list">
                                    <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        input {
            text-align: center;
            position: relative;
        }
        .buttonViamore {
            display: inline-block;
            border-radius: 4px;
            background-color: #5088f4;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 20px;
            padding: 20px;
            width: 120px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
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
    <!-- DataTables -->

    <script type="text/javascript">
        $(document).ready( function () {
            fechaInicio = document.getElementById("FechaInicio").valueAsDate = new Date();
            fechaFin = document.getElementById("FechaFin").valueAsDate = new Date();
        });
        function verificar() {
            var fechaInicio = document.getElementById("FechaInicio").value;
            var fechaFin = document.getElementById("FechaFin").value;
            ventasSalonFacturado(fechaInicio,fechaFin)
            ventasPedidosFacturados(fechaInicio,fechaFin)
            ventasSalonCantidad(fechaInicio,fechaFin)
            ventasPedidosCantidad(fechaInicio,fechaFin)
        }
        function ventasSalonFacturado(fechaInicio,fechaFin) {
            $.ajax({
                url: '/api/ventasSalonFacturado?fecha_inicio=' + fechaInicio + '&fecha_fin=' + fechaFin,
                'method': "GET",
                'contentType': 'application/json',
                success: function (json) {
                    ordenInsert = json
                    tableVentasSalonFacturado = $('#ventasSalonFacturado').DataTable({
                        dom: 'Bfrtip',
                        "autoWidth": false,
                        "bDestroy": true,
                        "pageLength": 5,
                        buttons: [
                            'excel'
                        ],
                        order: [0, 'desc'],
                        "aaData": json,
                        "columns": [
                            {"data": "Fecha"},
                            {"data": "Total"}
                        ]})
                },
            })
        }
        function ventasPedidosFacturados(fechaInicio,fechaFin) {
            $.ajax({
                url: '/api/ventasPedidosFacturados?fecha_inicio=' + fechaInicio + '&fecha_fin=' + fechaFin,
                'method': "GET",
                'contentType': 'application/json',
                success: function (json) {
                    ordenInsert = json
                    tableVentasPedidosFacturados = $('#ventasPedidosFacturados').DataTable({
                                dom: 'Bfrtip',
                                "autoWidth": false,
                                "bDestroy": true,
                                "pageLength": 5,
                                buttons: [
                                    'excel'
                                ],
                                order: [0, 'desc'],
                                "aaData": json,
                                "columns": [
                                    {"data": "Fecha"},
                                    {"data": "Total"},
                                ]
                            }
                    );
                },
            })
        }
        function ventasSalonCantidad(fechaInicio,fechaFin) {
            $.ajax({
                url: '/api/ventasSalonCantidad?fecha_inicio=' + fechaInicio + '&fecha_fin=' + fechaFin,
                'method': "GET",
                'contentType': 'application/json',
                success: function (json) {
                    document.getElementById("TotalSalon").value = json[0]['cantidad'];
                }
            })
        }
        function ventasPedidosCantidad(fechaInicio,fechaFin) {
            $.ajax({
                url: '/api/ventasPedidosCantidad?fecha_inicio=' + fechaInicio + '&fecha_fin=' + fechaFin,
                'method': "GET",
                'contentType': 'application/json',
                success: function (json) {
                    document.getElementById("TotalPedidos").value = json[0]['cantidad'];
                },
            })
        }
    </script>
@stop