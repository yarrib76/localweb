@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Articulos en la Web</i></div>
                    <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Articulo</th>
                                    <th>Detalle</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Sku</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{$a = 1}}
                                @foreach($articulosWeb as $articuloWeb)
                                    <tr>
                                        <td>{{$articuloWeb['Articulo']}}</td>
                                        <td>{{$articuloWeb['Detalle']}}</td>
                                        <td>{{$articuloWeb['Precio']}}</td>
                                        <td>{{$articuloWeb['Stock']}}</td>
                                        <td>{{$articuloWeb['WebSku']}}</td>
                                        <td>
                                            @if($articuloWeb['WebSku'] === Null or $articuloWeb['WebSku'] == 0)
                                                <input type="button" id="boton{{$a++}}" value="Agregar SKU" class="btn btn-success" onclick="modificoSku({{$articuloWeb['Articulo']}},'{{$articuloWeb['Detalle']}}', '{{$articuloWeb['WebSku']}}',{{$a - 1}});">
                                            @else
                                                <input type="button" id="boton{{$a++}}" value="Modificar SKU" class="btn btn-primary" onclick="modificoSku({{$articuloWeb['Articulo']}},'{{$articuloWeb['Detalle']}}', '{{$articuloWeb['WebSku']}}',{{$a - 1}});">
                                            @endif
                                        </td>
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
        body {font-family: Arial, Helvetica, sans-serif;}

        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }


        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            height: 40%;
            overflow-y: auto;
        }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .well {
            background: none;
            height: 120px;
        }


    </style>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h4>Articulo</h4>
            <div class="col-xs-12 col-xs-offset-0 well">
                <label id="Detalle"></label>
                <input id="WebSku" type="number" step="any" class="form-control" placeholder="WebSku" name="WebSku">
            </div>
            <input type="button" id="guardar" value="Guardar SKU" class="btn btn-success" onclick="guardarSku();">
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
        //Definiciòn de variables
        var posicionBot;
        var posicionTable;
        var nroArti;
        var reporte = document.getElementById('reporte')
        $(document).ready( function () {
            $('#reporte').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ]
                    }
            );

        } );
        // Get the modal
        var modal = document.getElementById('myModal');
        function modificoSku(nroarticulo,detalle,webSku,posicionBoton){

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal
            modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            $(".modal-content h4").html("Articulo Nº:" + nroarticulo);
            document.getElementById("Detalle").innerHTML = "Detalle: " + detalle
            //Cargo las variables con los datos que llegan la llamda del metodo
            posicionBot = posicionBoton
            nroArti = nroarticulo
            //Identifico la filla accedida para cambiar el valor del WebSku
            var rows = document.getElementById('reporte').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            for (i = 0; i < rows.length; i++) {
                rows[i].onclick = function() {
                    //Paso a la variable la fila seleccionada
                    posicionTable = this.rowIndex
                    //Me vijo el valor que tiene la fila en el cambio webSku y se lo asigno al Input
                    //que esta en el model con el ID WebSku
                    newWebSku = reporte.rows[posicionTable].cells[4].innerHTML
                    document.getElementById("WebSku").value = newWebSku
                }
            }
        }

        function guardarSku() {
            $.ajax({
                url: 'api/sku?nroarticulo=' + nroArti + '&&webSku=' + document.getElementById("WebSku").value,
                dataType: "json",
                success: function (json) {
                    if (json[0]['websku'] === null) {
                        document.getElementById("boton" + posicionBot).className = "btn btn-success";
                        document.getElementById("boton" + posicionBot).value = "Agregar SKU"
                    } else {
                        document.getElementById("boton" + posicionBot).className = "btn btn-primary";
                        document.getElementById("boton" + posicionBot).value = "Modificar SKU"
                    }
                    modal.style.display = "none";
                    //El "json" es la respuesta del valor que se cambio pot la API del webSky
                    //Luego se lo cargo a la tabla en le posición "posicionTable"
                    reporte.rows[posicionTable].cells[4].innerHTML = json[0]['websku'] ;
                }
            });
        }


    </script>
@stop