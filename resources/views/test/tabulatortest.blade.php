<div id="myModalArticulos" class="modal">
    <!-- Modal Ingreso -->
    <div id="modal-content-articulos" class="modal-content">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <span id="close-articulos" class="close">&times;</span>

                    <div class="panel-heading"><i class="fa fa-cog">Articulos</i></div>
                        <div class="panel-body">
                            <div id="table-articulos"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #myModalArticulos {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 50px; /* Location of the box */
        left: 0;
        top: -10%;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }
    /* Modal Content */
    #modal-content-articulos {
        background-color: rgba(243, 255, 242, 0.91);
        margin: auto;
        padding: 100px;
        border: 1px solid #888;
        width: 100%;
        height: 100%;
        overflow-y: auto;
    }

    /* The Close Button */
    #close-articulos {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    #close-articulos:hover,
    #close-articulos:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<script>
    var modalArticulos = document.getElementById('myModalArticulos');
    // Get the <span> element that closes the modal
    var spanArticulos = document.getElementById("close-articulos");
    var precioArticulo;
    var precioArgentina;
    function cargoModalArticulos(){
        getArticulos()
        // When the user clicks the button, open the modal
        modalArticulos.style.display = "block";

        table.setHeaderFilterFocus("Detalle");

        // When the user clicks on <span> (x), close the modal
        spanArticulos.onclick = function() {
            modalArticulos.style.display = "none";
            table.clearFilter('Detalle', 'Articulo', 'Cantidad')
        }
    }
        table = new Tabulator("#table-articulos", {
        height: "550px",
        // initialSort:[
        //     {column:"NroFactura", dir:"asc"}, //sort by this first
        //   ],
        columns: [
            {title: "Articulo", field: "Articulo", sortable: true, width: 150,headerFilter:"input"},
            {title: "Detalle", field: "Detalle", sortable: true, width: 350,headerFilter:"input"},
            {title: "Cantidad", field: "Cantidad", sortable: true, width: 110,headerFilter:"input"},
            {title: "Accion",width:100, align:"center", cellClick:function(e, cell){
                globalNroArticulo.value  = cell.getRow().getData()['Articulo'];
                globalDetalle.value  = cell.getRow().getData()['Detalle'];
                globalStock.value  = cell.getRow().getData()['Cantidad'];
                $.ajax({
                    url: "/getPrecio?nroArticulo=" + cell.getRow().getData()['Articulo'],
                    dataType: "json",
                    async: false,
                    success: function(json){
                        precioArticulo = (json[0]['PrecioVenta']);
                        precioArgentina = (json[0]['PrecioArgen']);
                    },
                })
                globalPrecioVenta.value = precioArticulo;
                globalPrecioArgen = precioArgentina;
                modalArticulos.style.display = "none";
                globalCantidad.value = 1;
                globalCantidad.focus();
                globalBtnAgregar.disabled = false
            },
                formatter: function (cell) {
                    return "<button class='btn-info'>Agregar</button>"; // Ícono de cruz (times)
                }
            }
        ],

    });
    // Agregar un manejador de eventos keydown para la tabla
    $("#table-articulos").on("keydown", function(e) {
        // Verificar si la tecla presionada es la tecla Tab (código 9)
        if (e.keyCode === 13) {
            e.preventDefault(); // Prevenir el comportamiento predeterminado de la tecla Tab

            var visibleCells = $("#table-articulos .tabulator-row[style!='display: none;'] .tabulator-cell");
            visibleCells.trigger("click")


        //    var firstVisibleCell = $("#table-articulos .tabulator-row[style!='display: none;'] .tabulator-cell:first");

        //    console.log(firstVisibleCell);
        /*
            if (firstVisibleCell.length > 0) {
                console.log("Entre")
                firstVisibleCell.trigger("click");
            }
        */
            /*
            visibleCells.each(function() {
                $(this).trigger("click");
            });
            */
            // Obtener todas las filas de la tabla
            //var table = $("#table-articulos").tabulator("getData");
            // console.log(table[0])
            // Iterar a través de las filas y mostrar la información
          //  table.forEach(function(rowData, index) {
          //      console.log("Fila " + (index + 1) + ":", rowData);
          //  });
        }

    });

    function getArticulos(){
        table.setData('/getArticulos');
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
</script>

