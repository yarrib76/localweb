<div id="myModalFactura" class="modal">
    <!-- Modal Ingreso -->
    <div id="modal-content-factura" class="modal-content">
        <span id="close-factura" class="close">&times;</span>
        <div class="row">
            <div class="col-sm-30">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-calculator"> Factura</i></div>
                        <table class="table table-striped table-bordered records_list">
                            <tr>
                                <td style="width: 100px;">
                                    <section >
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" placeholder="Articulo" id="NroArticulo" style="width: 150px">
                                            <input type="text" class="form-control" placeholder="Detalle" id="Detalle" style="width: 350px" disabled="true">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control" placeholder="Cliente" id="Cliente" style="width: 350px" disabled="true">
                                                    </td>
                                                    <td>
                                                        <div class="col-md-4">
                                                            <button class="btn btn-primary" onclick="cargoModalClientes()"><i class="fas fa-search"></i></button>
                                                            <button class="btn btn-danger" onclick="eliminarCliente()"><i class="fas fa-times-circle"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-primary" onclick="cargoModalArticulos()"><i class="fas fa-search"></i></button>
                                            <button>Buscar</button>
                                        </div>
                                    </section>
                                </td>
                                <td style="width: 300px;">
                                    <div>
                                        <div class="col-md-4">
                                            <h5>Precio de Venta</h5>
                                            <h5>Stock</h5>
                                            <h5>Cantidad</h5>
                                            <label style="font-size: 15px"> <input type="checkbox" id="chkBoxDescuento">Descuento</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control"  id="PrecioVenta" disabled="true" style="width: 90px">
                                            <input type="number" class="form-control"  id="Stock" disabled="true">
                                            <input type="number" class="form-control"  id="Cantidad" tabindex="1" min="0">
                                                <select id="descuento" class="form-control">
                                                <option value="0">0</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                                <option value="40">40</option>
                                                <option value="50">50</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button onclick="btnAgregar()" class="btn-info" id="btnAgregar" tabindex="2">Agregar</button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <h4>Vendedora</h4>
                                        <select id="vendedora" class="form-control"></select>
                                    </div>
                                    <div>
                                        <h4>Tipo Pago</h4>
                                        <select id="tipo_pago" class="form-control"></select>
                                    </div>
                                </td>
                                <td>
                                    <h4>Total</h4>
                                    <input type="number" id="totalApagar" min="0" disabled="true" style="width: 120px">
                                    <h4>Descuento</h4>
                                    <h4>0</h4>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-striped table-bordered records_list">
                            <tr>
                                <td style="width: 1020px;">
                                   <div id="table-arti-factura"></div>
                                    <div>

                                    </div>

                                </td>
                                <td>
                                    <div>
                                        <input type="text" name="correo" placeholder="Correo" style="width: 80px">
                                        <input type="text" name="total" placeholder="Total" style="width: 80px">
                                        <label style="font-size: 15px"> <input type="checkbox" name="chkBoxPedido">Pedido</label>
                                        <h4>NroPedido</h4>
                                        <input type="number" name="NroPedido" style="width: 70px">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        <label style="font-size: 15px"> <input type="checkbox" name="chkBoxListoEnvio">Listo Para Envio</label>
                                        <button class="btn btn-secondary">Facturar</button>
                                        <label style="font-size: 15px"> <input type="checkbox" name="chkBoxOrdenarPorPrecio">Ordenar Precio</label>
                                        <button class="btn btn-primary"><i class="fas fa-print"></i></button>

                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal {
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
    .modal-content {
        background-color: rgba(243, 255, 242, 0.91);
        margin: auto;
        padding: 100px;
        border: 1px solid #888;
        width: 100%;
        height: 100%;
        overflow-y: auto;
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
    var globalNroArticulo = document.getElementById('NroArticulo');
    var globalDetalle = document.getElementById('Detalle');
    var globalPrecioVenta = document.getElementById('PrecioVenta');
    var globalStock = document.getElementById('Stock');
    var globalCantidad = document.getElementById('Cantidad');
    var globalBtnAgregar = document.getElementById('btnAgregar');
    var globalCliente = document.getElementById('Cliente');
    var checkboxDescuento = document.getElementById("chkBoxDescuento");
    var glocalVendedora = document.getElementById('vendedora');
    var globalClientId;
    var globalTotal = 0.00;
    var globalDescuento;
    var globalPrecioArgen;
    var datosFactura = [];

    //Ejecuta cuando carga la pagina
    $(document).ready ( function(){
        recargaPagina()
        limpiezaVentanas()
        cargoComboVendedoras()
        cargoComboTipoPagos()
    });
    function callFactura(){
        var modalFactura = document.getElementById('myModalFactura');

        // Get the <span> element that closes the modal
        var spanFactura = document.getElementById("close-factura");

        // When the user clicks the button, open the modal
        modalFactura.style.display = "block";

        // When the user clicks on <span> (x), close the modal
        spanFactura.onclick = function() {
            modalFactura.style.display = "none";
            location.reload();
        }
    }

    globalCantidad.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            // Llama a la función dek boton Agrrgar
            btnAgregar();
        }
    });

    function btnAgregar(){
        estado = 0
        if (parseFloat(globalStock.value) >= parseFloat(globalCantidad.value) && parseFloat(globalCantidad.value) != 0 ){
            for (var i = 0; i < datosFactura.length; i++) {
                if (datosFactura[i]['Articulo'] === globalNroArticulo.value) {
                    if ((parseFloat(datosFactura[i]['Cantidad']) + parseFloat(globalCantidad.value)) <= parseFloat(globalStock.value)){
                        datosFactura[i]['Cantidad'] = parseFloat(datosFactura[i]['Cantidad']) + parseFloat(globalCantidad.value)
                        datosFactura[i]['PrecioVenta'] = (parseFloat(datosFactura[i]['Cantidad']) * parseFloat(globalPrecioVenta.value)).toFixed(2)
                        datosFactura[i]['Ganancia'] = datosFactura[i]['Ganancia'] + (globalPrecioArgen * parseFloat(globalCantidad.value));
                        globalTotal += parseFloat(globalPrecioVenta.value) * parseFloat(globalCantidad.value).toFixed(2);
                        document.getElementById('totalApagar').value = globalTotal;
                        limpiezaVentanas();
                    } else {alert('Stock Insuficientessss!!!!')}

                    estado = 1
                    break; // Sale del bucle una vez que se elimina el elemento
                }
            }
            if (estado === 0){
                // Crear un objeto con los datos del formulario
                var nuevoAticulo = {
                    Articulo: globalNroArticulo.value,
                    Detalle: globalDetalle.value,
                    Cantidad: globalCantidad.value,
                    PrecioUnitario: globalPrecioVenta.value,
                    PrecioVenta: (parseFloat(globalPrecioVenta.value).toFixed(2) * parseFloat(globalCantidad.value)).toFixed(2),
                    Vendedora: glocalVendedora.options[glocalVendedora.selectedIndex].text,
                    PrecioArgen: globalPrecioArgen,
                    Ganancia: (globalPrecioArgen * parseFloat(globalCantidad.value))
                };
                datosFactura.push(nuevoAticulo);
                globalTotal += parseFloat(globalPrecioVenta.value).toFixed(2) * parseFloat(globalCantidad.value).toFixed(2);
                document.getElementById('totalApagar').value = parseFloat(globalTotal).toFixed(2);
                limpiezaVentanas();
            }
            refreshTabulator();
        } else {alert('Stock Insuficiente!!!!')}

    }
    //Recarga la pagina si fueron con el boton del navegador para atras y luego para adelante
    function recargaPagina(){
        let currentPage = window.location.href;

        $(window).on('popstate', function(event) {
            currentPage = window.location.href;
        });

        $(window).on('pageshow', function(event) {
            if (event.originalEvent.persisted || (window.performance && window.performance.navigation.type === 2)) {
                location.reload(true); // Recargar la página
            }
        });
    }

    function eliminarAritculoFactura(nroArticulo){
        for (var i = 0; i < datosFactura.length; i++) {
            if (datosFactura[i]['Articulo'] === nroArticulo) {
                globalTotal = parseFloat(globalTotal).toFixed(2) - parseFloat(datosFactura[i]['PrecioVenta']).toFixed(2);
                document.getElementById('totalApagar').value = parseFloat(globalTotal).toFixed(2);
                datosFactura.splice(i, 1); // Elimina 1 elemento en la posición 'i'
                break; // Sale del bucle una vez que se elimina el elemento
            }
        }
        refreshTabulator() //vvuelvo a llenar el array para que actualice lo que se elimino
    }

    function eliminarCliente(){
        globalClientId = 1
        globalCliente.value = ""
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

    $("#table-arti-factura").tabulator({
        height: "550px",
        // initialSort:[
        //     {column:"NroFactura", dir:"asc"}, //sort by this first
        //   ],
        columns: [
            {title: "Articulo", field: "Articulo", sortable: true, width: 150,headerFilter:"input"},
            {title: "Detalle", field: "Detalle", sortable: true, width: 350,headerFilter:"input"},
            {title: "Cantidad", field: "Cantidad", sortable: true, width: 80},
            {title: "PrecioUnitario", field: "PrecioUnitario", sortable: true, width:140},
            {title: "PrecioVenta", field: "PrecioVenta", sortable: true, width:140},
            {title: "Accion",width:100, align:"center", cellClick:function(e, cell){
                eliminarAritculoFactura(cell.getRow().getData()['Articulo'])
            },
                formatter: function (cell) {
                    return "<button class='btn-info'>Eliminar</button>"; // Ícono de cruz (times)
                }
            }
        ],
    });

    function refreshTabulator(){
        $("#table-arti-factura").tabulator("setData", datosFactura);
    }

    function cargoComboVendedoras(){
        $.ajax({
            type: 'get',
            url: '/listaVendedoras',
            //    data: {radio_id:category_id , flota_id:flota_id},
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (datos, textStatus, jqXHR) {
                $.each(datos, function (i, value) {
                    $('#vendedora').append("<option value='" + value['Id'] + "'>" + value['Nombre'] + '</option>');
                }); // each
            },
            error: function (datos) {
                console.log("Este callback maneja los errores " + datos);
            }

        }); // aja
    }

    function cargoComboTipoPagos(){
        $.ajax({
            type: 'get',
            url: '/listaTipoPagos',
            //    data: {radio_id:category_id , flota_id:flota_id},
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (datos, textStatus, jqXHR) {
                $.each(datos, function (i, value) {
                    $('#tipo_pago').append("<option value='" + value['id_tipo_pagos'] + "'>" + value['tipo_pago'] + '</option>');
                }); // each
                //Selecciono en el combo por default la opción 2 que es "Efectivo"
                $("#tipo_pago").val(2);
            },
        error: function (datos) {
            console.log("Este callback maneja los errores " + datos);
        }

        }); // aja

    }
    function limpiezaVentanas(){
        globalCantidad.value = ""
        globalDetalle.value = ""
        globalNroArticulo.value = ""
        globalPrecioVenta.value = ""
        globalStock.value = ""
        globalBtnAgregar.disabled = true
        checkboxDescuento.checked = false
        document.getElementById('descuento').disabled = true
        globalClientId = 1 //Se asigna valor de 1 ya que pertenece al cliente Ninguno,Ninguno
        globalCliente.value = ""
    }


    // Agregar un listener para el evento change
    checkboxDescuento.addEventListener("change", function(event) {
        if (event.target.checked) {
            document.getElementById('descuento').disabled = false
        } else {
            document.getElementById('descuento').disabled = true
            $("#descuento").val(0);
        }
    });
</script>
