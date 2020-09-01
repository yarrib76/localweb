@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Panel E-Comerce</i>
                        <button class="btn btn-primary" onclick="refresh()"><span class="glyphicon glyphicon-refresh"></span></button>
                    </div>
                    <div class="panel-body">
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Corrida</th>
                                <th>Proveedor</th>
                                <th>Nombre</th>
                                <th>Tienda</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>OK</th>
                                <th>Errores</th>
                                <th>Pendientes</th>
                                <th>Accion</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($provEcomerces as $provEcomerce)
                                <tr>
                                    <td>{{$provEcomerce->corrida}}</td>
                                    <td>{{$provEcomerce->proveedor}}</td>
                                    <td>{{$provEcomerce->nombre}}</td>
                                    <td>{{$provEcomerce->tienda}}</td>
                                    <td>{{$provEcomerce->fecha}}</td>
                                    <td align="center"><span class="badge badge-success"><h5>{{$provEcomerce->total}}</h5></span></td>
                                    <td align="center"><span class="badge badge-success"><h5>{{$provEcomerce->ok}}</h5></span></td>
                                    <td align="center"><span class="badge badge-success"><h5>{{$provEcomerce->error}}</h5></span></td>
                                    <td align="center"><span class="badge badge-success"><h5>{{$provEcomerce->pending}}</h5></span></td>
                                    <td><a href='/consultadetalladaecomerce/?id_corrida={{$provEcomerce->corrida}}&nombre={{$provEcomerce->nombre}}&proveedor={{$provEcomerce->proveedor}}&tienda={{$provEcomerce->tienda}}&id_cliente={{$provEcomerce->id_cliente}}' class = 'btn btn-primary'>Detalle</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>
    span h5 {
        color: #fff;
        display:table;
        margin:0 auto;
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
        $('#reporte').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'excel'
                    ],
                    order: [0,'desc']
                }

        );
    } );

    function refresh (){
        location.reload();
    }
</script>
@stop