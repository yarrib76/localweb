@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Clientes</i></div>
                    <div class="panel-body">
                        <div>
                            Seleccionar Columnas: <a class="toggle-vis" data-column="0">Nombre</a> -
                            <a class="toggle-vis" data-column="1">Apellido</a> -
                            <a class="toggle-vis" data-column="2">Apodo</a> -
                            <a class="toggle-vis" data-column="3">Cuit</a> -
                            <a class="toggle-vis" data-column="4">Direccion</a> -
                            <a class="toggle-vis" data-column="5">Mail</a> -
                            <a class="toggle-vis" data-column="6">Telefono</a> -
                            <a class="toggle-vis" data-column="7">Localidad</a> -
                            <a class="toggle-vis" data-column="8">Provincia</a> -
                            <a class="toggle-vis" data-column="9">Fecha</a>
                        </div>
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Apodo</th>
                                <th>Cuit</th>
                                <th>Direccion</th>
                                <th>Mail</th>
                                <th>Telefono</th>
                                <th>Localidad</th>
                                <th>Provincia</th>
                                <th>Fecha</th>
                                <th>Accion</th>
                            </tr>
                            </thead>
                        </table>
                        <a href='{{ route('clientes.create') }}' class = 'btn btn-primary'>Crear Cliente</a>
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
        var table;
        $(document).ready( function () {
            // Get the modal
            var modal = document.getElementById('myModal');
            // When the user clicks the button, open the modal
            modal.style.display = "block";
            $.ajax({
                'url': "/api/abmclientes",
                'method': "GET",
                'contentType': 'application/json',
                success : function(json) {
                    for (var i = 0, ien = json.length; i < ien; i++) {
                        json[i]['Accion'] = "<a href='/clientesedit/" + json[i]['id_clientes'] + " ' target='_blank' class = 'btn btn-primary'>Modificar</a>"
                    }
                     table =  $('#reporte').DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excel',
                                        text: 'Excel',
                                        className: 'btn btn-default',
                                        exportOptions: {
                                            columns: [0,1,5,6,9]
                                        }
                                    }
                                ],
                                "aaData": json,
                                "columns": [
                                    { "data": "nombre" },
                                    { "data": "apellido" },
                                    { "data": "apodo" },
                                    { "data": "cuit" },
                                    { "data": "direccion" },
                                    { "data": "mail" },
                                    { "data": "telefono" },
                                    { "data": "localidad" },
                                    { "data": "provincia" },
                                    { "data": "fecha" },
                                    { "data": "Accion" }
                                ],
                                "columnDefs": [
                                    {
                                        "targets": [ 2 ],
                                        "visible": false,
                                        "searchable": true
                                    },
                                    {
                                        "targets": [ 3 ],
                                        "visible": false,
                                        "searchable": true
                                    },
                                    {
                                        "targets": [ 7 ],
                                        "visible": false,
                                        "searchable": true
                                    },
                                    {
                                        "targets": [ 8 ],
                                        "visible": false,
                                        "searchable": true
                                    },
                                    {
                                        "targets": [ 9 ],
                                        "visible": false,
                                        "searchable": true
                                    }
                                ]
                            }
                    );
                    modal.style.display = "none";
                },
            })
            $('a.toggle-vis').on( 'click', function (e) {
                e.preventDefault();

                // Get the column API object
                var column = table.column( $(this).attr('data-column') );

                // Toggle the visibility
                column.visible( ! column.visible() );
            } );
        });
    </script>
@stop