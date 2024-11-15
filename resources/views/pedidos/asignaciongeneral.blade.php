@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Asignacion de Pedidos
                    </div>
                    <div class="panel-body">
                        <div id="example-table"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('extra-javascript')

    <link rel="stylesheet" href="../../js/tabulador/tabulator5-5-2min.css">
    <script type="text/javascript" src="../../js/tabulador/tabulator5-5-2.min.js"></script>

   <script>
       idleTimer = null;
       idleState = false;
       idleWait = 180000;
       (function ($) {
       $(document).ready( function () {
           //llenarTabla();
           //paramLookup();
           $('*').bind('mousemove keydown scroll', function () {

               clearTimeout(idleTimer);

               if (idleState == true) {

                   // Reactivated event
               }

               idleState = false;

               idleTimer = setTimeout(function () {

                   // Idle Event
                   window.location.replace("/notasadhesivas");

                   idleState = true; }, idleWait);
           });

           $("body").trigger("mousemove");

       });
       }) (jQuery)

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
               url: '/asignaciongeneral/vendedoras',
               dataType : "json",
               async: false,
               success : function(json) {
                   var arr= json
                   var obj = {}; //create the empty output object
                   arr.forEach( function(item){
                       var key = Object.keys(item)[0]; //take the first key from every object in the array
                       obj[ key ] = item [ key ]; //assign the key and value to output obj
                   });
                   vendedoras = obj
                   console.log (vendedoras)
               }
           });
           //do some processing and return the param object
           return vendedoras;
       }
       /*
       $("#example-table").tabulator({
           height: "550px",
           initialSort:[
               {column:"nropedido", dir:"desc"}, //sort by this first
           ],
                columns: [
                    {title: "Pedido", field: "nropedido", sortable: true, width: 115},
                    {title: "Cliente", field: "cliente", sortable: true, width: 300, headerFilter:"input"},
                    {title: "Vendedora", field: "vendedora", width: 200, editor:"select", editorParams:paramLookup,headerFilter:"input"},
                    {title: "OrdenWeb", field: "ordenweb", sortable: true, width: 110},
                    {title: "Total", field: "total", sortable: true, width: 110},
                    {title: "TotalWeb", field: "totalweb", sortable: true, width: 110, bottomCalc:"sum",bottomCalcParams:{precision:2}},
                    {title: "Local", field: "local", sortable: true, width: 145},
                ],
                cellEdited:function(cell, value, data){
                    $.ajax({
                        url: "/asignaciongeneral/update",
                        data: cell.getRow().getData(),
                        type: "post"
                    })
                }
            });
            */

       var tableAsignacionPedidos = new Tabulator ("#example-table", {
           height: "550px",
           initialSort:[
               {column:"nropedido", dir:"desc"}, //sort by this first
           ],
           columns: [
               {title: "Pedido", field: "nropedido", headerSort : true, width: 115},
               {title: "Cliente", field: "cliente", headerSort : true, width: 300, headerFilter:"input"},
               {
                   title: "Vendedora",
                   field: "vendedora",
                   width: 200,
                   editor:"list",
                   editorParams: {
                       values: paramLookup(), // Lista de opciones para "Estado"
                       clearable: true // Permite limpiar la selección si es necesario
                   },
                   headerFilter:"input"},
               {title: "OrdenWeb", field: "ordenweb", headerSort : true, width: 110},
               {title: "Total", field: "total", headerSort : true, width: 110},
               {title: "TotalWeb", field: "totalweb", headerSort : true, width: 110, bottomCalc:"sum",bottomCalcParams:{precision:2}},
               {title: "Local", field: "local", headerSort : true, width: 145},
           ],
       })


       tableAsignacionPedidos.on("cellEdited", function(cell){
           $.ajax({
               url: "/asignaciongeneral/update",
               data: cell.getRow().getData(),
               type: "post"
           })
       })

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
       function llenarTabla() {
           // $("#example-table").tabulator("setData", '/asignaciongeneral/query');
           tableAsignacionPedidos.setData('/asignaciongeneral/query');
       }

       // Llama a la función `llenarTabla` después de que la tabla se haya construido completamente
       tableAsignacionPedidos.on("tableBuilt", function() {
           llenarTabla();
       });

       $(window).resize(function () {
           // $("#example-table").tabulator("redraw");
           tableAsignacionPedidos.redraw()
       });
    </script>
@stop
