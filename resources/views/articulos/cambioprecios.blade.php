@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Cambio de Precio</i></div>
                    <table class="table table-striped table-bordered records_list">
                        <tr>
                            <td>
                                <section >
                                    <div>
                                        <h6>Proveedores</h6>
                                        <select id="select1" size="5" multiple></select>
                                        <div>
                                            <input type="text" id="txtRight" />
                                        </div>
                                    </div>
                                    <div>
                                        <h6>_</h6>
                                        <input type="button" id="btnRight" value="&gt;&gt;" />
                                        <input type="button" id="btnLeft" value="&lt;&lt;" />
                                    </div>
                                    <div>
                                        <h6>Seleccionados</h6>
                                        <select id="select2" size="4" multiple>
                                        </select>
                                    </div>
                                </section>
                            </td>
                            <td>
                                <div>
                                    <input type="checkbox" id="checkboxAgregoPorcentaje" name="opciones" value="valor" onclick="selectores('checkboxAgregoPorcentaje')"> Aumentos  basados en Porcentajes (Ana)
                                </div>
                                <input type="number" id="porcentaje" style="width: 60px;" disabled="true">
                                <div>
                                    <input type="checkbox" id="checkboxBasadoDolar" name="opciones" value="valor" onclick="selectores('checkboxBasadoDolar')"> Aumentos basados en Precio Dolar (Marcelo)
                                </div>
                                <input type="number" id="precioDolar" style="width: 60px;" disabled="true">
                                <div>
                                    <input type="checkbox" id="checkboxQuitoAgregoPorcentaje" name="opciones" value="valor" onclick="selectores('checkboxQuitoAgregoPorcentaje')"> Aumentos quitando y agregando % (Z)
                                </div>
                                <input type="number" id="quitarPorcentaje" style="width: 60px;" disabled="true">Quita
                                <input type="number" id="agregarPorcentaje" style="width: 60px;" disabled="true">Agrega
                            </td>
                            <td>
                                <div>
                                    <input type="checkbox" id="checkboxPorcentajeDescuento" name="opciones" value="valor" disabled="true" onclick="selectores('checkboxPorcentajeDescuento')"> Descuento
                                </div>
                                <input type="number" id="porcentajeDescuento" style="width: 60px;" disabled="true">

                            </td>
                        </tr>
                        <td style="text-align: center;">
                            <button id="enviar_seleccion_verificacion" class="btn btn-success"  onclick="enviarSeleccion('verificacion')">Verificacion</button>
                        </td>
                        <td style="text-align: center;">
                            <button id="enviar_seleccion_produccion" class="btn btn-success" onclick="enviarSeleccion('produccion')">Produccion</button>
                        </td>
                        <td>
                            <button id="historico" class="btn btn-success" onclick="reporteHistorico()">ReporteHistorico</button>
                        </td>
                    </table>
                    <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Articulo</th>
                                    <th>PrecioConvertidoViejo</th>
                                    <th>nuevoPrecioConvertido</th>
                                    <th>PrecioManualViejo</th>
                                    <th>nuevoPrecioManual</th>
                                    <th>PrecioOrigenViejo</th>
                                    <th>nuevoPrecioOrigen</th>
                                    <th>Proveedor</th>
                                </tr>
                                </thead>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <img src="refresh/load.gif" height="100" width="100">
        </div>
    </div>
    <div id="myModalFinish" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="pedidos" class="table table table-scroll table-striped">
                    <thead>
                    <tr>
                        <td><img src="refresh/checkmark.png" height="100" width="100"></td>
                        <td><h1>Finalizado</h1></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="button" value="Cerrar" class="btn btn-success" onclick="cerrarFinish()"></td>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>

    <style>
        SELECT, INPUT[type="text"] {
            width: 180px;
            box-sizing: border-box;
        }

        SECTION > DIV {
            float: left;
            padding: 4px;
        }
        SECTION > DIV + DIV {
            width: 40px;
            text-align: center;
        }
        #myModal {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 8%;
            height: 20%;
            overflow-y: auto;
        }
        #myModalFinish {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 30%;
            height: 50%;
            overflow-y: auto;
        }
    </style>
