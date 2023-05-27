<div id="myModalObjetivo" class="modal">
    <!-- Modal Ingreso -->
    <div id="modal-content" class="modal-content-objetivos">
        <span class="close">&times;</span>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Objetivo
                        </div>
                        <div class="panel-body">
                            <button id="download-xlsx" type="button" class="btn btn-primary">Bajar xlsx</button>
                            <div id="example-table-objetivos"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>

    /* Modal Content */
    .modal-content-objetivos {
        background-color: rgba(243, 255, 242, 0.91);
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 90%;
        overflow-y: auto;
        border-radius: 10%;
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


<script>
    function cargaModalObjetivos() {
        // llenarTabla(numMes,usuario_id)
        llenarTabla()
        var modal = document.getElementById('myModalObjetivo');

        // Get the <span> element that closes the modal
        var span = document.getElementById("close");

        // When the user clicks the button, open the modal
        modal.style.display = "block";

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
    //custom max min header filter
    var minMaxFilterEditor = function (cell, onRendered, success, cancel, editorParams) {
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

        function buildValues() {
            success({
                start: start.value,
                end: end.value,
            });
        }

        function keypress(e) {
            if (e.keyCode == 13) {
                buildValues();
            }

            if (e.keyCode == 27) {
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
    function minMaxFilterFunction(headerValue, rowValue, rowData, filterParams) {
        //headerValue - the value of the header filter element
        //rowValue - the value of the column in this row
        //rowData - the data for the row being filtered
        //filterParams - params object passed to the headerFilterFuncParams property

        if (rowValue) {
            if (headerValue.start != "") {
                if (headerValue.end != "") {
                    return rowValue >= headerValue.start && rowValue <= headerValue.end;
                } else {
                    return rowValue >= headerValue.start;
                }
            } else {
                if (headerValue.end != "") {
                    return rowValue <= headerValue.end;
                }
            }
        }

        return true; //must return a boolean, true if it passes the filter.
    }

    var vendedoras = {}
    //define lookup function
    function paramLookup(cell) {
        //cell - the cell component
        $.ajax({
            url: '/tipo_pagos',
            dataType: "json",
            success: function (json) {
                var arr = json
                var obj = {}; //create the empty output object
                arr.forEach(function (item) {
                    var key = Object.keys(item)[0]; //take the first key from every object in the array
                    obj[key] = item [key]; //assign the key and value to output obj
                });
                vendedoras = obj
            }
        });
        //do some processing and return the param object
        return vendedoras;
    }
    var estados = {}
    function estadosLookup(cell) {
        //cell - the cell component
        $.ajax({
            url: '/estados_financiera',
            dataType: "json",
            success: function (json) {
                var arr = json
                var obj = {}; //create the empty output object
                arr.forEach(function (item) {
                    var key = Object.keys(item)[0]; //take the first key from every object in the array
                    obj[key] = item [key]; //assign the key and value to output obj
                });
                estados = obj
            }
        });
        //do some processing and return the param object
        return estados;
    }

    $("#example-table-objetivos").tabulator({
        height: "550px",
        // initialSort:[
        //     {column:"NroFactura", dir:"asc"}, //sort by this first
        //   ],
        columns: [
            {title: "Cliente", field: "Cliente", sortable: true, width: 200, headerFilter: "input"},
            {title: "Fecha", field: "fecha", sortable: true, width: 100, headerFilter: "input"},
            {title: "NroFactura", field: "NroFactura", sortable: true, width: 110, headerFilter: "input"},
            {title: "Total", field: "Totales", sortable: true, width: 110, headerFilter: "input"},
            {title: "Envio", field: "Envio", sortable: true, width: 80},
            {title: "TotalEnvio", field: "TotalConEnvio", sortable: true, width: 110, headerFilter: "input"},
            {title: "A Cobrar", field: "Cobrar", sortable: true, width: 110, headerFilter: "input", bottomCalc: "sum"},
            {
                title: "Tipo de Pago",
                field: "tipo_pago",
                width: 150,
                editor: "select",
                editorParams: paramLookup,
                headerFilter: "input"
            },
            {
                title: "Estado",
                field: "nombre",
                sortable: true,
                width: 110,
                editor: "select",
                editorParams: estadosLookup,
                headerFilter: "input"
            },
            {title: "Comentario", field: "comentario", width: 115, editor: "textarea"},
        ],
        cellEdited: function (cell, value, data) {
            $.ajax({
                url: "/updateFactura/update",
                data: cell.getRow().getData(),
                type: "post"
            })
        },

    });

    function llenarTabla() {
        $("#example-table-objetivos").tabulator("setData", '/listaobjetivos');
    }
    $(window).resize(function () {
        $("#example-table-objetivos").tabulator("redraw");
    });
    $("#download-xlsx").click(function () {
        $("#example-table-objetivos").tabulator("download", "xlsx", "data.xlsx", {sheetName: "ReporteFinanciera"});
    });

</script>

