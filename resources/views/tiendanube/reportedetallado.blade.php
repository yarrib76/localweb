@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Corria en E-Comerce Nº {{$id_corrida}}, Proveedor: {{$proveedor}}, Nombre: {{$nombre_ejecutor}}</i>
                        <button class="btn btn-primary" onclick="volver()"><i class="fa fa-arrow-left"></i></button>
                    </div>
                    <div class="panel-body">
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Articulo</th>
                                <th>Status</th>
                                <th>Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($statusEcomerce as $statusEcomerces)
                                <tr>
                                    <td>{{$statusEcomerces->articulo}}</td>
                                    <td>{{$statusEcomerces->status}}</td>
                                    <td>{{$statusEcomerces->fecha}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
        $('#reporte').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'excel'
                    ],
                    order: [2,'asc']
                }

        );
    } );
    function volver(){
        window.history.back();
    }
</script>
@stop