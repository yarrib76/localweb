<div id="myModalObjetivo" class="modal">
    <!-- Modal Ingreso -->
    <div id="modal-content" class="modal-content-objetivos">
        <span class="closeObjetivos" id="closeObjetivos">&times;</span>
        <button class="modal-minimize" onclick="minimizeModal()">_</button>
        <button class="modal_maximize" onclick="maximizeModal()">[]</button>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Objetivo
                            <input type="text" id="nombreVendedora" style="border: hidden; color: white; background-color: #347bb7">
                        </div>
                        <div class="panel-body">
                            <button id="download-xlsx-Objetivos" type="button" class="btn btn-primary">Bajar xlsx</button>
                            <select id="selectObjetivos">
                                <option>Enero</option>
                                <option>Febrero</option>
                                <option>Marzo</option>
                                <option>Abril</option>
                                <option>Mayo</option>
                                <option>Junio</option>
                                <option>Julio</option>
                                <option>Agostos</option>
                                <option>Septiembre</option>
                                <option>Octubre</option>
                                <option>Noviembre</option>
                                <option>Diciembre</option>
                            </select>
                            <button id="resetObjetivos" onclick="crearObjetivos()" style="color: #0000FF">Crear Objetivo</button>
                            <button onclick="resetObjetivos()" style="color: #0000FF">Reset</button>
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
    .closeObjetivos {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .closeObjetivos:hover,
    .closeObjetivos:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    .modal-minimize {
        background: none;
        border: none;
        color: #aaaaaa;
        font-size: 28px;
        font-weight: bold;
    }

    .modal_maximize {
        background: none;
        border: none;
        color: #aaaaaa;
        font-size: 28px;
        font-weight: bold;
    }
</style>


<script>
    function cargaModalObjetivos(usuario_id) {
        llenarTablaObjetivos(usuario_id)
        document.getElementById('nombreVendedora').value = nombre.value
        var modal = document.getElementById('myModalObjetivo');

        // Get the <span> element that closes the modal
        var span = document.getElementById("closeObjetivos");

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

    $("#example-table-objetivos").tabulator({
        height: "550px",
        // initialSort:[
        //     {column:"NroFactura", dir:"asc"}, //sort by this first
        //   ],
        columns: [
            {title:"Fecha", field:"mes", width:100},
            {//create column group
                title:"Fichaje",
                columns:[
                    {title:"Objetivo", field:"fich_obj", editor:"input", sorter:"number", width:90,
                        formatter: function(cell, formatterParams, onRendered) {
                            var value = cell.getValue();
                            if (value !== null && value !== undefined) {
                                value = "<=" + value  // Agregar el símbolo % al valor
                            }
                            return value;
                        },
                    },
                    {title:"Alcance", field:"fich_alcance", editor:"input", sorter:"number", width:90},
                ],
            },
            {//create column group
                title:"Pedidos",
                columns:[
                    {title:"Objetivo", field:"ped_obj", editor:"input", width:90,
                        formatter: function(cell, formatterParams, onRendered) {
                            var value = cell.getValue();
                            if (value !== null && value !== undefined) {
                                value = ">=" + value + "%"; // Agregar el símbolo % al valor
                            }
                            return value;
                        },
                    },
                    {title:"Alcance", field:"ped_alcance", editor:"input", width:90,
                        formatter: function(cell, formatterParams, onRendered) {
                            var value = cell.getValue();
                            if (value !== null && value !== undefined) {
                                value += "%"; // Agregar el símbolo % al valor
                            }
                            return value;
                        },
                    },
                ],
            },
            {//create column group
                title:"Venta Salon",
                columns:[
                    {title:"Objetivo", field:"v_salon_obj", editor:"input", width:90,
                        formatter: function(cell, formatterParams, onRendered) {
                            var value = cell.getValue();
                            if (value !== null && value !== undefined) {
                                value = ">=" + value + "%"; // Agregar el símbolo % al valor
                            }
                            return value;
                        },
                    },
                    {title:"Alcance", field:"v_salon_alcance", editor:"input", width:90,
                        formatter: function(cell, formatterParams, onRendered) {
                            var value = cell.getValue();
                            if (value !== null && value !== undefined) {
                                value += "%"; // Agregar el símbolo % al valor
                            }
                            return value;
                        },
                    },
                ],
            },
            {//create column group
                title:"Pedidos Cancelados",
                columns:[
                    {title:"Objetivo", field:"cancel_obj", editor:"input", width:90,
                        formatter: function(cell, formatterParams, onRendered) {
                            var value = cell.getValue();
                            if (value !== null && value !== undefined) {
                                value = "<=" + value  // Agregar el símbolo % al valor
                            }
                            return value;
                        },
                    },
                    {title:"Alcance", field:"cancel_alcance", editor:"input", width:90},
                ],
            },
            {//create column group
                title:"No Encuestados",
                columns:[
                    {title:"Objetivo", field:"no_encuesta_obj", editor:"input", width:90,
                        formatter: function(cell, formatterParams, onRendered) {
                            var value = cell.getValue();
                            if (value !== null && value !== undefined) {
                                value = "<=" + value + "%"; // Agregar el símbolo % al valor
                            }
                            return value;
                        },
                    },
                    {title:"Alcance", field:"no_encuesta_alcance", editor:"input", width:90,
                        formatter: function(cell, formatterParams, onRendered) {
                            var value = cell.getValue();
                            if (value !== null && value !== undefined) {
                                value + "%"; // Agregar el símbolo % al valor
                            }
                            return value;
                        },
                    },
                ],
            },
            {//create column group
                title:"Fidelizacion",
                columns:[
                    {title:"Objetivo", field:"fidel_obj", editor:"input", width:90,
                        formatter: function(cell, formatterParams, onRendered) {
                            var value = cell.getValue();
                            if (value !== null && value !== undefined) {
                                value = ">=" + value  // Agregar el símbolo % al valor
                            }
                            return value;
                        },
                    },
                    {title:"Alcance", field:"fidel_alcance", editor:"input", width:90},
                ],
            },
        ],
        cellEdited: function (cell, value, data) {
            $.ajax({
                url: "/objetivosUpdate",
                data: cell.getRow().getData(),
                type: "post"
            })
        },

    });

    function llenarTablaObjetivos(usuario_id) {
        $("#example-table-objetivos").tabulator("setData", '/listaObjetivos?usuario_id=' + usuario_id );
    }
    $(window).resize(function () {
        $("#example-table-objetivos").tabulator("redraw");
    });
    $("#download-xlsx-Objetivos").click(function () {
        $("#download-xlsx-Objetivos").tabulator("download", "xlsx", "data.xlsx", {sheetName: "ReporteFinanciera"});
    });

    function crearObjetivos(){
        var selectMes = document.getElementById('selectObjetivos')
        var mes = selectMes.options[selectMes.selectedIndex].text
        $.ajax({
            url: "/crearObjetivo?mes=" + mes + "&usuario_id=" + '{{$id}}',
            dataType : "json",
            success : function(json) {
                alert(json)
                llenarTablaObjetivos({{$id}})
            }
        })
    }

    function resetObjetivos(){
        $.ajax({
            url: "/resetObjetivos?usuario_id=" + '{{$id}}',
            dataType: "json",
            success: function(json){
                llenarTablaObjetivos({{$id}})
            }
        })
    }

    function  minimizeModal()
    {
        var modal = document.getElementById("myModalObjetivo");
        modal.style.height = "50px"; // Ajusta la altura del modal para minimizarlo
    }
     function maximizeModal(){
         var modal = document.getElementById("myModalObjetivo");
         modal.style.height = "auto"; // Ajusta la altura del modal para minimizarlo
     }
</script>

