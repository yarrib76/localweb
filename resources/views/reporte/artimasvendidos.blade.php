@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Articulos Mas Vendidos</i>
                    </div>
                    @if (substr(Request::url('http://donatella.dyndns.org'),0,27) == 'http://donatella.dyndns.org')
                        <button onclick="verificar('Local')" class="buttonDonatella">Donatella</button>
                        <button onclick="verificar('Samira')" class="buttonSamira">Samira</button>
                        <button onclick="verificar('Viamore')" class="buttonViamore">Viamore</button>
                    @elseif (substr(Request::url('http://samirasrl.dyndns.org'),0,27) == 'http://samirasrl.dyndns.org')
                        <button onclick="verificar('Donatella')" class="buttonDonatella">Donatella</button>
                        <button onclick="verificar('Local')" class="buttonSamira">Samira</button>
                        <button onclick="verificar('Viamore')" class="buttonViamore">Viamore</button>
                    @elseif (substr(Request::url('http://viamore.dyndns.org'),0,25) == 'http://viamore.dyndns.org')
                        <button onclick="verificar('Donatella')" class="buttonDonatella">Donatella</button>
                        <button onclick="verificar('Samira')" class="buttonSamira">Samira</button>
                        <button onclick="verificar('Local')" class="buttonViamore">Viamore</button>
                    @elseif (substr(Request::url('http://donalab2.dyndns.org'),0,26) == 'http://donalab2.dyndns.org')
                        <button onclick="verificar('Local')" class="buttonDonatella">Local</button>
                        <button onclick="verificar('Samira')" class="buttonSamira">Samira</button>
                        <button onclick="verificar('Viamore')" class="buttonViamore">Viamore</button>
                    @endif
                    <div class="col-sm-3">
                        Fecha Inicio
                        <input type="date" class="form-control" placeholder="Fecha" id="FechaInicio" required="required">
                    </div>
                    <div class="col-sm-3">
                        Fecha Fin
                        <input type="date" class="form-control" placeholder="Fecha" id="FechaFin" required="required">
                        <input type="checkbox" id="checkbox" onclick="checkboxProveedor()">Todos los Proveedores
                        <select id="proveedores" class="form-control" name="proveedor_name" ></select>
                        <input type="checkbox" id="checkboxWeb" onclick="checkboxWeb()">WEB TN

                    </div>
                    <h3 id="LocalName"></h3>
                    <div class="panel-body">
                        <table id="reporteViamore" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Articulo</th>
                                <th>Detalle</th>
                                <th>ProveedorSKU</th>
                                <th>TotalVendido</th>
                                <th>TotalStock</th>
                                <th>PrecioVenta</th>
                                <th>Imagen</th>
                                <th>IA</th>
                            </tr>
                            </thead>
                            <tbody>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #myModal {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 11%;
            height: 20%;
            overflow-y: auto;
        }
        #modalImage {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 43.3%;
            height: 80%;
            overflow-y: auto;
        }
        #myModalError {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 30%;
            height: 50%;
            overflow-y: auto;
        }
        #modalMachineLearning {
            background-color: #c5fee9;
            margin: auto;
            padding: 20px;
            width: 50%;
            height: 50%;
            overflow-y: auto;
        }
        #LocalName {
            margin-top: 10px;
            margin-bottom: 100px;
            margin-right: 150px;
            margin-left: 500px;
            color: #00bcd4;
        }

        .ml-wrapper {
            position: relative;          /* referencia para la lista flotante */
        }

        .ml-dropdown {
            border: 1px solid #ccc;
            padding: 8px;
            background: #fff;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px;
        }

        .ml-arrow {
            font-size: 14px;
        }

        .ml-list {
            position: absolute;          /* flotante */
            top: 100%;                   /* justo debajo del dropdown */
            left: 0;
            width: 100%;
            border: 1px solid #ccc;
            background: white;
            margin-top: 3px;
            display: none;
            max-height: 180px;
            overflow-y: auto;
            border-radius: 4px;
            z-index: 9999;               /* para que quede por encima de todo */
        }

        .ml-item {
            padding: 6px;
            cursor: pointer;
        }

        .ml-item:hover {
            background: #eee;
        }

        .ml-item.selected {
            background: #2d7df4;
            color: white;
        }

        /* Botón X de cierre */
        .closeML {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .closeML:hover,
        .closeML:focus {
            color: black;
        }

    </style>
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <img src="refresh/load.gif" height="100" width="100">
        </div>
    </div>
    <div id="modalImage" class="modal">
        <span class="close">&times;</span>
        <!-- Modal content -->
        <div class="modal-content">
            <img id="imagen">
        </div>
    </div>

    <div id="modalMachineLearning" class="modal">
        <span class="closeML">&times;</span>

        <div class="modal-content-ML">
            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 10px;">
                <h5 id="tituloSKU" style="margin: 0;"></h5>
                <h5 id="detalle" style="margin: 0; color: #555;"></h5>
            </div>

            <div style="display: flex; gap: 15px; align-items: flex-end;">

                <div>
                    <label for="selectAnio">Año:</label>
                    <select id="selectAnio" class="form-control">
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                    </select>
                </div>

                <div style="min-width: 200px;">
                    <label>Mes/es:</label>

                    <!-- Contenedor relativo -->
                    <div class="ml-wrapper">
                        <!-- Caja que se ve cerrada -->
                        <div id="mesDropdown" class="ml-dropdown">
                            <div class="ml-selected" id="mlSelectedText">Seleccionar meses</div>
                            <div class="ml-arrow">&#9662;</div>
                        </div>

                        <!-- Lista flotante -->
                        <div id="mesList" class="ml-list"></div>
                    </div>
                </div>

                <div>
                    <button id="btnProcesarML"
                            class="btn btn-info"
                            type="button"
                            onclick="procesarMachineLearning()">
                        <i class="bi bi-gear-fill"></i>
                    </button>
                </div>
                <div>
                    <h5>Stock</h5><input id="stock_actual" disabled="true"  style="width: 50px;">
                </div>
                <div>
                    <h5>Demanda</h5><input id="demanda_total_horizonte" disabled="true"  style="width: 50px;">
                </div>
                <div>
                    <h5>Comprar</h5><input id="compra_sugerida_total" disabled="true"  style="width: 50px;">
                </div>
            </div>
            <div class="panel-body">
                <table id="tablaMachineLerning" class="table table-striped table-bordered records_list">
                    <thead>
                    <tr>
                        <th>Mes</th>
                        <th>Prediccion_ventas</th>
                    </tr>
                    </thead>
                    <tbody>
                    <td>Sin Informacion</td>
                    <td>Sin Informacion</td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div id="myModalError" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="pedidos" class="table table table-scroll table-striped">
                    <thead>
                    <tr>
                        <td><img src="refresh/error.png" height="100" width="100"></td>
                        <td><h1>Error en Proceso</h1></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="button" value="Cerrar" class="btn btn-success" onclick="cerrarError()"></td>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
@stop
@section('extra-javascript')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<!-- DataTables -->
<script type="text/javascript">
    var modalError = document.getElementById('myModalError');
    var proveedor;
    var web = 'NO';
    let skuML = null;
    let totalStockML = null;
    $(document).ready ( function(){
        $.ajax({
            type: 'get',
            url: '/api/proveedoresSelect',
            //    data: {radio_id:category_id , flota_id:flota_id},
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (datos, textStatus, jqXHR) {
                $.each(datos, function (i, value) {
                    $('#proveedores').append("<option value='" + value['Nombre'] + "'>" + value['Nombre'] + '</option>');
                }); // each
                //Ordeno Select
                var opt = $("#proveedores option").sort(function (a,b) { return a.value.toUpperCase().localeCompare(b.value.toUpperCase()) });
                $("#proveedores").append(opt);
            },
            error: function (datos) {
                console.log("Este callback maneja los errores " + datos);
            }
            }); // ajax
    });
    //Asigno DataTable para que exista vacìa
    table1 =  $('#reporteViamore').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],

            }
    );


    function verificar(local) {

        // Get the modal
        var modal = document.getElementById('myModal');
        // When the user clicks the button, open the modal
        var fechaInicio = document.getElementById("FechaInicio").value;
        var fechaFin = document.getElementById("FechaFin").value;
        if (document.getElementById("proveedores").disabled == false){
            proveedor = document.getElementById("proveedores").value;
        }
        var localName = document.getElementById("LocalName");
        localName.innerHTML = local
        modal.style.display = "block";
        var table = $("#reporteViamore");
        table.children().remove()
        table.append("<thead><tr><th>Articulo</th><th>Detalle</th><th>TotalVendido</th><th>TotalStock</th><th>PrecioVenta</th><th>Imagen</th></tr></thead>")
        table.append("<tbody>")
        $.ajax({
            url: '/api/artimasvendidos?' + 'local=' + local + '&anioDesde=' + fechaInicio
            + '&anioHasta=' + fechaFin + '&proveedor=' + proveedor + '&esWeb=' + web,
            dataType: "json",
            success: function (json) {
                if (json[0] != "") {
                    $.each(json, function (index, json) {
                        if (json['TotalStock'] < 10 ){
                            colorCelda = '#FF0000'
                        }else colorCelda = 'FFFFFF'
                        table.append(
                                "<tr><td>" + json['Articulo'] + "</td><td>"
                                + json['Detalle'] + "</td><td>"
                                + json['ProveedorSKU'] + "</td><td>"
                                + json['TotalVendido'] + "</td><td bgcolor='" + colorCelda + "'>"
                                + json['TotalStock'] + "</td><td>"
                                + json['PrecioVenta'] + "</td><td>"
                                + '<img src="' + json['imagessrc'] + '" height="52" width="52" onclick="verImagen(\'' + json['imagessrc'] + '\')">'
                                + "</td><td>"
                                + '<button class="btn btn-info" onclick="machineLearning(\''
                                + json['Articulo'] + '\', \'' + json['Detalle'] + '\', \'' + json['TotalStock'] + '\')">'
                                + '<i class="bi bi-gear-fill"></i>'
                                + '</button>'
                                + "</td></tr>"
                        );

                    });
                    table.append("</tr>")
                    table.append("</tbody>")
                    dataTable()
                    //close the modal
                    modal.style.display = "none";
                } else {
                    table.append("<tr><td>" + "Sin Informacion" + "</td><td>" + "Sin Informacion" + "</td><td>" + "Sin Informacion" + "</td>" + "Sin Informacion" + "</td>" + "</td></tr>");
                }
            },
            error: function () {
                //close the modal
                modal.style.display = "none";
                // When the finish process, open the modalError
                modalError.style.display = "block";
            }
        })
    }
    function dataTable(){
        //Si exsiste la table1 la elimino para volver a crear con la nueva informacion
        table1.destroy()
        table1 =  $('#reporteViamore').DataTable({
                    dom: 'Bfrtip',
                    columnDefs: [
                        { width: '50%', targets: 1 }
                    ],
                    fixedColumns: true,
                    buttons: [
                        'excel'
                    ]
                }
        );

    }
    function cerrarError(){
        //close the modal
        modalError.style.display = "none";
    }
    function checkboxProveedor(){
        // Get the checkbox
        var checkBox = document.getElementById("checkbox");
        // If the checkbox is checked, display the output text
        if (checkBox.checked == true){
            document.getElementById("proveedores").disabled = true;
            proveedor = 'SinFiltro';
        } else {
            document.getElementById("proveedores").disabled = false;
        }
    }
    function checkboxWeb(){
        // Get the checkbox
        var checkBox = document.getElementById("checkboxWeb");
        // If the checkbox is checked, display the output text
        if (checkBox.checked == true){
            web = 'SI';
        } else {
            web = 'NO'
        }
    }
    function verImagen(imagenName) {
        var image = document.getElementById("imagen");
        var modalImage = document.getElementById('modalImage');
        // Get the <span> element that closes the modal
        var spanImage = document.getElementsByClassName("close")[0];
        // When the user clicks the button, open the modal
        modalImage.style.display = "block";
        // When the user clicks on <span> (x), close the modal
        spanImage.onclick = function () {
            modalImage.style.display = "none";
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modalImage) {
                modalImage.style.display = "none";
            }
        }
        image.src = imagenName;
        image.style.width = '494px';
        image.style.height = '450px';
    }

    // === ABRIR MODAL Y CARGAR DATOS ===
    function machineLearning(sku, detalle, totalStock) {
        // Primero limpiar de todo
        limpiarModalMachineLearning();

        totalStockML = totalStock;
        skuML = sku;  // guardamos el SKU para usarlo al procesar
        // Setear texto del título
        document.getElementById("tituloSKU").textContent = "SKU A PREDECIR: " + sku;
        document.getElementById("detalle").textContent   = detalle;

        // Mostrar modal
        var modalMachineLearning = document.getElementById('modalMachineLearning');
        modalMachineLearning.style.display = "block";
    }


    // === CERRAR MODAL (X y click fuera) ===
    document.addEventListener("DOMContentLoaded", function () {

        var modalMachineLearning = document.getElementById('modalMachineLearning');
        var spanClose = document.querySelector("#modalMachineLearning .closeML");

        // Cerrar al hacer clic en la X
        if (spanClose) {
            spanClose.addEventListener("click", function () {
                modalMachineLearning.style.display = "none";
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const meses = [
            "Enero", "Febrero", "Marzo", "Abril",
            "Mayo", "Junio", "Julio", "Agosto",
            "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];

        const mesList = document.getElementById("mesList");
        const dropdown = document.getElementById("mesDropdown");
        const selectedText = document.getElementById("mlSelectedText");

        // Crear meses
        meses.forEach(function(mes, i) {
            const div = document.createElement("div");
            div.classList.add("ml-item");
            div.dataset.value = i + 1;
            div.textContent = mes;

            // Selección/des-selección
            div.addEventListener("click", function (e) {
                e.stopPropagation(); // evitar cerrar al hacer clic

                this.classList.toggle("selected");
                actualizarTextoResumen();
            });

            mesList.appendChild(div);
        });

        // Abrir/cerrar dropdown
        dropdown.addEventListener("click", function () {
            mesList.style.display =
                    mesList.style.display === "block" ? "none" : "block";
        });

        // Cerrar al hacer clic afuera
        document.addEventListener("click", function (e) {
            if (!dropdown.contains(e.target) && !mesList.contains(e.target)) {
                mesList.style.display = "none";
            }
        });

        function actualizarTextoResumen() {
            const seleccionados = Array.from(
                    document.querySelectorAll(".ml-item.selected")
            ).map(function (el) {
                        return el.textContent;
                    });  // <-- acá va el ;

            if (seleccionados.length === 0) {
                selectedText.textContent = "Seleccionar meses";
            } else {
                selectedText.textContent = seleccionados.join(", ");
            }
        }

    });

    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("selectAnio");
        const currentYear = new Date().getFullYear();

        // Si el año actual existe en la lista, lo selecciona
        if ([2025, 2026, 2027, 2028, 2029, 2030].includes(currentYear)) {
            select.value = currentYear;
        } else {
            // Si no, por defecto selecciona 2025
            select.value = "2025";
        }
    });

    function procesarMachineLearning() {
        if (!skuML) {
            alert("No se encontró el SKU para predecir.");
            return;
        }

        const anio = parseInt(document.getElementById("selectAnio").value, 10);

        const mesesSeleccionados = Array.from(
                document.querySelectorAll(".ml-item.selected")
        ).map(function (el) {
                    return parseInt(el.dataset.value, 10);  // 1..12
                });

        if (mesesSeleccionados.length === 0) {
            alert("Seleccioná al menos un mes.");
            return;
        }

        const periodos = mesesSeleccionados.map(function (mes) {
            return { anio: anio, mes: mes };
        });

        const payload = {
            sku: skuML,
            periodos: periodos
        };

        console.log("JSON que se envía a Laravel:", payload);

        $.ajax({
            url: 'api/getMachineLearning',   // 👉 tu ruta Laravel
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(payload),
            success: function (data) {
                console.log('Respuesta de Laravel / FastAPI:', data);

                // seguridad básica
                if (!data || !data.resultados || data.resultados.length === 0) {
                    alert("El predictor no devolvió resultados.");
                    return;
                }

                var res0 = data.resultados[0]; // tomo el primero como resumen

                // Completar inputs del modal
                $('#stock_actual').val(totalStockML);
                $('#demanda_total_horizonte').val(res0.demanda_total_horizonte);
                $('#compra_sugerida_total').val(res0.demanda_total_horizonte - totalStockML);

                // Y de paso llenamos la tabla:
                completarTablaPredicciones(data.resultados);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error llamando a getMachineLearning:', textStatus, errorThrown);
                console.error('Respuesta del servidor:', jqXHR.responseText);
                alert('Ocurrió un error consultando al predictor.');
            }
        });
        function completarTablaPredicciones(resultados) {
            var mesesNombres = [
                "Enero","Febrero","Marzo","Abril","Mayo","Junio",
                "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
            ];

            var $tbody = $('#tablaMachineLerning tbody');
            $tbody.empty(); // limpio "Sin Información"

            resultados.forEach(function (item) {
                var nombreMes = mesesNombres[item.mes - 1] || item.mes;

                var fila = '<tr>' +
                        '<td>' + nombreMes + ' ' + item.anio + '</td>' +
                        '<td>' + item.prediccion_ventas_mes + '</td>' +
                        '</tr>';

                $tbody.append(fila);
            });
        }
    }

    function limpiarModalMachineLearning() {

        // Limpiar textos del encabezado
        $("#tituloSKU").text("");
        $("#detalle").text("");

        // Limpiar inputs de resumen
        $("#stock_actual").val("");
        $("#demanda_total_horizonte").val("");
        $("#compra_sugerida_total").val("");

        // Limpiar selección del año (volver al primero)
        $("#selectAnio").val("2025");

        // Limpiar meses seleccionados
        $("#mesList .ml-item").removeClass("selected");

        // Restaurar texto del dropdown
        $("#mlSelectedText").text("Seleccionar meses");

        // Ocultar menú de meses si quedó abierto
        $("#mesList").hide();

        // Limpiar tabla de resultados
        let tbody = $("#tablaMachineLerning tbody");
        tbody.empty();
        tbody.append(
                '<tr>' +
                '<td>Sin Información</td>' +
                '<td>Sin Información</td>' +
                '</tr>'
        );

        // Resetear variable global del SKU
        skuML = null;
        totalStockML = null;
    }


</script>

    <style>
        .buttonDonatella {
            display: inline-block;
            border-radius: 4px;
            background-color: #f41f72;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 20px;
            padding: 20px;
            width: 200px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
        }
        .buttonSamira {
            display: inline-block;
            border-radius: 4px;
            background-color: #e12bf4;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 20px;
            padding: 20px;
            width: 200px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
        }
        .buttonViamore {
            display: inline-block;
            border-radius: 4px;
            background-color: #5088f4;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 20px;
            padding: 20px;
            width: 200px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
        }
        .button span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }
        .button span:after {
            content: '\00bb';  /* Entidades CSS. Para usar entidades HTML, use &#8594;*/
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
        }
        .button:hover span {
            padding-right: 25px;
        }
        .button:hover span:after {
            opacity: 1;
            right: 0;
        }
    </style>
@stop