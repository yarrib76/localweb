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
                                                <tr>
                                                    <td>
                                                        <img id="fotoArticulo" src="../../imagenes/sinfoto.png" width="100" height="100">
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-primary" onclick="cargoModalArticulos()"><i class="fas fa-search"></i></button>
                                            <button onclick="busquedaManualArt()">Buscar</button>
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
                                                <select id="select_descuento" class="form-control">
                                                <option value="0">0</option>
                                                <option value="0.9">10</option>
                                                <option value="0.85">15</option>
                                                <option value="0.8">20</option>
                                                <option value="0.75">25</option>
                                                <option value="0.7">30</option>
                                                <option value="0.65">35</option>
                                                <option value="0.6">40</option>
                                                <option value="0.5">50</option>
                                                <option value="0">100</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button onclick="btnAgregar()" class="btn-info" id="btnAgregar" tabindex="2">Agregar</button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <di>
                                        <h4>Factura</h4>
                                        <input type="number" id="nroFactura" disabled="true" style="width: 120px">
                                    </di>

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
                                    <input type="number" id="totalDescuento" min="0" disabled="true" style="width: 120px">
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
                                        <input type="number" id="correo" placeholder="Correo" style="width: 80px">
                                        <input type="number" id="total_correo" placeholder="Total" style="width: 80px">
                                        <!--PEDIDOS-->
                                        <label style="font-size: 15px"> <input type="checkbox" id="chkBoxPedido">Pedido</label>
                                        <h4>NroPedido</h4>
                                        <input type="number" id="NroPedido" style="width: 70px">
                                        <button id="btnBuscarPedido" onclick="cargoModalPedidos()" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        <label style="font-size: 15px"> <input type="checkbox" id="chkBoxListoEnvio" disabled="true">Listo Para Envio</label>
                                        <!--PEDIDO-->
                                        <button class="btn btn-secondary" onclick="facturar()">Facturar</button>
                                        <label style="font-size: 15px"> <input type="checkbox" name="chkBoxOrdenarPorPrecio">Ordenar Precio</label>
                                        <button id="imprimir" onclick="imprimir()" class="btn btn-primary"><i class="fas fa-print"></i></button>

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
    var listDescuento = document.getElementById('select_descuento')
    var textDescuento = document.getElementById('totalDescuento');
    var glocalVendedora = document.getElementById('vendedora');
    document.getElementById('totalApagar').value = 0.00
    var globalClientId = 1;
    var globalTotal = 0.00;
    var globalDescuento;
    var globalPrecioArgen;
    var datosFactura = [];
    var inputCorreo = document.getElementById('correo');
    var inputTotal_correo = document.getElementById('total_correo');
    var chkBoxListoEnvio = document.getElementById('chkBoxListoEnvio');
    var globalFotoArticulo = document.getElementById('fotoArticulo');

    /*PEDIDOS*/
    var chkBoxPedido = document.getElementById('chkBoxPedido');
    var chkBoxListoEnvio = document.getElementById('chkBoxListoEnvio');
    var inputNroPedido = document.getElementById('NroPedido');
    var btnBuscarPedido = document.getElementById('btnBuscarPedido');
    /*PEDIDOS*/

    //Ejecuta cuando carga la pagina
    $(document).ready ( function(){
        recargaPagina()
        limpiezaVentanas()
        cargoComboVendedoras()
        cargoComboTipoPagos()
        limpiezaPedidos()
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
        globalNroArticulo.focus()
        getNroFactura();
    }

    function btnAgregar(){
        estado = 0
        if (parseFloat(globalStock.value) >= parseFloat(globalCantidad.value) && parseFloat(globalCantidad.value) != 0 ){
            for (var i = 0; i < datosFactura.length; i++) {
                if (datosFactura[i]['Articulo'] === globalNroArticulo.value) {
                    if ((parseFloat(datosFactura[i]['Cantidad']) + parseFloat(globalCantidad.value)) <= parseFloat(globalStock.value)){
                        datosFactura[i]['Cantidad'] = parseFloat(datosFactura[i]['Cantidad']) + parseFloat(globalCantidad.value)
                        datosFactura[i]['PrecioVenta'] = (parseFloat(datosFactura[i]['Cantidad']) * parseFloat(globalPrecioVenta.value)).toFixed(2)
                        datosFactura[i]['Ganancia'] = datosFactura[i]['Ganancia'] + (globalPrecioArgen * parseFloat(globalCantidad.value));
                        globalTotal += parseFloat(globalPrecioVenta.value).toFixed(2) * parseFloat(globalCantidad.value).toFixed(2);
                        document.getElementById('totalApagar').value = parseFloat(globalTotal).toFixed(2);
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
                    Cantid: globalCantidad.value,
                    PrecioUnitario: globalPrecioVenta.value,
                    PrecioVenta: (parseFloat(globalPrecioVenta.value).toFixed(2) * parseFloat(globalCantidad.value)).toFixed(2),
                    Vendedora: glocalVendedora.value,
                    PrecioArgen: globalPrecioArgen,
                    Ganancia: (globalPrecioArgen * parseFloat(globalCantidad.value)),
                    Cajera: '{{$nameCajera}}',
                };
                datosFactura.push(nuevoAticulo);
                globalTotal += parseFloat(globalPrecioVenta.value).toFixed(2) * parseFloat(globalCantidad.value).toFixed(2);
                document.getElementById('totalApagar').value = parseFloat(globalTotal).toFixed(2);
                limpiezaVentanas();
            }
            refreshTabulator();
            limpiezaCorreo();
            globalNroArticulo.focus()
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
        limpiezaCorreo();
        limpiezaDescuentos();
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

    var tableFactura = new Tabulator("#table-arti-factura", {
        height: "550px",
        // initialSort:[
        //     {column:"NroFactura", dir:"asc"}, //sort by this first
        //   ],
        columns: [
            {title: "Articulo", field: "Articulo", width: 150,headerFilter:"input"},
            {title: "Detalle", field: "Detalle", width: 350,headerFilter:"input"},
            {title: "Cantidad", field: "Cantidad", width: 80},
            {title: "PrecioUnitario", field: "PrecioUnitario", width:140},
            {title: "PrecioVenta", field: "PrecioVenta", width:140},
            {title: "Accion",width:100, cellClick:function(e, cell){
                eliminarAritculoFactura(cell.getRow().getData()['Articulo'])
            },
                formatter: function (cell) {
                    return "<button class='btn-info'>Eliminar</button>"; // Ícono de cruz (times)
                }
            }
        ],
    });

    function refreshTabulator(){
        tableFactura.setData(datosFactura);
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
                    $('#vendedora').append("<option value='" + value['Nombre'] + "'>" + value['Nombre'] + '</option>');
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
        textDescuento.value = 0.00
        globalBtnAgregar.disabled = true
        checkboxDescuento.checked = false
        listDescuento.disabled = true
        $("#select_descuento").val(0);
    }

    function limpiezaTotal(){
        globalCantidad.value = ""
        globalDetalle.value = ""
        globalNroArticulo.value = ""
        globalPrecioVenta.value = ""
        globalStock.value = ""
        textDescuento.value = 0.00
        globalBtnAgregar.disabled = true
        checkboxDescuento.checked = false
        listDescuento.disabled = true
        globalClientId = 1 //Se asigna valor de 1 ya que pertenece al cliente Ninguno,Ninguno
        globalCliente.value = ""
        globalTotal = 0;
        $("#select_descuento").val(0);
        chkBoxListoEnvio.disabled = true
        document.getElementById('totalApagar').value = 0
        datosFactura = [];
    }

    function limpiezaCorreo(){
        inputCorreo.value = ""
        inputTotal_correo.value = ""
    }

    function limpiezaDescuentos(){
        textDescuento.value = 0.00
        $("#select_descuento").val(0);
        checkboxDescuento.checked = false
        listDescuento.disabled = true
    }

    function limpiezaPedidos(){
        inputNroPedido.value = "";
        inputNroPedido.disabled = true;
        btnBuscarPedido.disabled = true;
        chkBoxListoEnvio.checked = false;
    }

    /*LISTENER'S*/
    // Agregar un listener para el evento change
    checkboxDescuento.addEventListener("change", function(event) {
        limpiezaCorreo();
        if (event.target.checked) {
            listDescuento.disabled = false
        } else {
            listDescuento.disabled = true
            $("#select_descuento").val(0);
            textDescuento.value = 0.00
        }
    });

    // Agregar un listener para el evento change
    listDescuento.addEventListener('change', function(event){
        resultado = (parseFloat(globalTotal) * listDescuento.value)
        textDescuento.value = parseFloat(resultado).toFixed(2)
    })

    inputCorreo.addEventListener('keyup', function(event){
        if (checkboxDescuento.checked) {
            totalConDescuento = parseFloat(textDescuento.value) + parseFloat(inputCorreo.value)
            inputTotal_correo.value = parseFloat(totalConDescuento).toFixed(2)
        } else {
            totalSinDescuento = parseFloat(globalTotal) + parseFloat(inputCorreo.value)
            inputTotal_correo.value = parseFloat(totalSinDescuento).toFixed(2)
        }
    })
    globalCantidad.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            // Llama a la función dek boton Agrrgar
            btnAgregar();
        }
    });

    globalNroArticulo.addEventListener('keypress', function (evt) {
        if ( this.value.length > 1 && this.value.length == 13){
            busquedaManualArt();
        }
    });

    chkBoxPedido.addEventListener("change", function(event) {
        if (event.target.checked) {
            btnBuscarPedido.disabled = false;
            chkBoxListoEnvio.disabled = false;
            limpiezaTotal()
            limpiezaCorreo()
            refreshTabulator()
            chkBoxListoEnvio.disabled = false;
        } else {
            limpiezaPedidos()
            limpiezaTotal()
            limpiezaCorreo()
            refreshTabulator()
        }
    });
    /*LISTENER'S*/

    function facturar(){
        if (document.getElementById('totalApagar').value != 0){
            if (confirm('Confirma la Factura?')){
                var listaArticulos =  JSON.stringify(datosFactura)
                var tipo_pago_id  = document.getElementById('tipo_pago').value
                var esPedido;
                var listoParaEnvio;
                var nroPedido;
                if (chkBoxPedido.checked){
                    esPedido = "SI"
                    nroPedido = inputNroPedido.value
                    if (chkBoxListoEnvio.checked){
                        listoParaEnvio = 1
                    } else listoParaEnvio = 0
                }else esPedido = "NO"

                var datosCombinados = {
                    articulos: listaArticulos,
                    cliente_id: globalClientId,
                    tipo_pago_id: tipo_pago_id,
                    nroFactura: document.getElementById('nroFactura').value,
                    total: document.getElementById('totalApagar').value,
                    descuento: document.getElementById('totalDescuento').value,
                    porcentajeDescuento: document.getElementById('select_descuento').options[document.getElementById('select_descuento').selectedIndex].text,
                    envio: document.getElementById('correo').value,
                    totalEnvio: document.getElementById('total_correo').value,
                    vendedora: glocalVendedora.value,
                    esPedido: esPedido,
                    listoParaEnvio: listoParaEnvio,
                    nroPedido: nroPedido,
                };
                $.ajax({
                    url: "crearfactura",
                    method: 'post',
                    data: datosCombinados,
                    success: function (json) {
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores de la solicitud aquí
                        alert('Ha ocurrido un error consultar con el administrador')
                    }
                });
                alert('La venta se realizo con correctamente')
                location.reload();
            }
        }else (alert('No se puede facturar con valor Total en 0'))
    }

    function getNroFactura(){
        $.ajax({
            url: 'getNroFactura',
            method: 'get',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (json){
                document.getElementById('nroFactura').value = json[0]['NroFactura']
            },
            error: function(xhr, status, error){

            }
        })
    }

    function busquedaManualArt(){
        $.ajax({
            url: '/getArticulos?nroArticulo=' + globalNroArticulo.value + '&&botonManual=true',
            method: 'get',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (json){
                globalNroArticulo.value = json[0]['Articulo']
                globalDetalle.value = json[0]['Detalle']
                globalStock.value = json[0]['Cantidad']
            },
            error: function(xhr, status, error){
            }
        })
        $.ajax({
            url: "/getPrecio?nroArticulo=" + globalNroArticulo.value,
            dataType: "json",
            async: false,
            success: function(json){
                globalPrecioVenta.value = (json[0]['PrecioVenta']);
                globalPrecioArgen = (json[0]['PrecioArgen']);
            },
        })
        $.ajax({
            url: 'api/fotoarticulo?nroArticulo=' + globalNroArticulo.value,
            dataType : "json",
            success : function(json) {
                if (json.length != 0) {
                    globalFotoArticulo.src = json[0]['imagessrc']
                } else {
                    globalFotoArticulo.src = "../../imagenes/sinfoto.png"
                }
            },
        })
        globalCantidad.value = 1;
        globalCantidad.focus();
        globalBtnAgregar.disabled = false
    }

    function imprimir(){
        window.jsPDF = window.jspdf.jsPDF;

        var fechaHoraActual = new Date();
        // Obtener la fecha actual
        var fechaActual = fechaHoraActual.toLocaleDateString();
        // Obtener la hora actual
        var horaActual = fechaHoraActual.toLocaleTimeString();


        var columns = Object.keys(datosFactura[0]);

        // Definir los nombres de las columnas personalizados
        var customHeaders = ['Cant', 'Detalle', 'Uni.', 'Total'];

        // Definir el orden y los campos que deseas mostrar
        var fieldsToShow = ['Cantidad', 'Detalle', 'PrecioUnitario', 'PrecioVenta'];

        var bodyData = datosFactura.map(function(row) {
            var rowData = [];
            fieldsToShow.forEach(function(field) {
                rowData.push(row[field]);
            });
            return rowData;
        });

        var doc = new jsPDF();


        // Título en la parte superior
        var topTitle = "Fecha: " + fechaActual + " " + horaActual + " Orden#: " + document.getElementById('nroFactura').value
        var topTitleY = 15;
        doc.setFontSize(10);
        doc.text(topTitle, 105, topTitleY, { align: "right" });

        /* Título en la parte inferior
        var bottomTitle = "Título en la parte inferior";
        var bottomTitleY = doc.internal.pageSize.height - 10;
        doc.text(bottomTitle, 105, bottomTitleY, { align: "center" });
        */

        doc.autoTable({
            head: [customHeaders],
            body: bodyData,
            startY: topTitleY + 10, // Comenzar después del título superior
            margin: { top: 25, bottom: 20 },
            styles: {
                fontSize: 8, // Tamaño de letra
                cellPadding: 2 // Espaciado interno de las celdas
            },
            columnStyles: {
                0: { // Estilo de la primera columna
                    columnWidth: 7 // Ancho de la primera columna
                },
                1: { // Estilo de la segunda columna
                    columnWidth: 40 // Ancho de la segunda columna
                },
                2: { // Estilo de la tercera columna
                    columnWidth: 10 // Ancho de la tercera columna
                },
                3: { // Estilo de la cuarta columna
                    columnWidth: 15 // Ancho de la cuarta columna
                }// Ajuste de márgenes
            }
        });

        // Obtener la altura del contenido de la tabla
        var finalY = doc.autoTable.previous.finalY;

        // Título en la parte inferior al final de la tabla
        if (textDescuento.value != 0){
            var bottomTitle = "Total: " + globalTotal + " " +
                    document.getElementById('select_descuento').options[document.getElementById('select_descuento').selectedIndex].text +
                    "%" + " de Descuento = " + document.getElementById('totalDescuento').value;
        } else {
            var bottomTitle = "Total: " + globalTotal
        }
        doc.setFontSize(10);
        var bottomTitleY = finalY + 10;
        doc.text(bottomTitle, 15, bottomTitleY, { align: "left", baseline: "bottom", fontSize: 7 });


        // Agregar otro texto debajo del título en la parte inferior
        if(inputCorreo.value != ""){
            // Define el tamaño de la fuente antes de agregar el texto
            doc.setFontSize(12);
            var additionalText = "Envio: " + inputCorreo.value + " Total Con Envio: " + inputTotal_correo.value;
            var additionalTextY = bottomTitleY + 10; // Posición Y para el texto adicional
            doc.text(additionalText, 15, additionalTextY, { align: "left", baseline: "bottom"});
        }

        doc.save('ticket-' + document.getElementById('nroFactura').value + '.pdf');
    }
</script>

