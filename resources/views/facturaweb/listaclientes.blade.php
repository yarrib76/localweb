<div id="myModalClientes" class="modal">
    <!-- Modal Ingreso -->
    <div id="modal-content-clientes" class="modal-content">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <span id="close-clientes" class="close">&times;</span>

                    <div class="panel-heading"><i class="fa fa-cog">Clientes</i></div>
                        <div class="panel-body">
                            <div id="table-clientes"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #myModalClientes {
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
    #modal-content-clientes {
        background-color: rgba(243, 255, 242, 0.91);
        margin: auto;
        padding: 100px;
        border: 1px solid #888;
        width: 100%;
        height: 100%;
        overflow-y: auto;
    }

    /* The Close Button */
    #close-clientes {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    #close-clientes:hover,
    #close-clientes:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<script>
    var modalClientes = document.getElementById('myModalClientes');
    // Get the <span> element that closes the modal
    var spanClientes = document.getElementById("close-clientes");
    var precioAreticulo;
    function cargoModalClientes(){
        getClientes()
        // When the user clicks the button, open the modal
        modalClientes.style.display = "block";

        // When the user clicks on <span> (x), close the modal
        spanClientes.onclick = function() {
            modalClientes.style.display = "none";
        }
    }
    $("#table-clientes").tabulator({
        height: "550px",
        // initialSort:[
        //     {column:"NroFactura", dir:"asc"}, //sort by this first
        //   ],
        columns: [
            {title: "Nombre", field: "nombre", sortable: true, width: 150,headerFilter:"input"},
            {title: "Apellido", field: "apellido", sortable: true, width: 110,headerFilter:"input"},
            {title: "Mail", field: "mail", sortable: true, width: 350,headerFilter:"input"},
            {title: "Accion",width:100, align:"center", cellClick:function(e, cell){
                globalCliente.value  = cell.getRow().getData()['nombre'] + "," + cell.getRow().getData()['apellido'] ;
                // getPrecioArticulo(cell.getRow().getData()['Articulo']);
                modalClientes.style.display = "none";
            },
                formatter: function (cell) {
                    return "<button class='btn-info'>Agregar</button>"; // Ícono de cruz (times)
                }
            }
        ],
    });

    function getClientes(){
        $("#table-clientes").tabulator("setData", '/getClientes');
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