@stop
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
    <link href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-html5-2.4.2/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../js/tabulador/tabulator.css">

    <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script>

    <script src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-html5-2.4.2/datatables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>

    <!-- DataTables -->

    <script type="text/javascript">
        var checkboxAgregoPorcentaje = document.getElementById('checkboxAgregoPorcentaje');
        var checkboxBasadoDolar = document.getElementById('checkboxBasadoDolar');
        var checkboxQuitoAgregoPorcentaje = document.getElementById('checkboxQuitoAgregoPorcentaje');
        var inputporcentaje = document.getElementById('porcentaje');
        var inputprecioDolar = document.getElementById('precioDolar');
        var inputquitarPorcentaje = document.getElementById('quitarPorcentaje');
        var inputagregarPorcentaje = document.getElementById('agregarPorcentaje');
        var inputPorcentajedescuento = document.getElementById('porcentajeDescuento');
        inputPorcentajedescuento.value = ""
        var modalFinish = document.getElementById('myModalFinish');

        $(document).ready( function () {
            var table;
            llenarSelectProveedor();
            $("#btnLeft").click(function () {
                var selectedItem = $("#select2 option:selected");
                $("#select1").append(selectedItem);
                // Ordenar las opciones en select1 alfabéticamente por texto
                $("#select1").html($("#select1").find("option").sort(function(a, b) {
                    return a.text.localeCompare(b.text);
                }));
            });

            $("#btnRight").click(function () {
                var selectedItem = $("#select1 option:selected");
                $("#select2").append(selectedItem);
                $("#txtRight").val('')
            });

            $("#select1").change(function () {
                var selectedItem = $("#select1 option:selected");
                $("#txtRight").val(selectedItem.text());
            });
        });

        function llenarSelectProveedor(){
            $.ajax({
                type: 'get',
                url: '/api/listaSelectProveeor',
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (datos) {
                    //itera sobre los datos y agrega cada uno como una opción del select
                    $.each(datos, function(index, item) {
                        $("#select1").append("<option value='" + item.nombre + "'>" + item.nombre + "</option>");
                    });
                },
                error: function (datos) {
                    console.log("Este callback maneja los errores " + datos);
                }

            }); // ajax
        }

        function eliminarTabla(){
            if(typeof table != "undefined"){
                table.clear().draw();
                table.destroy()
            }
        }

        function limpiaTabla(){
            if(typeof table != "undefined"){
                table.clear().draw();
            }
        }

        function mostrarSeleccion() {
            var select2 = document.getElementById('select2');
            var allOptions = Array.from(select2.options);
            var allItems = allOptions.map(function(option) {
                return option.text;
            });

            return allItems;
        }

        function selectores(tipo){
            switch (tipo){
                case "checkboxAgregoPorcentaje":
                    if (checkboxAgregoPorcentaje.checked){
                        inputporcentaje.disabled = false
                        checkboxBasadoDolar.checked = false
                        checkboxQuitoAgregoPorcentaje.checked = false
                        inputagregarPorcentaje.disabled = true
                        inputquitarPorcentaje.disabled = true
                        inputprecioDolar.disabled = true
                        inputagregarPorcentaje.value = ""
                        inputquitarPorcentaje.value = ""
                        inputprecioDolar.value = ""

                    } else {
                        inputporcentaje.disabled = true
                        inputporcentaje.value = ""
                    }
                    break;
                case "checkboxBasadoDolar":
                    if (checkboxBasadoDolar.checked){
                        inputprecioDolar.disabled = false
                        checkboxAgregoPorcentaje.checked = false
                        checkboxQuitoAgregoPorcentaje.checked = false
                        checkboxPorcentajeDescuento.disabled = false
                        inputagregarPorcentaje.disabled = true
                        inputquitarPorcentaje.disabled = true
                        inputporcentaje.disabled = true
                        inputagregarPorcentaje.value = ""
                        inputquitarPorcentaje.value = ""
                        inputporcentaje.value = ""
                    } else {
                        inputprecioDolar.disabled = true
                        inputprecioDolar.value = ""
                        checkboxPorcentajeDescuento.disabled = true
                        checkboxPorcentajeDescuento.checked = false
                        inputPorcentajedescuento.disabled = true;
                        inputPorcentajedescuento.value = ""
                    }
                    break;
                case "checkboxQuitoAgregoPorcentaje":
                    if (checkboxQuitoAgregoPorcentaje.checked){
                        inputagregarPorcentaje.disabled = false
                        inputquitarPorcentaje.disabled = false
                        checkboxAgregoPorcentaje.checked = false
                        checkboxBasadoDolar.checked = false
                        inputporcentaje.disabled = true
                        inputprecioDolar.disabled = true
                        inputporcentaje.value = ""
                        inputprecioDolar.value = ""
                    } else {
                        inputagregarPorcentaje.disabled = true
                        inputquitarPorcentaje.disabled = true
                        inputagregarPorcentaje.value = ""
                        inputquitarPorcentaje.value = ""
                    }
                    break;
                case "checkboxPorcentajeDescuento":
                    if (checkboxPorcentajeDescuento.checked){
                        inputPorcentajedescuento.disabled = false;
                    } else {
                        inputPorcentajedescuento.disabled = true;
                        inputPorcentajedescuento.value = ""
                    }
            }
        }
        function enviarSeleccion(tipo){
            if (validacionOpciones()){
                var datosSelecionados = mostrarSeleccion()
                if (tipo == 'verificacion'){
                    eliminarTabla()
                } else limpiaTabla()
                if (checkboxAgregoPorcentaje.checked){
                    var calculo = {
                        tipo: "porcentaje",
                        valor: inputporcentaje.value,
                        porcentajeDescuento: inputPorcentajedescuento.value
                    }
                }
                if (checkboxBasadoDolar.checked){
                    var calculo = {
                        tipo: "dolar",
                        valor: inputprecioDolar.value,
                        porcentajeDescuento: inputPorcentajedescuento.value
                    }
                }
                if (checkboxQuitoAgregoPorcentaje.checked){
                    var calculo = {
                        tipo: "agregoQuito",
                        valorAgrego: inputagregarPorcentaje.value,
                        valorQuito: inputquitarPorcentaje.value,
                        porcentajeDescuento: inputPorcentajedescuento.value
                    }
                }
                var headers = {
                    'Content-Type': 'application/json'
                }
                // Get the modal
                var modal = document.getElementById('myModal');
                // When the user clicks the button, open the modal
                modal.style.display = "block";
                $.ajax({
                    url: '/cambioprecios',
                    method: "POST",
                    headers:headers,
                    data: JSON.stringify({
                        proveedores: datosSelecionados,
                        calculo: calculo,
                        tipo: tipo
                    }),
                    success: function(json) {
                        if (tipo == "verificacion"){
                            table = $('#reporte').DataTable({
                                        dom: 'Bfrtip',
                                        "autoWidth": false,
                                        buttons: [
                                            'excel'
                                        ],
                                        order: [1,'desc'],
                                        "aaData": json,
                                        "columns": [
                                            { "data": "Fecha" },
                                            { "data": "Articulo" },
                                            { "data": "PrecioConvertidoViejo" },
                                            { "data": "nuevoPrecioConvertido" },
                                            { "data": "PrecioManualViejo" },
                                            { "data": "nuevoPrecioManual" },
                                            { "data": "PrecioOrigenViejo" },
                                            { "data": "nuevoPrecioOrigen" },
                                            { "data": "Proveedor" },
                                        ]
                                    }
                            );
                            modal.style.display = "none";
                        } else {
                            modal.style.display = "none";
                            modalFinish.style.display = "block"
                        }
                    }
                });
            }

        }

        function validacionOpciones(){
            if (checkboxAgregoPorcentaje.checked == false && checkboxBasadoDolar.checked == false && checkboxQuitoAgregoPorcentaje.checked == false){
                alert('Debe haber al menos un checkbox seleccionado.')
                return false
            }
            if (checkboxAgregoPorcentaje.checked && inputporcentaje.value == ""){
                alert('Debe agragar un porcentaje para continuar')
                return false
            }
            if (checkboxBasadoDolar.checked && inputprecioDolar.value == ""){
                alert('Debe agregar el valor del dolar para continuar')
                return false
            }
            if (checkboxQuitoAgregoPorcentaje.checked && (inputagregarPorcentaje.value == "" || inputquitarPorcentaje.value == "")){
                alert('Debe agregar porcentaje para quitar y porcentaje para agregar')
                return false
            }
            if (checkboxPorcentajeDescuento.checked && inputPorcentajedescuento.value == ""){
                alert('Debe agragar un porcentaje de descuento')
                return false
            }
            return true
        }

        function cerrarFinish(){
            modalFinish.style.display = "none";
            location.reload();
            //close the modal
        }
    </script>
    <!-- Incluir archivo cambioPreciosHistorico --!>
    @include('articulos.cambioprecioshistorico')
@stop