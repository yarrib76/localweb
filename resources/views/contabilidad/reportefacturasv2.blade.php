@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Facturas
                    </div>
                    <div class="panel-body">
                        <button id="download-xlsx" type="button" class="btn btn-primary">Bajar xlsx</button>
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
   <script type="text/javascript" src="../../js/tabulador/xlsx.full.min.js"></script>

   <script>
       $(document).ready( function () {
          // llenarTabla();
           // paramLookup();
           // estadosLookup();
       });

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
               url: '/tipo_pagos',
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
               }
           });
           //do some processing and return the param object
           return vendedoras;
       }
       var estados = {}
       function estadosLookup (cell){
           //cell - the cell component
           $.ajax({
               url: '/estados_financiera',
               dataType : "json",
               async: false,
               success : function(json) {
                   var arr= json
                   var obj = {}; //create the empty output object
                   arr.forEach( function(item){
                       var key = Object.keys(item)[0]; //take the first key from every object in the array
                       obj[ key ] = item [ key ]; //assign the key and value to output obj
                   });
                   estados = obj
               }
           });
           //do some processing and return the param object
           return estados;
       }

       var tableFacturas = new Tabulator ("#example-table",
               {
       // $("#example-table").tabulator({
                height: "550px",
          // initialSort:[
          //     {column:"NroFactura", dir:"asc"}, //sort by this first
        //   ],
                columns: [
                    {title: "Cliente", field: "Cliente",  width: 200,headerFilter:"input"},
                    {title: "Fecha", field: "fecha",  width: 100,headerFilter:"input"},
                    {title: "NroFactura", field: "NroFactura",  width: 110,headerFilter:"input"},
                    {title: "Total", field: "Totales",  width: 110,headerFilter:"input"},
                    {title: "Envio", field: "Envio",  width: 80},
                    {title: "TotalEnvio", field: "TotalConEnvio", width: 110,headerFilter:"input"},
                    {title: "A Cobrar", field: "Cobrar", width: 110,headerFilter:"input", bottomCalc:"sum"},
                    {
                        title: "Tipo de Pago",
                        field: "tipo_pago",
                        width: 150,
                        editor: "list",
                        editorParams: {
                            values: paramLookup(), // Lista de opciones para "Tipo de Pago"
                            clearable: true // Permite limpiar la selección si es necesario
                        },
                        headerFilter: "input"
                    },
                    {
                        title: "Estado",
                        field: "nombre",
                        width: 110,
                        editor: "list",
                        editorParams: {
                            values: estadosLookup(), // Lista de opciones para "Estado"
                            clearable: true // Permite limpiar la selección si es necesario
                        },
                        headerFilter: "input"
                    },
                    {title: "pagomixto", field: "pagomixto", width: 115, editor:true},
                    {title: "Comentario", field: "comentario", width: 115,editor:"textarea"},
                ],
                });

       tableFacturas.on("cellEdited", function(cell){
           $.ajax({
               url: "/updateFactura/update",
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
          // $("#example-table").tabulator("setData", '/listarfacturas');
           tableFacturas.setData('/listarfacturas');
       }
       
       // Evento para descargar la tabla en formato Excel
       $("#download-xlsx").click(function(){
           tableFacturas.download("xlsx", "data.xlsx", {sheetName:"ReporteFinanciera"});
       });

       // Llama a la función `llenarTabla` después de que la tabla se haya construido completamente
       tableFacturas.on("tableBuilt", function() {
           llenarTabla();
       });

       $(window).resize(function () {
           tableFacturas.redraw();
       });

    </script>
@stop
