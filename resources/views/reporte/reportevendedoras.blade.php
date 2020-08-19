@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Panel de Vendedoras</div>
                        <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Vendedora</th>
                                    <th>Asignados</th>
                                    <th>Empaquetados</th>
                                    <th>En Proceso</th>
                                    <th>Para Facturar</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($consultas as $consulta)
                                    <tr>
                                        <td>{{$consulta->vendedora}}</td>
                                        <td><a href='/pedidosAsignados?Vendedora={{$consulta->vendedora}}' class="badgeAsignado" data-badge="{{$consulta->Asignados}}"></a></td>
                                        <td><a href='/pedidosEmpaquetados?Vendedora={{$consulta->vendedora}}' class="badgeEmpaquetado" data-badge="{{$consulta->Empaquetado}}"></a></td>
                                        <td><a href='/pedidosEnProceso?Vendedora={{$consulta->vendedora}}' class="badgeEnProceso" data-badge="{{$consulta->EnProceso}}"></a></td>
                                        <td><a href='/pedidosParaFacturar?Vendedora={{$consulta->vendedora}}' class="badgeEspera" data-badge="{{$consulta->ParaFacturar}}"></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badgeEnProceso {
            position:relative;
        }
        .badgeEnProceso[data-badge]:after {
            content:attr(data-badge);
            position:absolute;
            top:0px;
            right:-110px;
            font-size:.7em;
            background:red;
            color:white;
            width:18px;height:18px;
            text-align:center;
            line-height:18px;
            border-radius:50%;
            box-shadow:0 0 1px #333;
        }
        .badgeAsignado {
            position:relative;
        }
        .badgeAsignado[data-badge]:after {
            content:attr(data-badge);
            position:absolute;
            top:0px;
            right:-110px;
            font-size:.7em;
            background:yellow;
            color:black;
            width:18px;height:18px;
            text-align:center;
            line-height:18px;
            border-radius:50%;
            box-shadow:0 0 1px #333;
        }
        .badgeEmpaquetado{
            position:relative;
        }
        .badgeEmpaquetado[data-badge]:after {
            content:attr(data-badge);
            position:absolute;
            top:0px;
            right:-110px;
            font-size:.7em;
            background:green;
            color:white;
            width:18px;height:18px;
            text-align:center;
            line-height:18px;
            border-radius:50%;
            box-shadow:0 0 1px #333;
        }
        .badgeEspera {
            position:relative;
        }
        .badgeEspera[data-badge]:after {
            content:attr(data-badge);
            position:absolute;
            top: 0px;
            right:-110px;
            font-size:.7em;
            background:blue;
            color:white;
            width:18px;height:18px;
            text-align:center;
            line-height:18px;
            border-radius:50%;
            box-shadow:0 0 1px #333;
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
            var table =  $('#reporte').DataTable({
                        "lengthMenu": [ [8,  16, 32, -1], [8, 16, 32, "Todos"] ],
                        language: {
                            search: "Buscar:",
                            "thousands": ",",
                            processing:     "Traitement en cours...",
                            lengthMenu:    "Mostrar _MENU_ Vendedoras",
                            info:           "Mostrando del  _START_ al _END_ de _TOTAL_ Vendedoras",
                            infoEmpty:      "0 Vendedoras",
                            infoFiltered:   "(Filtrando _MAX_ Vendedoras en total)",
                            infoPostFix:    "",
                            loadingRecords: "Chargement en cours...",
                            zeroRecords:    "No se encontraron Vendedoras para esa busqueda",
                            emptyTable:     "No existen Vendedoras",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Proximo",
                                last:       "Ultimo"
                            }
                        }
                    }

            );
            $('a.toggle-vis').on( 'click', function (e) {
                e.preventDefault();

                // Get the column API object
                var column = table.column( $(this).attr('data-column') );

                // Toggle the visibility
                column.visible( ! column.visible() );
            } );
        } );
    </script>
@stop