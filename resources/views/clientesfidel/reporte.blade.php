@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Fidelizacion de Clientes <input onclick="cambioEstado()" type="checkbox" id="estado">
                        <h5 className='element' id='estadoActual'></h5>
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
        #myModalCliente {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 120%; /* Full height */
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

        #modal-content-cliente {
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
    <div id="myModalCliente" class="modal">
        <div id="modal-content-cliente" class="modal-content">
            <span class="close">&times;</span>
            <h4 id="nombreClientes">Prueba</h4>
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="tabla_top_mercaderia" class="table table table-scroll table-striped">
                    <thead>
                    <tr>
                        <th>Articulo</th>
                        <th>Descripcion</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <h2 id="Promo"></h2>
        </div>
    </div>

@stop
@section('extra-javascript')

   <link rel="stylesheet" href="../../js/tabulador/tabulator.css">
   <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script>

   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

   <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
   <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>

   <!-- DataTables -->

   <script>
       var globalFidelizacion_id
       var estado = 0
       var table;
       $(document).ready( function () {
           llenarTabla(estado);
           document.getElementById('estadoActual').innerText = "Abiertos"
           paramLookup();
           etapasLookup();
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
               {column:"nropedido", dir:"desc"}, //sort by this first
           ],
                columns: [
                    {title: "Cliente", field: "Cliente", sortable: true, width: 150, headerFilter:"input", cellClick:function(e,cell){
                            clientesFidel(cell.getRow())}},
                    {title: "Vendedora", field: "vendedora", width: 115, editor:"select", editorParams:paramLookup,headerFilter:"input"},
                    {title: "Creado", field: "fecha_creacion", sortable: true, width: 145},
                    {title: "Celular", field: "cel_contacto", sortable: true, width: 120, formatter:"link", formatterParams:{url:function(cell){
                        return "https://wa.me/" + cell.getData().cel_contacto + "' target='_blank' "}}},
                    {title: "Ultima Compra", field: "fecha_ultima_compra", sortable: true, width: 145},
                    {title: "Promedio Compras", field: "promedioTotal", sortable: true, width: 120},
                    {title: "Cantidad", field: "cant_compras", sortable: true, width: 100},
                    {title: "Etapa", field: "nombre_etapa", sortable: true, width: 110,editor:"select",editorParams:etapasLookup,headerFilter:"input"},
                    {title: "Notas", width:100, align:"center", formatter:function iconFormatter(cell){
                        return "<img class='infoImage' src='refresh/agenda.png' height='50' width='50'>" + cell.getRow().getData()['cant_notas']}
                        ,cellClick:function(e, cell) {
                        notas_carrito(cell.getRow().getData()['idclientes_fidelizacion'],cell.getRow().getData()['Cliente'])

                    }},
                    {title:"Cerrar",width:110, align:"center", formatter:"buttonTick", cellClick:function(e, cell){
                        if (confirm("Esta seguro que quiere cerrar el carrito abandonado de " + cell.getRow().getData()['Cliente'] + "?")) {
                            $.ajax({
                                url: '/clientesFidelizacion/notas?idclientes_fidelizacion=' + cell.getRow().getData()['idclientes_fidelizacion'],
                                dataType : "json",
                                success : function(json) {
                                    console.log(isEmptyObject(json))
                                    if (!isEmptyObject(json)){
                                        cell.getRow().delete()
                                        $.ajax({
                                            url: "/clientesFidelizacion/finalizarClienteFidel",
                                            data: cell.getRow().getData(),
                                            type: "post"
                                        })
                                    } else alert('Para finalizar debe agregar una nota')
                                }
                            });

                         /*   cell.getRow().delete()
                            $.ajax({
                                url: "/carritosAbandonados/finalizarCarrito",
                                data: cell.getRow().getData(),
                                type: "post"
                            }) */
                        }
                    }}

                ],
           rowFormatter:function(row){
               var data = row.getData();
               var fechaCreacion = data.fecha_creacion
               var respuesta = fechaMonitoreo(fechaCreacion)
                if (respuesta == "Amarillo"){
                    row.getElement().css({"background-color":"yellow"});
                }
                if (respuesta == "Rojo"){
                    row.getElement().css({"background-color":"red"});
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
       function llenarTabla(estado) {
           $("#example-table").tabulator("setData", '/clientesFidelizacion/query?estado=' + estado);
       }
       $(window).resize(function () {
           $("#example-table").tabulator("redraw");
       });

       function notas_carrito(idclientes_fidelizacion,cliente){
           globalFidelizacion_id  = idclientes_fidelizacion
           var table = $("#comentarios");
           table.children().remove()
           table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
           $.ajax({
               url: '/clientesFidelizacion/notas?idclientes_fidelizacion=' + idclientes_fidelizacion,
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
           $(".modal-content #cliente").html( "Cliente: " + cliente);
       }


       function agregarNota(user_id){
           var textarea = $.trim($("textarea").val());
           if (textarea != ""){
               $.ajax({
                   url: '/clientesFidelizacion/agregarNotas?idclientes_fidelizacion=' + globalFidelizacion_id + "&" +
                   'user_id=' + user_id + "&" + 'textarea=' + textarea,
                   dataType : "json",
                   success : function(json) {
                       document.getElementById("textarea").value = "";
                       refreshfunctionComentario()
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
               url: '/clientesFidelizacion/notas?idclientes_fidelizacion=' + globalFidelizacion_id,
               dataType : "json",
               success : function(json) {
                   $.each(json, function(index, json){
                       table.append("<tr><td>"+json['nombre']+"</td><td>"+json['comentario']+
                               "</td><td>"+json['fechaFormateada']+"</td>"+ "</tr>");
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
               document.getElementById('estadoActual').innerText = "Cerrados"
           } else
           {
               llenarTabla(0);
               document.getElementById('estadoActual').innerText = "Abiertos"
           }
       }

       function clientesFidel(datos){
           idclientes_fidelizacion = (datos.getData()['idclientes_fidelizacion']);
           promo(datos);
           $.ajax({
               url: '/clientesFidelizacion/biFidel?idclientes_fidelizacion=' + idclientes_fidelizacion,
               dataType : "json",
               success : function(json) {
                   table = $('#tabla_top_mercaderia').DataTable({
                               dom: 'Bfrtip',
                               "autoWidth": false,
                               "bDestroy": true,
                               "pageLength" : 5,
                               order: [2,'desc'],
                               "aaData": json,
                               "columns": [
                                   {"data": "Articulo"},
                                   {"data": "Descripcion"},
                                   {"data": "Total"},
                               ]
                           }
                   )
               }
           });
           // Get the modal
           var modalCliente = document.getElementById('myModalCliente');

           // Get the <span> element that closes the modal
           var spanCliente = document.getElementsByClassName("close")[0];

           // When the user clicks the button, open the modal
           modalCliente.style.display = "block";

           // When the user clicks on <span> (x), close the modal
           spanCliente.onclick = function() {
               modalCliente.style.display = "none";
           }

           // When the user clicks anywhere outside of t he modal, close it
           window.onclick = function(event) {
               if (event.target == modalCliente) {
                   modalCliente.style.display = "none";
               }
           }

            $(".modal-content h4").html('Cliente: ' + datos.getData()['Cliente']);
       }

       function promo(datos){
           //22-03-2023 Reemplazar obteniendo valores del panel de Configuracion
           var promedioCompra_cant_compras = 12000;
           var promedioCompras_sin_cant_compras = 30000;
           var cant_compras = 3
           if(datos.getData()['promedioTotal'] >= promedioCompra_cant_compras && datos.getData()['cant_compras'] >= cant_compras){
               $(".modal-content h2").html('Envio Gratis!!!!');
           }else if(datos.getData()['promedioTotal'] >= promedioCompras_sin_cant_compras){
               $(".modal-content h2").html('Envio Gratis!!!!');
           }else {$(".modal-content h2").html('Sin Ofertas Disponibles')}
       }

       function fechaMonitoreo(fechaOriginal){
           // Convertir la fecha original en una instancia de la clase Date
           let fechaOriginal7Dias = new Date(fechaOriginal);
           let fechaOriginal4Dias = new Date(fechaOriginal);
           let fechaActual = new Date();
           let fechaActualFormateada = fechaActual.toISOString().slice(0, 10);
           // Sumarle 7 d�as a la fecha
           fechaOriginal7Dias.setDate(fechaOriginal7Dias.getDate() + 7);
           fechaOriginal4Dias.setDate(fechaOriginal4Dias.getDate() + 4);

           // Formatear la fecha resultante en formato yyyy-mm-dd
           let fecha7dia = ("0" + fechaOriginal7Dias.getDate()).slice(-2);
           let fecha7mes = ("0" + (fechaOriginal7Dias.getMonth() + 1)).slice(-2);
           let fecha7anio = fechaOriginal7Dias.getFullYear();
           let fechaFormateadaOriginal7Dias = fecha7anio + "-" + fecha7mes + "-" + fecha7dia;
           let fecha4dia = ("0" + fechaOriginal4Dias.getDate()).slice(-2);
           let fecha4mes = ("0" + (fechaOriginal4Dias.getMonth() + 1)).slice(-2);
           let fecha4anio = fechaOriginal4Dias.getFullYear();
           let fechaFormateadaOriginal4Dias = fecha4anio + "-" + fecha4mes + "-" + fecha4dia;


           let respuesta = "SinColor"
           if (fechaFormateadaOriginal4Dias <= fechaActualFormateada){
               respuesta = "Amarillo"
           }
           if (fechaFormateadaOriginal7Dias <= fechaActualFormateada ){
               respuesta = "Rojo"
           }
           return (respuesta); // Es verdadero si la fecha Original + 7 d�as es Mayor a la fecha Actual.
       }
    </script>
@stop
