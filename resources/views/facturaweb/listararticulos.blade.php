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
    var precioAreticulo;
    function cargoModalArticulos(){
        getArticulos()
        // When the user clicks the button, open the modal
        modalArticulos.style.display = "block";

        // When the user clicks on <span> (x), close the modal
        spanArticulos.onclick = function() {
            modalArticulos.style.display = "none";
        }
    }
    $("#table-articulos").tabulator({
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
                getPrecioArticulo(cell.getRow().getData()['Articulo']);
                globalPrecioVenta.value = precioAreticulo;
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

    function getArticulos(){
        $("#table-articulos").tabulator("setData", '/getArticulos');
    }

    function getPrecioArticulo(nroArticulo){
        $.ajax({
            url: "/getPrecio?nroArticulo=" + nroArticulo
            + "&tipo=SinEncuesta",
            dataType: "json",
            async: false,
            success: function(json){
                precioAreticulo = (json[0]['PrecioVenta']);
            }
        })
    }

</script>

