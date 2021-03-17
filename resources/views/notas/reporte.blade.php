@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Notas Adhesivas</i></div>
                    <div class="panel-body">
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Titulo</th>
                                <th>Nota</th>
                                <th>Rol</th>
                                <th>Accion</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notas as $nota)
                                <tr>
                                    <td>{{$nota->titulo}}</td>
                                    <td>{{$nota->body}}</td>
                                    <td>{{$nota->tipo_rol}}</td>
                                    <td>
                                        <a href='{{ route('notasadmin.edit', $nota->id_notas_adhesivas) }}' class = 'btn btn-primary'>Modificar</a>
                                        <form action="{{ URL::route('notasadmin.destroy', $nota->id_notas_adhesivas) }}" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button class = 'btn btn-danger'>Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a href='{{ route('notasadmin.create') }}' class = 'btn btn-primary'>Crear Nota</a>
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

    </script>
@stop