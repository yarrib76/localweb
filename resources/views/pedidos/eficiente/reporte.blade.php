@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Articulos Del Pedido {{$nroPedido}} ({{$vendedora}}), Repetidos En Otros Pedidos </i> <button class="btn btn-primary" onclick="refresh()"><span class="glyphicon glyphicon-refresh"></span></button></div>
                    <div class="panel-body">
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Articulo</th>
                                <th>Detalle</th>
                                <th>EnPedido</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($articulosEnPedidos as $articuloEnPedido)
                                <tr>
                                    <td><a onclick="artiPedidos('{{$articuloEnPedido->NroArticulo}}', '{{$vendedora}}',
                                                '{{$articuloEnPedido->Detalle}}', '{{$articuloEnPedido->Imagesrc}}', '{{$articuloEnPedido->Stock}}')"> {{$articuloEnPedido->NroArticulo}}</a></td>
                                    <td>{{$articuloEnPedido->Detalle}}</td>
                                    <td>{{$articuloEnPedido->EnPedidos}}</td>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Articulo: </h3>
            <button target='_blank' class = 'btn btn-primary' onclick="verImagen()">Foto</button>
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="articulo-table" class="table table table-scroll table-striped">
                    <thead>
                    <tr>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="modalImage" class="modal">
        <span class="closeImagen">&times;</span>
        <!-- Modal content -->
        <div class="modal-content">
            <img id="imagen">
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
            width: 89%; /* Full width */
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
            width: 86%;
            overflow-y: auto;
        }
        #modalImage {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 50%;
            height: 50%;
            overflow-y: auto;
        }
        /* The Close Button */
        .closeImagen {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .closeImagen:hover,
        .closeImagen:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
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

    <!-- DataTables -->

    <script type="text/javascript">
        var modal = document.getElementById('myModal');
        var imagenName;
        $(document).ready( function () {
            $('#reporte').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ]
                    }

            );
        } );

        //Llena la tabla y muestra el modal con los pedidos que tienen el artículo.
        function artiPedidos (nroArticulo,vendedora,detalle,imagesrc,stock){
            imagenName = imagesrc
            llenarTabla(nroArticulo,vendedora)
            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal
            modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            //    llenarTablaTabulador()
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            $(".modal-content h3").html("Articulo: " + nroArticulo + " - " + detalle + " - En Stock:" + stock);
        }
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
        $("#articulo-table").tabulator({
            height: "270px",
            // initialSort:[
            //     {column:"NroFactura", dir:"asc"}, //sort by this first
            //   ],
            columns: [
                {title: "Articulo", field: "Articulo", sortable: true, width: 200},
                {title: "Detalle", field: "Detalle", sortable: true, width: 250},
                {title: "NroPedido", field: "NroPedido", sortable: true, width: 110,headerFilter:"input"},
                {title: "OrdenWeb", field: "OrdenWeb", sortable: true, width: 110,headerFilter:"input"},
                {title: "Cantidad", field: "Cantidad", sortable: true, width: 100},
                {title:"Cerrar",width:110, align:"center", formatter:"buttonTick", cellClick:function(e, cell){
                    if (confirm("Esta seguro que ya agrago el articulo " + cell.getRow().getData()['Articulo'] + "?")) {
                            cell.getRow().delete()
                            $.ajax({
                                url: "/pedidoeficienteArticuloPedidos/agregar",
                                data: cell.getRow().getData(),
                                type: "post"
                            })
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
                $.ajax({
                    url: "/updateFactura/update",
                    data: cell.getRow().getData(),
                    type: "post"
                })
            },
        });
        function llenarTabla(nroArticulo, vendedora) {
            $("#articulo-table").tabulator("setData", '/pedidoeficienteArticuloPedidos?nroArticulo=' + nroArticulo + '&vendedora=' + vendedora);
        }

        function refresh (){
            location.reload();
        }
        function verImagen() {
            // console.log(imagenName)
            var image = document.getElementById("imagen");
            var modalImage = document.getElementById('modalImage');
            // Get the <span> element that closes the modal
            var spanImage = document.getElementsByClassName("closeImagen")[0];
            // When the user clicks the button, open the modal
            modalImage.style.display = "block";
            // When the user clicks on <span> (x), close the modal
            spanImage.onclick = function () {
                modalImage.style.display = "none";
            }
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalImage) {
                    modalImage.style.display = "none";
                }
            }
            image.src = imagenName;
            image.style.width = '550px';
            image.style.height = '300px'
            image.style.maxWidth = '50%';
            image.style.maxHeight = '50%';
        }
    </script>
@stop