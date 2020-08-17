@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Articulos</i></div>
                    <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Articulo</th>
                                    <th>Detalle</th>
                                    <th>ProveedorSKU</th>
                                    <th>Cantidad</th>
                                    <th>EnPedido</th>
                                    <th>PrecioVenta</th>
                                    <th>Imagen</th>
                                    <th>Acccion</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{$a = 1}}
                                @foreach($articulos as $articulo)
                                    <tr>
                                        <td>{{$articulo->Articulo}}</td>
                                        <td>{{$articulo->Detalle}}</td>
                                        <td>{{$articulo->ProveedorSKU}}</td>
                                        <td>{{$articulo->Cantidad}}</td>
                                        <td>{{$articulo->Pedido}}</td>
                                        <td>{{$articulo->PrecioVenta}}</td>
                                        <td>
                                            @if(!empty($articulo->ImageName))
                                                <img src="/imagenes/articulos/{{{$articulo->ImageName}}}" alt="Sin Imagen" height="52" width="52">
                                            @endif</td>
                                        <td>
                                            <a href='{{ route('articulos.edit', $articulo->Articulo) }}' target="_blank" class = 'btn btn-primary'>Modificar</a>
                                            @if($articulo->Web == 0)
                                                <input type="button" id="boton{{$a++}}" value="Cargar Web" class="btn btn-success" onclick="modificoArticulo({{$articulo->Articulo}},{{$a - 1}});">
                                            @else
                                                <input type="button" id="boton{{$a++}}" value="Quitar Web" class="btn btn-danger" onclick="modificoArticulo({{$articulo->Articulo}},{{$a - 1}});">
                                            @endif
                                            <a href='/barcode/?articulo={{$articulo->Articulo}}' target="_blank" class = 'fa fa-barcode' style="font-size:38px;color:red"></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        <a href='{{ route('articulos.create') }}' target="_blank" class = 'btn btn-primary'>Crear Articulo</a>
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
                        ]
                    }

            );
        } );

        function modificoArticulo(nroarticulo,posicionBoton){
            $.ajax({
                url: 'api/modificoSiEsWeb?nroarticulo=' + nroarticulo,
                dataType : "json",
                success : function(json) {
                    console.log(json)
                    if (json == 0){
                        document.getElementById("boton" + posicionBoton).className = "btn btn-success";
                        document.getElementById("boton" + posicionBoton).value = "Cargar Web"
                    }else {
                        document.getElementById("boton" + posicionBoton).className = "btn btn btn-danger";
                        document.getElementById("boton" + posicionBoton).value = "Quitar Web"
                    }
                }
            });

        }
    </script>
@stop