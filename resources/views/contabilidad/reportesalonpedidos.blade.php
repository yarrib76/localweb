@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Reporte Salon Pedidos</i></div>
                        <div class="col-sm-3">
                            Fecha Inicio
                            <input type="date" class="form-control" placeholder="Fecha" id="FechaInicio" required="required">
                        </div>
                        <div class="col-sm-3">
                            Fecha Fin
                            <input type="date" class="form-control" placeholder="Fecha" id="FechaFin" required="required">
                        </div>
                        <button onclick="verificar()" class="buttonViamore">Ejecutar</button>
                    <div class="col-sm-15">
                        <div class="col-sm-2">
                            Promedio Total
                            <input type="number" id="PromedioTotal" class="form-control" name="PromedioTotal" disabled = true >
                        </div>
                        <div class="col-sm-2">
                            Pedidos Pendientes
                            <input type="number" id="pedidosPedientes" class="form-control" name="pedidosPedientes" disabled = true >
                        </div>
                        <div class="col-sm-2">
                            Dias De Trabajo
                            <input type="number" id="DiasDeTrabajo" class="form-control" name="DiasDeTrabajo" disabled = true >
                        </div>
                    </div>
                    <div class="panel-body">
                            <table id=ventasSalonFacturado" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Frcha</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                <div class="panel-body">
                    <table id=ventasPedidosFacturados" class="table table-striped table-bordered records_list">
                        <thead>
                        <tr>
                            <th>Frcha</th>
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
    <style>
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
    fechaInicio = document.getElementById("FechaInicio").valueAsDate = new Date();
    fechaFin = document.getElementById("FechaFin").valueAsDate = new Date();



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
                                {"data": "total"},
                                {"data": "fecha"},
                            ]
                        }
                );
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
                                {"data": "total"},
                                {"data": "fecha"},
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

            }
        })
    }
    function ventasPedidosCantidad(fechaInicio,fechaFin) {
        $.ajax({
            url: '/api/ventasPedidosCantidad?fecha_inicio=' + fechaInicio + '&fecha_fin=' + fechaFin,
            'method': "GET",
            'contentType': 'application/json',
            success: function (json) {

            },
        })
    }
</script>
@stop