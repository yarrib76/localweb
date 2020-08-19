@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Panel de Promociones</div>
                        <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Vencido</th>
                                    <th>Activo</th>
                                    <th>Finalizado</th>
                                    <th>En Espera</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($promociones as $promocion)
                                    <tr>
                                        <td>{{$promocion->Nombre}}<a href='/promocionestado/?id_cliente={{$promocion->Id}}&tipo=Total' class="badgeTotal" data-badge="{{$promocion->Vencido + $promocion->Activo + $promocion->Finalizado + $promocion->Espera}}"></a></td>
                                        <td><a href='/promocionestado/?id_cliente={{$promocion->Id}}&tipo=Vencido' class="badgeVencido" data-badge="{{$promocion->Vencido}}"></a></td>
                                        <td><a href='/promocionestado/?id_cliente={{$promocion->Id}}&tipo=Activo' class="badgeActivo" data-badge="{{$promocion->Activo}}"></a></td>
                                        <td><a href='/promocionestado/?id_cliente={{$promocion->Id}}&tipo=Finalizado' class="badgeFinalizado" data-badge="{{$promocion->Finalizado}}"></a></td>
                                        <td><a href='/promocionestado/?id_cliente={{$promocion->Id}}&tipo=Espera' class="badgeEspera" data-badge="{{$promocion->Espera}}"></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <a href='{{ route('promocion.create') }}' class = 'btn btn-primary'>Crear Promocion</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badgeVencido {
            position:relative;
        }
        .badgeVencido[data-badge]:after {
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
        .badgeActivo {
            position:relative;
        }
        .badgeActivo[data-badge]:after {
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
        .badgeFinalizado {
            position:relative;
        }
        .badgeFinalizado[data-badge]:after {
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
        .badgeTotal {
            position:relative;
        }
        .badgeTotal[data-badge]:after {
            content:attr(data-badge);
            position:absolute;
            top:-10px;
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
                            lengthMenu:    "Mostrar _MENU_ promociones",
                            info:           "Mostrando del  _START_ al _END_ de _TOTAL_ promociones",
                            infoEmpty:      "0 promociones",
                            infoFiltered:   "(Filtrando _MAX_ promociones en total)",
                            infoPostFix:    "",
                            loadingRecords: "Chargement en cours...",
                            zeroRecords:    "No se encontraron promociones para esa busqueda",
                            emptyTable:     "No existen promociones",
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