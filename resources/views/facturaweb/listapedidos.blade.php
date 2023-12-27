<div id="myModalPedidos" class="modal">
    <!-- Modal Ingreso -->
    <div id="modal-content-pedidos" class="modal-content">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <span id="close-pedidos" class="close">&times;</span>

                    <div class="panel-heading"><i class="fa fa-cog">Clientes</i></div>
                        <div class="panel-body">
                            <div id="table-pedidos"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #myModalPedidos {
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
    #modal-content-pedidos {
        background-color: rgba(243, 255, 242, 0.91);
        margin: auto;
        padding: 100px;
        border: 1px solid #888;
        width: 100%;
        height: 100%;
        overflow-y: auto;
    }

    /* The Close Button */
    #close-pedidos {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    #close-pedidos:hover,
    #close-pedidos:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<script>
    var modalPedidos = document.getElementById('myModalPedidos');
    // Get the <span> element that closes the modal
    var spanPedidos = document.getElementById("close-pedidos");
    var precioAreticulo;
    function cargoModalPedidos(){
        getPedidos()
        // When the user clicks the button, open the modal
        modalPedidos.style.display = "block";

        // When the user clicks on <span> (x), close the modal
        spanPedidos.onclick = function() {
            modalPedidos.style.display = "none";
        }
    }
    var tablePedidos = new Tabulator("#table-pedidos", {
        height: "550px",
        // initialSort:[
        //     {column:"NroFactura", dir:"asc"}, //sort by this first
        //   ],
        columns: [
            {title: "nropedido", field:"nropedido",width:100, headerFilter:"input"},
            {title: "Cliente", field: "Cliente", width: 150,headerFilter:"input"},
            {title: "Vendedora", field: "vendedora", width: 110,headerFilter:"input"},
            {title: "Fecha", field: "fecha", width: 150, headerFilter:"input"},
            {title: "OrdenWeb", field: "ordenWeb", width: 100,headerFilter:"input"},
            {title: "Accion",width:100, cellClick:function(e, cell){
                globalCliente.value  = cell.getRow().getData()['Cliente'];
                globalClientId = cell.getRow().getData()['id_cliente'];
                globalTotal = cell.getRow().getData()['total'];
                $("#vendedora").val(cell.getRow().getData()['vendedora']);
                inputNroPedido.value = cell.getRow().getData()['nropedido'];
                document.getElementById('totalApagar').value = cell.getRow().getData()['total'];
                /*Verifico si existe la variable globalOrdenWeb ya que estoy utilizando listapedidos.blade.php tanto en la factura
                como en los pedidos, por si existe la variable es porque estoy llamando a listapedidos desde un pedido y no una factura
                 */
                if (typeof globalOrdenWeb !== 'undefined') {
                    globalOrdenWeb.value = cell.getRow().getData()['ordenWeb'];
                } else chkBoxListoEnvio.checked = false;

                /*Hago lo mismo que con globalOrdenWeb, necesito saber si tiene que poner un numero de Pedido o Factura*/
                if (typeof globalNroPedido !== 'undefined') {
                    globalNroPedido.value = cell.getRow().getData()['nropedido'];
                }
                // getPrecioArticulo(cell.getRow().getData()['Articulo']);
                $.ajax({
                    url: "getPedidosArticulos?nroPedido=" + cell.getRow().getData()['nropedido'],
                    dataType: "json",
                    async: false,
                    success: function(json){
                        limpiezaDatosTabulator()
                        Array.prototype.push.apply(datosFactura, json);
                        refreshTabulator();
                    },
                })
                modalPedidos.style.display = "none";
            },
                formatter: function (cell) {
                    return "<button class='btn-info'>Agregar</button>"; // Ícono de cruz (times)
                }
            }
        ],
    });

    function getPedidos(){
        tablePedidos.setData('/getPedidos');
    }

</script>

