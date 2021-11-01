@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Articulos Replicados
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

    <link rel="stylesheet" href="../../js/tabulador/tabulator.css">
    <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script>

    <script>
        $(document).ready( function () {
                llenarTabla();
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
        $("#example-table").tabulator({
            height: "550px",
            initialSort:[
                {column:"detalle", dir:"asc"}, //sort by this first
            ],

            columns: [
                {title: "Articulo", field: "articulo", sortable: true, width: 120, headerFilter:"input"},
                {title: "Detalle", field: "detalle", sortable: true, width: 350, headerFilter:"input"},
                {title: "Cantidad", field: "cantidad", sortable: true, width: 115},
                {title: "Sincronizar", field:"sincronizar", editor:"select", editorParams:{"Si":"Si", "No":"No"}}
            ],
            cellEdited:function(cell, value, data){
                $.ajax({
                    url: "/reportesArticulosWebnew/update",
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
        function llenarTabla() {
            $("#example-table").tabulator("setData", '/reportesArticulosWebnew/query');
        }
        $(window).resize(function () {
            $("#example-table").tabulator("redraw");
        });
    </script>
@stop
