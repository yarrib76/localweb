@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Control Ordenes de Compra
                        <table>
                            <tr>
                                <td>
                                    <h5 className='element' id='estadoActual'>Todas las Ordenes</h5>
                                </td>
                                <td>
                                    <input id='estado' onclick="cambioEstado()" type="checkbox">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="number" class="form-control" placeholder="Ingrese Orden" id="nroOrden">
                                </td>
                                <td>
                                    <button class="btn btn-danger" onclick="llenarTabla();">Buscar</button>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div class="panel-body">
                        <div id="example-table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            height: 80%;
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
    </style>

    <div id="myModalComentarios" class="modal">
    <!-- Modal content -->
        <div id="modal-content-comentarios" class="modal-content">
            <span class="close1">&times;</span>
            <h5 id="cliente"></h5>
            <div id="general">
                <div id="nuevomensajes">
                    <textarea id="textarea" class="textarea is-warning" type="text" placeholder="Escriba una nota"  rows="3" cols="125"></textarea>
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

   <link rel="stylesheet" href="../../js/tabulador/tabulator.css">
   <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script>

   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
   <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>

   <!-- DataTables -->

   <script>
       var globalCompra_id
       var estado = 0
       var table;
       $(document).ready( function () {
       })

       //custom max min header filter
       var minMaxFilterEditor = function(cell, onRendered, success, cancel, editorParams){

           var end;

           var container = document.createElement("span");

           //create and style inputs
           var start = document.createElement("input");
           start.setAttribute("type", "number");
           start.setAttribute("placeholder", "Min");
           start.setAttribute("min", 0);
           start.setAttribute("max", 100);
           start.style.padding = "4px";
           start.style.width = "50%";
           start.style.boxSizing = "border-box";

           start.value = cell.getValue();

           function buildValues(){
               success({
                   start:start.value,
                   end:end.value,
               });
           }

           function keypress(e){
               if(e.keyCode == 13){
                   buildValues();
               }

               if(e.keyCode == 27){
                   cancel();
               }
           }

           end = start.cloneNode();

           start.addEventListener("change", buildValues);
           start.addEventListener("blur", buildValues);
           start.addEventListener("keydown", keypress);

           end.addEventListener("change", buildValues);
           end.addEventListener("blur", buildValues);
           end.addEventListener("keydown", keypress);


           container.appendChild(start);
           container.appendChild(end);

           return container;
       }

       //custom max min filter function
       function minMaxFilterFunction(headerValue, rowValue, rowData, filterParams){
           //headerValue - the value of the header filter element
           //rowValue - the value of the column in this row
           //rowData - the data for the row being filtered
           //filterParams - params object passed to the headerFilterFuncParams property

           if(rowValue){
               if(headerValue.start != ""){
                   if(headerValue.end != ""){
                       return rowValue >= headerValue.start && rowValue <= headerValue.end;
                   }else{
                       return rowValue >= headerValue.start;
                   }
               }else{
                   if(headerValue.end != ""){
                       return rowValue <= headerValue.end;
                   }
               }
           }

           return true; //must return a boolean, true if it passes the filter.
       }

       var vendedoras = {}
       //define lookup function
       function paramLookup(cell){
           //cell - the cell component
           $.ajax({
               url: '/carritosAbandonados/vendedoras',
               dataType : "json",
               success : function(json) {
                   var arr= json
                   var obj = {}; //create the empty output object
                   arr.forEach( function(item){
                       var key = Object.keys(item)[0]; //take the first key from every object in the array
                       obj[ key ] = item [ key ] //assign the key and value to output obj
                   });
                   vendedoras = obj
               }
           });
           //do some processing and return the param object
           return vendedoras;
       }
       var etapas = {}
       //define lookup function
       function etapasLookup(cell){
           //cell - the cell component
           $.ajax({
               url: '/clientesFidelizacion/etapasFidel',
               dataType : "json",
               success : function(json) {
                   var arr= json
                   var obj = {}; //create the empty output object
                   arr.forEach( function(item){
                       var key = Object.keys(item)[0]; //take the first key from every object in the array
                       obj[ key ] = item [ key ] //assign the key and value to output obj
                   });
                   etapas = obj
               }
           });
           //do some processing and return the param object
           return etapas;
       }
       $("#example-table").tabulator({
                height: "550px",
               initialSort:[
               {column:"FechaCompra", dir:"Desc"}, //sort by this first
           ],
                columns: [
                    {title: "Orden", field: "OrdenCompra", headerFilter:"input", sortable: true, width: 80},
                    {title: "Articulo", field: "Articulo", sortable: true, width: 120},
                    {title: "Detalle", field: "Detalle", headerFilter:"input", sortable: true, width: 300},
                    {title: "QTY", field: "Cantidad", sortable: true, width: 70},
                    {title: "Fecha", field: "Fecha", headerFilter:"input", sortable: true, width: 115},
                    {title: "Observaciones", field: "Observaciones", sortable: true, width: 170},
                    {title: "PVenta", field: "PVenta", sortable: true, width: 85},
                    {title: "Notas", width:80, align:"center", formatter:function iconFormatter(cell){
                        return "<img class='infoImage' src='refresh/agenda.png' height='50' width='50'>" + cell.getRow().getData()['cant_notas']}
                        ,cellClick:function(e, cell) {
                        notas_ordenes(cell.getRow().getData()['id_compra'],cell.getRow().getData()['OrdenCompra'])

                    }},
                    {title:"Cerrar",width:80, align:"center", cellClick:function(e, cell){
                        if (confirm("Esta seguro que desea finalizar el control del articulo " + cell.getRow().getData()['Articulo'] + "?")) {
                            if (confirm('Esta Completa la Orden?')){
                                estado = 1
                            } else estado = 2
                            $.ajax({
                                url: 'ordenescomprasnotas?id_compra=' + cell.getRow().getData()['id_compra'],
                                dataType : "json",
                                success : function(json) {
                                    if (!isEmptyObject(json)){
                                        $.ajax({
                                            url: "ordenescompra/fincontrol?id_compra=" + "&estado=" + estado,
                                            data: cell.getRow().getData(),
                                            async: false,
                                            type: "post"
                                        })
                                        cambioEstado()
                                    } else alert('Para finalizar debe agregar una nota')
                                }
                            });
                        }
                    },
                        formatter: function (cell) {
                            var data = cell.getRow().getData();
                            var ordenControlada = data.ordenControlada;

                            // Verifica si la orden ya fue controlada (0 = No controlada, 1 = Controlada, 2 = Incompleta)
                            if (ordenControlada == "1" || ordenControlada == "2") {
                                return "<i class='fas fa-times'></i>"; // Ícono de cruz (times)
                            } else {
                                return "<i class='fas fa-check'></i>"; // Ícono de tilde (check)
                            }
                        }
                    }

                ],
           rowFormatter:function(row){
               var data = row.getData();
               var ordenControlada = data.ordenControlada
                //Verifico si la orden ya fue controlado 0 = No controlada - 1 = Controlada - 2 = Incompleta
                if (ordenControlada == "1"){
                    row.getElement().css({"background-color":"green"});
                }
                if (ordenControlada == "2"){
                    row.getElement().css({"background-color":"yellow"});
                }
           },
                cellEdited:function(cell, value, data){
                    console.log(cell.getData())
                    $.ajax({
                        url: "/clientesFidelizacion/update",
                        data: cell.getRow().getData(),
                        type: "post"
                    })
                }
            });

       function isEmptyObject(obj) {
           return Object.keys(obj).length === 0;
       }

       function buscarProveedor(){
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
       function llenarTabla(tipo) {
           nroOrden = document.getElementById('nroOrden').value
           if(tipo == 1){
               $("#example-table").tabulator("setData", '/ordenescomprasconsulta/todas');
           }else {
               $("#example-table").tabulator("setData", '/ordenescomprasconsulta?nroOrden=' + nroOrden);
           }
       }
       $(window).resize(function () {
           $("#example-table").tabulator("redraw");
       });

       function notas_ordenes(id_compra,orden){
           globalCompra_id  = id_compra
           var table = $("#comentarios");
           table.children().remove()
           table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
           $.ajax({
               url: '/ordenescomprasnotas?id_compra=' + id_compra,
               dataType : "json",
               success : function(json) {
                   console.log(json)
                   $.each(json, function(index, json){
                       table.append("<tr><td>"+json['nombre']+"</td><td>"+json['comentario']+
                               "</td><td>"+json['fecha']+"</td>"+ "</tr>");
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
           $(".modal-content #cliente").html( "Orden: " + orden);
       }


       function agregarNota(user_id){
           var textarea = $.trim($("textarea").val());
           if (textarea != ""){
               $.ajax({
                   url: 'ordenescomprasnotas/agregar?id_compra=' + globalCompra_id + "&" +
                   'user_id=' + user_id + "&" + 'textarea=' + textarea,
                   dataType : "json",
                   success : function(json) {
                       document.getElementById("textarea").value = "";
                       refreshfunctionComentario()
                     //  llenarTabla()
                       cambioEstado()
                   }
               });
           } else alert("Debe agregar una nota")

       }

       function refreshfunctionComentario(){
           var table = $("#comentarios");
           table.children().remove()
           table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
           $.ajax({
               url: '/ordenescomprasnotas?id_compra=' + globalCompra_id,
               dataType : "json",
               success : function(json) {
                   $.each(json, function(index, json){
                       table.append("<tr><td>"+json['nombre']+"</td><td>"+json['comentario']+
                               "</td><td>"+json['fecha']+"</td>"+ "</tr>");
                   });
               }
           });
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
       }

       function cambioEstado(){
           if (document.getElementById("estado").checked){
               llenarTabla(1);
               document.getElementById('nroOrden').disabled = true
           } else
           {
               llenarTabla(0);
               document.getElementById('nroOrden').disabled = false
           }
       }
    </script>
@stop
