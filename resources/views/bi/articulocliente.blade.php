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
                                    <th>Cantidad</th>
                                    <th>Acccion</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datos as $dato)
                                    <tr>
                                        <td>{{$dato->Articulo}}</td>
                                        <td>{{$dato->Detalle}}</td>
                                        <td>{{$dato->Cantidad}}</td>
                                        <td><label id="selecionArticulo" class="btn btn-primary" onclick="seleccionArticulo('{{$dato->Articulo}}','{{$dato->Detalle}}');">Ver</label></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h4>Nº Pedido: </h4>
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="artclient" class="table table table-scroll table-striped">
                    <thead>
                    <tr>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <style type="text/css">
        body {
            color: #5d5d5d;
            font-family: Helvetica, Arial, sans-serif;
        }
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
            width: 70%;
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
            height: 420px;
        }

        .table-scroll tbody {
            position: absolute;
            overflow-y: scroll;
            height: 350px;
        }

        .table-scroll tr {
            width: 100%;
            table-layout: fixed;
            display: inline-table;
        }

        .table-scroll thead > tr > th {
            border: none;
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
                        order: [2,'desc']
                    }

            );
        } );

        function seleccionArticulo(nroarticulo,detalle){
            var table = $("#artclient");
            table.children().remove()
            table.append("<thead><tr><th>Cliente</th><th>Cantidad</th></tr></thead>")
            table.append("<tbody>")
            $.ajax({
                url: 'articulosclientes?articulo=' + nroarticulo,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['Cliente']+"</td><td>"+json['Cantidad']+"</tr>");
                    });
                    table.append("</tbody>")
                    $(".modal-content h4").html( detalle);
                }
            });
            // Get the modal
            var modal = document.getElementById('myModal');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal
            modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    </script>
@stop
