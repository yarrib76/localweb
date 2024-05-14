@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Mi Correo
                        <button class="btn btn-primary" onclick="llenarTabla()"><span class="glyphicon glyphicon-refresh"></span></button>
                        <input id='PedidosPagos' onclick="pedidosPagos()" type="checkbox"> Pedidos Pagos
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

    <link rel="stylesheet" href="../../js/tabulador/tabulator.css">
    <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script>
    <script type="text/javascript" src="../../js/tabulador/xlsx.full.min.js"></script>

    <script>
        $(document).ready( function () {
            llenarTabla();
            paramLookup();
            sucursalesLookup();
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

        var tipo_transporte = {}
        //define lookup function
        function paramLookup(cell){
            //cell - the cell component
            $.ajax({
                url: '/tipo_sucursal',
                dataType : "json",
                success : function(json) {
                    var arr= json
                    var obj = {}; //create the empty output object
                    arr.forEach( function(item){
                        var key = Object.keys(item)[0]; //take the first key from every object in the array
                        obj[ key ] = item [ key ]; //assign the key and value to output obj
                    });
                    tipo_transporte = obj
                }
            });
            //do some processing and return the param object
            return tipo_transporte;
        }
        var estados = {}
        function sucursalesLookup (tipo_envio,cod_provincia){
            if (tipo_envio == "Sucursal"){
                $.ajax({
                    url: '/pub_sucursales?codigo_provincia=' + cod_provincia,
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
                return estados
            }
            //do some processing and return the param object
            return "";
        }

        $("#example-table").tabulator({
            height: "550px",
            // initialSort:[
            //     {column:"NroFactura", dir:"asc"}, //sort by this first
            //   ],
            columns: [
                {title:"Del",width:80, align:"center", formatter:"buttonCross", cellClick:function(e, cell){
                    cell.getRow().delete()
                    $.ajax({
                        url: "/miCorreoEliminar",
                        data: cell.getRow().getData(),
                        type: "post"
                    })}},
                {title: "Cliente", field: "destino_nombre",download:false, sortable: true, width: 150, headerFilter:"input"},
                {title: "Pedido", field: "nropedido", sortable: true, download:false, width: 100,headerFilter:"input"},
                {title: "vendedora", field: "vendedora", sortable: true, download:false, width: 110,headerFilter:"input"},
                {title: "Ordenweb", field: "ordenweb", sortable: true, download:false,width: 110,headerFilter:"input"},
                {title: "tipo_envio", field: "tipo_envio", sortable: true, download:false, width: 100,headerFilter:"input"},
                {title: "tipo_producto", field: "tipo_producto", sortable: true, width: 10},
                {title: "largo", field: "largo", sortable: true,  editor:true, width: 80},
                {title: "altura", field: "altura", sortable: true,  editor:true, width: 80},
                {title: "ancho", field: "ancho", sortable: true,  editor:true, width: 80},
                {title: "peso", field: "peso", sortable: true,  editor:true, width: 80},
                {title: "valor_del_contenido", field: "valor_del_contenido", editor:true, sortable: true, width: 110},
                {title: "provincia", field: "provincia", sortable: true, download:false, width: 120, formatter: function(cell){
                    if (cell.getRow().getData()['provincia'] == "Gran Buenos Aires" || cell.getRow().getData()['provincia'] == "Otro"){
                        cell.getElement().css({"background-color": "red"});
                    } else cell.getElement().css({"background-color": ""})
                    return cell.getValue()
                }},
                {title: "provincia_destino", field: "provincia_destino", sortable: true, width: 50},
                {title: "sucursal_destino", field: "sucursal_destino", sortable: true, width: 110,editor: "select", editorParams: function(cell) {
                    // En lugar de asignar estados directamente, devuelve una función que obtiene los estados
                    var tipo_envio = cell.getData().tipo_envio;
                    var provincia_destino = cell.getData().provincia_destino;
                    return sucursalesLookup(tipo_envio,provincia_destino);
                },formatter: function(cell){
                    if (cell.getRow().getData()['tipo_envio'] == "Sucursal" &&  cell.getRow().getData()['sucursal_destino'] == ""){
                        cell.getElement().css({"background-color": "red"});
                    } else cell.getElement().css({"background-color": ""})
                    return cell.getValue()
                },headerFilter:"input"},
                {title: "localidad_destino", field: "localidad_destino", sortable: true,  editor:true, width: 150},
                {title: "calle_destino", field: "calle_destino", editor:true, sortable: true, width: 130},
                {title: "altura_destino", field: "altura_destino", editor:true, sortable: true, width: 150},
                {title: "piso", field: "piso", sortable: true,  editor:true, width: 80},
                {title: "dpto", field: "dpto", sortable: true,  editor:true, width: 80},
                {title: "codpostal_destino", field: "codpostal_destino", editor:"number", sortable: true, width: 130,headerFilter:"input", formatter: function(cell){
                    if (cell.getRow().getData()['codpostal_destino'] == ""){
                        cell.getElement().css({"background-color": "red"});
                    } else cell.getElement().css({"background-color": ""})
                    return cell.getValue()
                }},
                {title: "destino_nombre", field: "destino_nombre", editor:true, sortable: true, width: 150,headerFilter:"input"},
                {title: "Ordenweb", field: "ordenweb", sortable: true, download:false,width: 110,headerFilter:"input"},
                {title: "destino_email", field: "destino_email", sortable: true, width: 80,headerFilter:"input"},
                {title: "cod_area_tel", field: "cod_area_tel", sortable: true, width: 1},
                {title: "tel", field: "tel", sortable: true, width: 1},
                {title: "cod_area_cel", field: "cod_area_cel", sortable: true, editor:true, width: 80},
                {title: "cel", field: "cel", sortable: true, editor:true, width: 110,headerFilter:"input"},
                {title: "numero_orden", field: "numero_orden", sortable: true, editor:true, width: 110,headerFilter:"input"},
                //    {title: "tipo_producto", field: "tipo_producto", sortable: true, width: 10,headerFilter:"input"},
                //    {title: "Tipo de Pago", field: "tipo_pago", width: 150, editor:"select", editorParams:paramLookup,headerFilter:"input"},
                //    {title: "Estado", field: "nombre", sortable: true, width: 110,editor:"select",editorParams:estadosLookup,headerFilter:"input"},
                //    {title: "Comentario", field: "comentario", width: 115,editor:"textarea"},
            ],
            cellEdited:function(cell, value, data){
                $.ajax({
                    url: "/miCorreoUpdate",
                    data: cell.getRow().getData(),
                    type: "post"
                })
            },

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
        function llenarTabla() {
            document.getElementById("PedidosPagos").checked = false;
            $("#example-table").tabulator("setData", '/miCorreoCargaDatos');
        }
        $(window).resize(function () {
            $("#example-table").tabulator("redraw");
        });
        $("#download-xlsx").click(function(){
            $("#example-table").tabulator("download", "csv", "data.csv", {sheetName:"ReporteFinanciera", delimiter: ";"});
        });

        function pedidosPagos(){
            if (document.getElementById("PedidosPagos").checked){
                $("#example-table").tabulator("redraw");
                $("#example-table").tabulator("setData", '/miCorreoCargaDatos?tipo=Pagados');
            }else llenarTabla();
        }
    </script>
@stop
