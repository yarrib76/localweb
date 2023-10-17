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
                                    <input type="checkbox" id="checkboxPorcentajeDescuento" name="opciones" value="valor" onclick="selectores('checkboxPorcentajeDescuento')"> Descuento
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
                    </table>
                    <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                            </table>
                    </div>
                </div>
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

    </style>
@stop
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>


    <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
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

        $(document).ready( function () {
            var table;
            llenarSelectProveedor();
            $("#btnLeft").click(function () {
                var selectedItem = $("#select2 option:selected");
                $("#select1").append(selectedItem);
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

        //evento click para enviar los valores seleccionados del select
        $("#enviar_seleccion_paraborrar").click(function() {
            eliminarTabla()
            //obtiene los valores seleccionados del select
            var valoresSeleccionados = mostrarSeleccion();
            var headers = {
                'Content-Type': 'application/json'
            }
            var datos = valoresSeleccionados;
            console.log(datos)
            //realiza la petición ajax para enviar los datos
            $.ajax({
                url: '/cambioprecios',
                method: "POST",
                headers:headers,
                data: JSON.stringify(datos),
                success: function(json) {
                    var total = 0;
                    for(var i = 0; i < json.length; i++){
                        total =  total + json[i]['Total'];
                    }
                    document.getElementById("Total").value = total
                    table = $('#reporte').DataTable({
                                dom: 'Bfrtip',
                                "autoWidth": false,
                                buttons: [
                                    'excel'
                                ],
                                order: [1,'desc'],
                                "aaData": json,
                                "columns": [
                                    { "data": "Proveedor" },
                                    { "data": "Total" }
                                ]
                            }
                    );
                }
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
                table.destroy()
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
                        inputagregarPorcentaje.disabled = true
                        inputquitarPorcentaje.disabled = true
                        inputporcentaje.disabled = true
                        inputagregarPorcentaje.value = ""
                        inputquitarPorcentaje.value = ""
                        inputporcentaje.value = ""
                    } else {
                        inputprecioDolar.disabled = true
                        inputprecioDolar.value = ""
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
            var datosSelecionados = mostrarSeleccion()
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
                    console.log(json)
                    table = $('#reporte').DataTable({
                                dom: 'Bfrtip',
                                "autoWidth": false,
                                buttons: [
                                    'excel'
                                ],
                                order: [1,'desc'],
                                "aaData": json,
                                "columns": [
                                    { "data": "Proveedor" },
                                    { "data": "Total" }
                                ]
                            }
                    );
                }
            });
        }
    </script>
@stop