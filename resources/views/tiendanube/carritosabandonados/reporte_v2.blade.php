@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">CheckOut Estado <input onclick="cambioEstado()" type="checkbox" id="estado">
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

   <script>
       var globalCarrito_id
       var estado = 0
       $(document).ready( function () {
           llenarTabla(estado);
           document.getElementById('estadoActual').innerText = "Abiertos"
           paramLookup();
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
                   console.log (vendedoras)
               }
           });
           //do some processing and return the param object
           return vendedoras;
       }
       
       $("#example-table").tabulator({
                height: "550px",
           initialSort:[
               {column:"nropedido", dir:"desc"}, //sort by this first
           ],
                columns: [
                    {title: "Contacto", field: "nombre_contacto", sortable: true, width: 150, headerFilter:"input"},
                    {title: "Vendedora", field: "vendedora", width: 115, editor:"select", editorParams:paramLookup,headerFilter:"input"},
                    {title: "Celular", field: "cel_contacto", sortable: true, width: 120, formatter:"link", formatterParams:{url:function(cell){
                        return "https://wa.me/" + cell.getData().cel_contacto + "' target='_blank' "}}},
                    {title: "Total", field: "total", sortable: true, width: 110, bottomCalc:"sum",bottomCalcParams:{precision:2}},
                    {title: "Email", field: "email_contacto", sortable: true, width: 250},
                    {title: "Fecha", field: "fecha", sortable: true, width: 145},
                    {title: "Notas", width:100, align:"center", formatter:function iconFormatter(cell){
                        return "<img class='infoImage' src='refresh/agenda.png' height='50' width='50'>" + cell.getRow().getData()['cant_notas']}
                        ,cellClick:function(e, cell) {
                        console.log(cell.getRow().getData())
                        notas_carrito(cell.getRow().getData()['id_carritos'],cell.getRow().getData()['nombre_contacto'])

                    }},
                    {title:"Cerrar",width:110, align:"center", formatter:"buttonTick", cellClick:function(e, cell){
                        if (confirm("Esta seguro que quiere cerrar el carrito abandonado de " + cell.getRow().getData()['nombre_contacto'] + "?")) {
                            $.ajax({
                                url: '/carritosAbandonados/notasCarritos?id_carrito=' + cell.getRow().getData()['id_carritos'],
                                dataType : "json",
                                success : function(json) {
                                    console.log(isEmptyObject(json))
                                    if (!isEmptyObject(json)){
                                        cell.getRow().delete()
                                        $.ajax({
                                            url: "/carritosAbandonados/finalizarCarrito",
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
                cellEdited:function(cell, value, data){
                    console.log(cell.getData())
                    $.ajax({
                        url: "/carritosAbandonados/updateVendedora",
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
           $("#example-table").tabulator("setData", '/carritosAbandonados/query?estado=' + estado);
       }
       $(window).resize(function () {
           $("#example-table").tabulator("redraw");
       });

       function notas_carrito(id_carrito,cliente){
           globalCarrito_id  = id_carrito
           var table = $("#comentarios");
           table.children().remove()
           table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
           $.ajax({
               url: '/carritosAbandonados/notasCarritos?id_carrito=' + id_carrito,
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
                   url: '/carritosAbandonados/agregarNota?id_carrito=' + globalCarrito_id + "&" +
                   'user_id=' + user_id + "&" + 'textarea=' + textarea,
                   dataType : "json",
                   success : function(json) {
                       console.log(json)
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
               url: '/carritosAbandonados/notasCarritos?id_carrito=' + globalCarrito_id,
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
    </script>
@stop
