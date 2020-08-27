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
                                    <th>Acccion</th>
                                </tr>
                                </thead>
                            </table>
                        <a href='{{ route('articulos.create') }}' target="_blank" class = 'btn btn-primary'>Crear Articulo</a>
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
        $(document).ready( function () {
            // Get the modal
            var modal = document.getElementById('myModal');
            // When the user clicks the button, open the modal
            modal.style.display = "block";
            $.ajax({
                'url': "/api/abmarticulos",
                'method': "GET",
                'contentType': 'application/json',
                success : function(json) {
                    for ( var i=0, ien=json.length ; i<ien ; i++ ) {
                        json[i]['Accion'] = "<a href='/barcode?articulo=" + json[i]['Articulo'] + " 'target='_blank' class = 'fa fa-barcode' style='font-size:38px;color:red'></a>"
                        + "<br/>" +  "<a href='/articulocreate' target='_blank' class = 'btn btn-primary'>Modificar</a>"
                    }
                    $('#reporte').DataTable({
                                dom: 'Bfrtip',
                                "autoWidth": false,
                                buttons: [
                                    'excel'
                                ],
                                order: [0,'desc'],
                                "aaData": json,
                                "columns": [
                                    { "data": "Articulo" },
                                    { "data": "Detalle" },
                                    { "data": "ProveedorSKU" },
                                    { "data": "Cantidad" },
                                    { "data": "Pedido" },
                                    { "data": "PrecioVenta" },
                                    { "data": "Accion" }
                                ]
                            }
                    );
                    modal.style.display = "none";
                },
            })
        });

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