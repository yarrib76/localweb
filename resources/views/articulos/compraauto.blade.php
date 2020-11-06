@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Seleccione Articulo<label id="agregar" class="btn btn-primary" onclick="buscarArticulos();"><i class="fa fa-user"></i></label><label id="artiuclo"></label>
                    </div>
                    <div id="example-table">
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal Comentarios-->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
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
        </div>
    </div>
    <div id="myModalCarga" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <img src="refresh/load.gif" height="100" width="100">
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
        width: 80%;
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

    #example-table{
        width:100%;
        font-family:sans-serif;
    }
    #myModalCarga {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        width: 15%;
        height: 25%;
        overflow-y: auto;
    }
</style>

@stop
@section('extra-javascript')

   <link rel="stylesheet" href="../../js/tabulador/tabulator.css">
   <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script>

   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">

   <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
   <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>



   <script>
       var modal = document.getElementById('myModal');
       var articulosSeleccionados;
       $(document).ready( function () {
           llenarTablaTabulador()
        } );

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

       $("#example-table").tabulator({
                height: "550px",
                fitColumns: true,
                index:"Articulo",
                columns: [
                    {title: "Articulo", field: "Articulo", sortable: true, width: 300},
                    {title: "Detalle", field: "Detalle", sortable: true, width: 500, headerFilter:"input"},
                    {title: "Alerta", field: "cant_alerta",width: 140, editor:"number"},
                    {title:"Eliminar",width:200, align:"center", formatter:"buttonCross", cellClick:function(e, cell){
                        cell.getRow().delete()
                        $.ajax({
                            url: "api/compraauto/eliminar",
                            data: cell.getRow().getData(),
                            type: "post"
                        })}}
                ],
                cellEdited:function(cell, value, data){
                    $.ajax({
                        url: "api/compraauto/editar",
                        data: cell.getRow().getData(),
                        type: "post"
                    })
                },
            });

       function buscarArticulos(){
           cargarTablaArticulos()
           // Get the <span> element that closes the modal
           var span = document.getElementsByClassName("close")[0];

           // When the user clicks the button, open the modal
           modal.style.display = "block";

           // When the user clicks on <span> (x), close the modal
           span.onclick = function() {
               modal.style.display = "none";
               llenarTablaTabulador()
           }

           // When the user clicks anywhere outside of the modal, close it
           window.onclick = function(event) {
               if (event.target == modal) {
                   modal.style.display = "none";
               }
           }
       }

       //La función agrega el articulo a la lista de los artículos de Compra automática
       function agregarArtCompraauto(nroArticulo, nroColumna){
           document.getElementById("agregarArtCompAuto" + nroColumna).disabled = true
           $.ajax({
               'url': "/api/compraauto_agregar?nroArticulo=" + nroArticulo,
               'method': "GET",
               'contentType': 'application/json',
               success : function(json) {
               }
           })

       }


       function cargarTablaArticulos(){
           var modalCarga = document.getElementById('myModalCarga');
           // When the user clicks the button, open the modal
           modalCarga.style.display = "block";
           eliminarTabla()
           $.ajax({
               'url': "/api/compraauto",
               'method': "GET",
               'contentType': 'application/json',
               success : function(json) {
                   for ( var i=0, ien=json.length ; i<ien ; i++ ) {
                       json[i]['Accion'] = "<button class='btn btn-primary' id='agregarArtCompAuto" + i + "' onclick='agregarArtCompraauto(" + json[i]['Articulo'] + ", " + i +" )'>Agregar </button>"
                   }
                   table = $('#reporte').DataTable({
                               dom: 'Bfrtip',
                               "autoWidth": false,
                               "bDestroy": true,
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
                   modalCarga.style.display = "none";
               },
           })
       }

       function eliminarTabla(){
           if(typeof table != "undefined"){
               // table.destroy()
               // Para evitar hacer un destroy que demora mas tiempo, se agrego el parametro "bDestroy en las propiedades de la tabla" y luego hago un clear.
               table.clear().draw();
           }
           return
       }

       function seleccionarProveedor(nombreProveedor){
           llenarTabla(nombreProveedor)
           $("#proveedor").html(" " + nombreProveedor);
           var span = document.getElementsByClassName("close")[0];
           modal.style.display = "none";
       }

       function llenarTablaTabulador(nombreProveedor) {
           $("#example-table").tabulator("setData", '/api/compraauto_llenarTabulador', {Proveedor: nombreProveedor});
       }
       $(window).resize(function () {
           $("#example-table").tabulator("redraw");
       });
    </script>
@stop
