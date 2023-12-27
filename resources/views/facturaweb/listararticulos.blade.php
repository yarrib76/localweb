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
        top: -5%;
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
        tableArticulos.setHeaderFilterFocus("Detalle");

        // When the user clicks on <span> (x), close the modal
        spanArticulos.onclick = function() {
            modalArticulos.style.display = "none";
            tableArticulos.clearFilter('Detalle', 'Articulo', 'Cantidad')
        }
    }
        var tableArticulos = new Tabulator("#table-articulos", {
        height: "550px",
        // initialSort:[
        //     {column:"NroFactura", dir:"asc"}, //sort by this first
        //   ],
        columns: [
            {title: "Articulo", field: "Articulo", width: 150,headerFilter:"input"},
            {title: "Detalle", field: "Detalle",  width: 350,headerFilter:"input"},
            {title: "Cantidad", field: "Cantidad", width: 110,headerFilter:"input"},
            {title: "Accion",width:100, cellClick:function(e, cell){
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
                $.ajax({
                    url: 'api/fotoarticulo?nroArticulo=' + cell.getRow().getData()['Articulo'],
                    dataType : "json",
                    success : function(json) {
                        if (json.length != 0) {
                            globalFotoArticulo.src = json[0]['imagessrc']
                        } else {
                            globalFotoArticulo.src = "../../imagenes/sinfoto.png"
                        }
                    },
                })
                globalPrecioVenta.value = precioArticulo;
                globalPrecioArgen = precioArgentina;
                modalArticulos.style.display = "none";
                globalCantidad.value = 1;
                globalCantidad.focus();
                globalCantidad.select();
                globalBtnAgregar.disabled = false
                tableArticulos.clearFilter('Detalle', 'Articulo', 'Cantidad')
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
        }

    });

    function getArticulos(){
        tableArticulos.setData('/getArticulos');
    }

</script>

