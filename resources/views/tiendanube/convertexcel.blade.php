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
                                <th>Identificador de URL</th>
                                <th>Nombre</th>
                                <th>Categorías</th>
                                <th>Nombre de propiedad 1</th>
                                <th>Valor de propiedad 1</th>
                                <th>Nombre de propiedad 2</th>
                                <th>Valor de propiedad 2</th>
                                <th>Nombre de propiedad 3</th>
                                <th>Valor de propiedad 3</th>
                                <th>Precio</th>
                                <th>Precio promocional</th>
                                <th>Peso</th>
                                <th>Stock</th>
                                <th>SKU</th>
                                <th>Código de barras</th>
                                <th>Mostrar en tienda</th>
                                <th>Envío sin cargo</th>
                                <th>Descripción</th>
                                <th>Tags</th>
                                <th>Descripción para SEO</th>
                                <th>Marca</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($listArticulosTN as $articuloTN)
                                <tr>
                                    <td>{{$articuloTN['Identificador de URL']}}</td>
                                    <td>{{$articuloTN['Nombre']}}</td>
                                    <td>{{$articuloTN['Categorías']}}</td>
                                    <td>{{$articuloTN['Nombre de propiedad 1']}}</td>
                                    <td>{{$articuloTN['Valor de propiedad 1']}}</td>
                                    <td>{{$articuloTN['Nombre de propiedad 2']}}</td>
                                    <td>{{$articuloTN['Valor de propiedad 2']}}</td>
                                    <td>{{$articuloTN['Nombre de propiedad 3']}}</td>
                                    <td>{{$articuloTN['Valor de propiedad 3']}}</td>
                                    <td>{{$articuloTN['Precio']}}</td>
                                    <td>{{$articuloTN['Precio promocional']}}</td>
                                    <td>{{$articuloTN['Peso']}}</td>
                                    <td>{{$articuloTN['Stock']}}</td>
                                    <td>{{$articuloTN['SKU']}}</td>
                                    <td>{{$articuloTN['Código de barras']}}</td>
                                    <td>{{$articuloTN['Mostrar en tienda']}}</td>
                                    <td>{{$articuloTN['Envío sin cargo']}}</td>
                                    <td>{{$articuloTN['Descripción']}}</td>
                                    <td>{{$articuloTN['Tags']}}</td>
                                    <td>{{$articuloTN['Descripción para SEO']}}</td>
                                    <td>{{$articuloTN['Marca']}}</td>
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
                            'excel',

                        ],
                        order: [0,'desc'],
                        render: function (data, type, row ) {
                            data_replace = data.replace('&lt;',/</g);
                            return '<a href="invoice/'+data_replace3+'.pdf">' + data + '</a>';
                        },
                    }

            );
        } );

        function refresh (){
            location.reload();
        }
    </script>
@stop