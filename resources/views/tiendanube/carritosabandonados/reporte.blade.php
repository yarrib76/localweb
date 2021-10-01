@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Carritos Abandonados</i>
                        <button class="btn btn-primary" onclick="refresh()"><span class="glyphicon glyphicon-refresh"></span></button>
                    </div>
                    <div class="panel-body">
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>id_tienda_nube</th>
                                <th>Contacto</th>
                                <th>Celular</th>
                                <th>Email</th>
                                <th>Total</th>
                                <th>Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($carritosAbandonados as $carrito)
                                <tr>
                                    <td>{{$carrito->id_tienda_nube}}</td>
                                    <td>{{$carrito->nombre_contacto}}</td>
                                    <td>{{$carrito->cel_contacto}}</td>
                                    <td>{{$carrito->email_contacto}}</td>
                                    <td>{{$carrito->total}}</td>
                                    <td>{{$carrito->fecha}}</td>
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