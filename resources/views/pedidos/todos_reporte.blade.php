@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Todos</i></div>
                    <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>NroPedido</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Vendedora</th>
                                    <th>Factura</th>
                                    <th>Total</th>
                                    <th>OrdenWeb</th>
                                    <th>Estado</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                            </table>
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
            width: 100%;
            overflow-y: auto;
        }

        /* The Close Button */
        .closeVer {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .closeVer:hover,
        .closeVer:focus {
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

        #general{
            margin: auto;
            margin-top: 10px;
            width: auto;
            height: auto;
        }
        #mensajes{
            width: 550px;
            height: 300px;
        }
        #nuevomensajes{
            float: right;
            width: 300px;
            height: 300px;
        }
        .textarea{
            width: 300px;
            height: 120px;
            border: 3px solid #cccccc;
            padding: 5px;
            font-family: Tahoma, sans-serif;
            background-position: bottom right;
            background-repeat: no-repeat;
            resize: none;
        }
    </style>

    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        /* The Modal (background) */
        #myModalComentarios {
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
        #modal-content-comentarios {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 3px solid #888;
            width: 70%;
            overflow-y: auto;
        }
    </style>
    <style>
        @-webkit-keyframes greenPulse {
            from { background-color: #749a02; -webkit-box-shadow: 0 0 9px #333; }
            50% { background-color: #91bd09; -webkit-box-shadow: 0 0 18px #91bd09; }
            to { background-color: #749a02; -webkit-box-shadow: 0 0 9px #333; }
        }
        #botonComent {
            -webkit-animation-name: greenPulse;
            -webkit-animation-duration: 2s;
            -webkit-animation-iteration-count: infinite;
        }
    </style>

    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <img src="refresh/load.gif" height="100" width="100">
        </div>
    </div>
    <!-- Modal content -->
    <div id="modalVer" class="modal">
        <div class="modal-content">
            <span class="closeVer">&times;</span>
            <h3>Nº Pedido: </h3>
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="pedidos" class="table table table-scroll table-striped">
                    <thead>
                    <tr>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- The Modal Comentarios-->
    <div id="myModalComentarios" class="modal">

        <!-- Modal content -->
        <div id="modal-content-comentarios" class="modal-content">
            <span class="close1">&times;</span>
            <h3>Nº Pedido: </h3>
            <h5 id="cliente"></h5>
            <div id="general">
                <div id="nuevomensajes">
                    <textarea id="textarea" class="textarea is-warning" type="text" placeholder="Escriba una nota" rows="10"></textarea>
                    <div id="botones">
                        <button id="agregar"  class="btn btn-primary" onclick="agregarNota({{$user_id}});"><i class="fa fa-check"></i></button>
                        <button id="botoncerrar" class="btn btn-success" onclick="cerrar();"><i class="fa fa-close"></i></button>
                    </div>
                </div>
                <div id="mensajes">
                    <div class="col-xs-12 col-xs-offset-0 well">
                        <table id="comentarios" class="table table table-scroll table-striped">
                            <thead>
                            <tr>

                            </tr>
                            </thead>
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
            // Get the modal
            var modal = document.getElementById('myModal');
            // When the user clicks the button, open the modal
            modal.style.display = "block";
            $.ajax({
                'url': "/api/get_todos",
                'method': "GET",
                'contentType': 'application/json',
                success : function(json) {
                    for ( var i=0, ien=json.length ; i<ien ; i++ ) {
                        /*
                        switch (json[i]['Estado']) {
                            case "Empaquetado": json[i]['Estado'] = "<a  style='background-color: #87CEFA'</a>" + json[i]['Estado']
                                break;
                            case "Procesando": json[i]['Estado'] = "<a  style='background-color: #FFFF00'</a>" + json[i]['Estado']
                                break;
                            case "Facturado": json[i]['Estado'] = "<a  style='background-color: #00FF00'</a>" + json[i]['Estado']
                                break;
                            case "Cancelado": json[i]['Estado'] = "<a  style='background-color: #FF0000'</a>" + json[i]['Estado']
                                break;
                        }
                        */
                        if (json[i]['Comentarios'] == null) {
                            json[i]['Accion'] = "<br/>" +  "<a onclick = calcelarPedido(" + json[i]['NroPedido'] + ") ' target='_blank' class = 'btn btn-warning'>Cancel</a>"
                            + "<br/>" +  "<a onclick = cargoTablaPopup(" + json[i]['NroPedido'] + ") ' target='_blank' class = 'btn btn-info'>Ver</a>"
                            + "<br/>" +  "<a onclick = comentario(" + json[i]['id'] + "," + json[i]['NroPedido'] +  "," + "'" + json[i]['Cliente']+ "'" + ") id='botonSinComent' target='_blank' class = 'btn btn-success'> <i class='fa fa-book'></i> </a>"

                        }else {
                            json[i]['Accion'] = "<br/>" +  "<a onclick = calcelarPedido(" + json[i]['NroPedido'] + ") ' target='_blank' class = 'btn btn-warning'>Cancel</a>"
                            + "<br/>" +  "<a onclick = cargoTablaPopup(" + json[i]['NroPedido'] + ") ' target='_blank' class = 'btn btn-info'>Ver</a>"
                            + "<br/>" +  "<a onclick = comentario(" + json[i]['id'] + "," + json[i]['NroPedido'] +  "," + "'" + json[i]['Cliente']+ "'" + ") id='botonComent' target='_blank' class = 'btn btn-success'> <i class='fa fa-book'></i> </a>"
                        }
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
                                    { "data": "NroPedido" },
                                    { "data": "Cliente" },
                                    { "data": "Fecha"  },
                                    { "data": "Vendedora" },
                                    { "data": "Factura" },
                                    { "data": "Total" },
                                    { "data": "OrdenWeb" },
                                    { "data": "Estado" },
                                    { "data": "Accion" }
                                ],
                                "rowCallback": function( row, data, index ) {
                                    if ( data['Estado'] == "Empaquetado" )
                                    {
                                        $('td:eq(7)', row).css('background-color', '#87CEFA');
                                    }
                                    else if ( data["Estado"] == "Procesando" )
                                    {
                                        $('td:eq(7)', row).css('background-color', '#FFFF00');
                                    }
                                    else if ( data["Estado"] == "Facturado" )
                                    {
                                        $('td:eq(7)', row).css('background-color', '#00FF00');
                                    }
                                    else if ( data["Estado"] == "Cancelado" )
                                    {
                                        $('td:eq(7)', row).css('background-color', '#FF0000');
                                    }
                                }
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
        function getFoto (nroArticulo){
            $.ajax({
                url: 'api/fotoarticulo?nroArticulo=' + nroArticulo,
                dataType : "json",
                success : function(json) {
                    // console.log(json[0])
                    verImagen(json[0]['imagessrc'])
                }
            });
        }

        function calcelarPedido (nroPedido){
            if (confirm("Esta seguro que quiere cancelar el pedido Nro " + nroPedido + "?")){
                $.ajax({
                    url: '/api/cancelarPedido?nroPedido=' + nroPedido,
                    dataType : "json",
                    success : function(json) {
                        location.reload();
                    }
                });
            } else {

            }
        }

        function cargoTablaPopup(nroPedido){
            var table = $("#pedidos");
            table.children().remove()
            table.append("<thead><tr><th>Articulo</th><th>Detalle</th><th>Cantidad</th><th>Vendedora</th></tr></thead>")
            $.ajax({
                url: '/api/listaPedidosWeb?nroPedido=' + nroPedido,
                dataType : "json",
                success : function(json) {
                    console.log(json)
                    $.each(json, function(index, json){
                        console.log(json['Vendedora'])
                        table.append("<tr><td>"+json['Articulo']+"</td><td>"+json['Detalle']+
                                "</td><td>"+json['Cantidad']+"</td><td>"+json['Vendedora']+"</td></tr>");
                    });
                }
            });
            // Get the modal
            var modal = document.getElementById('modalVer');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("closeVer")[0];

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
            $(".modal-content h3").html("Pedido Nro:" + nroPedido);
        }
        function comentario(controlpedidos_id,nroPedido,cliente){
            glonalNroControlPedido = controlpedidos_id
            var table = $("#comentarios");
            table.children().remove()
            table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
            $.ajax({
                url: '/api/comentarios?controlpedidos_id=' + controlpedidos_id,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['nombre']+"</td><td>"+json['comentario']+
                                "</td><td>"+json['fechaFormateada']+"</td>"+ "</tr>");
                    });
                }
            });
            // Get the modal
            var modalComentario = document.getElementById('myModalComentarios');

            // Get the <span> element that closes the modal
            var spanComentario = document.getElementsByClassName("close1")[0];

            // When the user clicks the button, open the modal
            modalComentario.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            spanComentario.onclick = function() {
                modalComentario.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalComentario) {
                    modalComentario.style.display = "none";
                }
            }
            $(".modal-content h3").html("Pedido Nro:" + nroPedido);
            $(".modal-content #cliente").html( cliente);
        }
        function cerrar(){
            // Get the modal
            var modalComentario = document.getElementById('myModalComentarios');
            // When the user clicks on <span> (x), close the modal
            modalComentario.style.display = "none";
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalComentario) {
                    modalComentario.style.display = "none";
                }
            }
            document.getElementById("textarea").value = "";
        }

        function agregarNota(user_id){
            var textarea = $.trim($("textarea").val());
            if (textarea != ""){
                $.ajax({
                    url: '/api/agregarcomentarios?nroControlPedido=' + glonalNroControlPedido + "&" +
                    'user_id=' + user_id + "&" + 'textarea=' + textarea,
                    dataType : "json",
                    success : function(json) {
                        console.log(json)
                        document.getElementById("textarea").value = "";
                        refreshfunctionComentario()
                    }
                });
            } else alert("Debe agregar una nota")

        }
        function refreshfunctionComentario(){
            var table = $("#comentarios");
            table.children().remove()
            table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
            $.ajax({
                url: '/api/comentarios?controlpedidos_id=' + glonalNroControlPedido,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['nombre']+"</td><td>"+json['comentario']+
                                "</td><td>"+json['fechaFormateada']+"</td>"+ "</tr>");
                    });
                }
            });
        }

    </script>

@stop