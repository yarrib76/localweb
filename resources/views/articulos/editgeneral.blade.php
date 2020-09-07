@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Seleccione Proveedor <label id="agregar" class="btn btn-primary" onclick="buscarProveedor();"><i class="fa fa-user"></i></label><label id="proveedor"></label>
                    </div>
                    <div class="panel-body">
                        <div id="example-table"></div>
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
                    <th>Nombre</th>
                    <th>Pais</th>
                    <th>Contacto</th>
                    <th>Seleccionar</th>
                </tr>
                </thead>
                <tbody>
                @foreach($proveedores as $provedor)
                    <tr>
                        <td>{{$provedor->Nombre}}</td>
                        <td>{{$provedor->Pais}}</td>
                        <td>{{$provedor->Contacto}}</td>
                        <td><label id="selecionProveedor" class="btn btn-primary" onclick="seleccionarProveedor('{{$provedor->Nombre}}');"><i class="fa fa-check"></i></label></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
       $(document).ready( function () {
            $('#reporte').DataTable({

                        "lengthMenu": [ [8,  16, 32, -1], [8, 16, 32, "Todos"] ],
                        language: {
                            search: "Buscar:",
                            "thousands": ",",
                            processing:     "Traitement en cours...",
                            lengthMenu:    "Mostrar _MENU_ clientes",
                            info:           "Mostrando del  _START_ al _END_ de _TOTAL_ clientes",
                            infoEmpty:      "0 moviles",
                            infoFiltered:   "(Filtrando _MAX_ clientes en total)",
                            infoPostFix:    "",
                            loadingRecords: "Chargement en cours...",
                            zeroRecords:    "No se encontraron clientes para esa busqueda",
                            emptyTable:     "No existen clientes",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Proximo",
                                last:       "Ultimo"
                            }
                        }
                    }

            );
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
                columns: [
                    {title: "Articulo", field: "Articulo", sortable: true, width: 115},
                    {title: "Detalle", field: "Detalle", sortable: true, width: 300, headerFilter:"input"},
                    {title: "Qty", field: "Cantidad", sortable: true, width: 65, editable:true, editor:"number"},
                    {title: "P.Origen", field: "PrecioOrigen", sortable: false,sorter: "number", editable:true, width:100, editor:"number"},
                    {title: "P.Convertido", field: "PrecioConvertido",sorter: "number", width:120, editable:true, editor:"number"},
                    {title: "Precio Manual", field: "PrecioManual",sorter: "number", width:130, editable:true, editor:"number"},
                    {title: "Gastos", field: "Gastos",sorter: "number", editable:true, editor:"number"},
                    {title: "Ganancia", field: "Ganancia",sorter: "number", editable:true, width:100 , editor:"number"},
                    {title: "Moneda", field: "Moneda"},
                ],
                cellEdited:function(cell, value, data){
                    $.ajax({
                        url: "/editargeneral/update",
                        data: cell.getRow().getData(),
                        type: "post"
                    })
                }
            });

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

       function seleccionarProveedor(nombreProveedor){
           llenarTabla(nombreProveedor)
           $("#proveedor").html(" " + nombreProveedor);
           var span = document.getElementsByClassName("close")[0];
           modal.style.display = "none";
       }
       function llenarTabla(nombreProveedor) {
           console.log(nombreProveedor)
           $("#example-table").tabulator("setData", '/editargeneral/query', {Proveedor: nombreProveedor});
       }
       $(window).resize(function () {
           $("#example-table").tabulator("redraw");
       });
    </script>
@stop
