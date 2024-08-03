@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Panel Inversor</i></div>
                    <table class="table table-striped table-bordered records_list">
                        <tr>
                            <td>
                                <section >
                                    <div>
                                        <h6>Acciones</h6>
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
                                    <input type="checkbox" id="checkBoxCantidadAcciones" name="opciones" checked="true" value="valor" onclick="selectores('checkBoxCantidadAcciones')"> Cantidad de Empresas (Principales ganadores)
                                </div>
                                <input type="number" id="cantidad" style="width: 60px;">
                                ApiKey: <input type="text" id="apikey" style="width: 200px" value = "H75CXB3AOHKM8ZMW">
                                <button id="enviar_consulta_mejeres_acciones" class="btn btn-success"  onclick="enviarSeleccion('verificacion')">Buscar</button>
                            </td>
                        </tr>
                        <td style="text-align: center;">
                            <button id="enviar_seleccion_acciones" class="btn btn-success" onclick="crearInversiones()">Invertir</button>
                        </td>
                        <td>
                            <input id='tipo_todo' onclick="tipoEstado('todo')" type="checkbox">Todo
                            <input id='tipo_espera' onclick="tipoEstado('espera')" type="checkbox">Espera
                            <input id='tipo_corriendo' onclick="tipoEstado('corriendo')" type="checkbox">Corriendo
                            <input id='tipo_vendida' onclick="tipoEstado('vendida')" type="checkbox">Vendida
                        </td>

                    </table>
                    <div class="panel-body">
                        <div id="table-inversiones"></div>
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
    <script src="../../js/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="../../js/tabulador/tabulator5-5-2min.css" rel="stylesheet">
    <script type="text/javascript" src="../../js/tabulador/tabulator5-5-2.min.js"></script>

    <!-- DataTables -->

    <script type="text/javascript">
        var checkBoxCantidadAcciones = document.getElementById('checkBoxCantidadAcciones');
        var inputCantidadAcciones = document.getElementById('cantidad');
        var modalFinish = document.getElementById('myModalFinish');
        var inputapikey = document.getElementById('apikey')
        var select1 = document.getElementById('select1');
        var select2 = document.getElementById('select2');
        var tipo_todo = document.getElementById('tipo_todo');
        var tipo_espera = document.getElementById('tipo_espera');
        var tipo_corriendo = document.getElementById('tipo_corriendo');
        var tipo_vendida = document.getElementById('tipo_vendida');

        var headers = {
            'Content-Type': 'application/json'
        }

        $(document).ready( function () {
            var table;
            // llenarSelectProveedor();
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
            cargaDatosTabulator()
        });

        var tableInversiones = new Tabulator("#table-inversiones", {
            height: "550px",
            columns: [
                {title: "Accion", field: "nbr_accion", width: 100, headerFilter:"input"},
                {title: "Recomendacion", field: "recomendacion",  width: 150},
                {title: "Dias Retencion", field: "dias_retencion", width: 140},
                {title: "Precio Accion", field: "precio", width: 130},
                {title: "Fecha de Compra", field: "fecha_compra", width: 155},
                {title: "Estado", field: "estado", width: 100, editor:"select",editorParams: {
                    values: {
                        "Espera": "Espera",
                        "Corriendo": "Corriendo",
                        "Vendida": "Vendida"
                    }
                },headerFilter:"input"},
                {title: "Porcentaje de Ganancia", field: "porcentaje_ganancia", width: 110,editor:true},
                {title: "Precio Venta", field: "precio_venta", width: 130, editor:true},
                {title: "Fecha de Finalizacion", field: "fecha_finalizacion", width: 140},
                {title: "Ganancia", field: "ganancia", width: 110},
                {title: "Precio Actual", field: "precioactual", width: 140},
                {title: "Fecha ultimo Precio", field: "fechaverificacionprecio", width: 180},
                {title: "Informe Accion", field: "informeia", width: 3310},
            ]
        });

        // Suscríbete al evento cellEdited
        tableInversiones.on("cellEdited", function(cell) {
            $.ajax({
                url: "/actualizarinversion",
                data: cell.getRow().getData(),
                type: "post"
            })
        });

        function cargaDatosTabulator(){
            tableInversiones.setData("/cargardatosinversores");
        }

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
                case "checkBoxCantidadAcciones":
                    if (checkBoxCantidadAcciones.checked){
                        inputCantidadAcciones.disabled = false
                    } else {
                        inputCantidadAcciones.disabled = true
                        inputCantidadAcciones.value = ""
                    }
                    break;
            }
        }
        function enviarSeleccion(tipo){
            limpiezaSelect()
            if (inputCantidadAcciones.value != ""){
                // Get the modal
                var modal = document.getElementById('myModal');
                // When the user clicks the button, open the modal
                modal.style.display = "block";
                $.ajax({
                    url: '/buscaracciones?apikey=' + inputapikey.value + "&cantidad=" + inputCantidadAcciones.value,
                    method: "GET",
                    headers:headers,
                    success: function(datos) {
                        //itera sobre los datos y agrega cada uno como una opción del select
                        $.each(datos, function(index, item) {
                            $("#select1").append("<option value='" + item.ticker + "'>" + item.ticker + "</option>");
                        });
                        if (tipo == "verificacion"){
                            modal.style.display = "none";
                        } else {
                            modal.style.display = "none";
                            modalFinish.style.display = "block"
                        }
                    }
                });
            } else alert('Debe agregar la cantidad de Empresas')
        }
        function cerrarFinish(){
            modalFinish.style.display = "none";
            location.reload();
            //close the modal
        }
        function crearInversiones(){
            if (select2.innerHTML.trim() === '') {
                alert('Debe Seleccionar Una Accion')
            } else {
                var modal = document.getElementById('myModal');
                // When the user clicks the button, open the modal
                modal.style.display = "block"
                var datosSelecionados = mostrarSeleccion()
                $.ajax({
                 url: '/invertir',
                 method: "POST",
                 headers: headers,
                 data: JSON.stringify({
                 empresas: datosSelecionados,
                 apikey: inputapikey.value
                 }),
                 success: function (datos) {
                 modal.style.display = "none";
                 cargaDatosTabulator()
                 }
                 })
            }
        }
        function limpiezaSelect(){
            select1.innerHTML = '';
            select2.innerHTML = '';
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

        function tipoEstado(tipoEstado){
            switch (tipoEstado) {
                case 'todo':
                    if (tipo_todo.checked){
                        tipo_espera.checked = false
                        tipo_corriendo.checked = false
                        tipo_vendida.checked = false
                        cargaDatosTabulator()
                    }else{
                        tableInversiones.clearData();
                    }
                    break;
                case 'espera':
                    if (tipo_espera.checked){
                        tipo_corriendo.checked = false
                        tipo_vendida.checked = false
                        tipo_todo.checked = false
                        llenarTabla('espera');
                    }else {
                        tableInversiones.clearData();
                    }
                    break;
                case 'corriendo': {
                    if (tipo_corriendo.checked) {
                        tipo_espera.checked = false
                        tipo_vendida.checked = false
                        tipo_todo.checked = false
                        llenarTabla('corriendo');
                    }else {
                        tableInversiones.clearData();
                    }
                    break;
                }
                case 'vendida': {
                    if (tipo_vendida.checked) {
                        tipo_espera.checked = false
                        tipo_corriendo.checked = false
                        tipo_todo.checked = false
                        llenarTabla('vendida');
                    }else {
                        tableInversiones.clearData();
                    }
                    break;
                }
            }
        }

        function llenarTabla(estado){
            switch (estado){
                case 'espera':
                    tableInversiones.setData('/listainversiones?tipo=espera')
                    break;
                case 'corriendo':
                    tableInversiones.setData('/listainversiones?tipo=corriendo')
                    break;
                case 'vendida':
                    tableInversiones.setData('/listainversiones?tipo=vendida')
                    break;
            }
        }

    </script>
@stop