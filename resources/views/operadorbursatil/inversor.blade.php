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
                            <button id="historico" class="btn btn-success" onclick="reporteHistorico()">ReporteHistorico</button>
                        </td>
                    </table>
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
        var checkBoxCantidadAcciones = document.getElementById('checkBoxCantidadAcciones');
        var inputCantidadAcciones = document.getElementById('cantidad');
        var modalFinish = document.getElementById('myModalFinish');
        var inputapikey = document.getElementById('apikey')
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

        function otro(){
            $.ajax({
                url: '/buscaracciones?apykey=' + inputapikey.value,
                method: "POST",
                headers: headers,
                data: JSON.stringify({
                    proveedores: datosSelecionados,
                    calculo: calculo,
                    tipo: tipo
                }),
                success: function (datos){

                }
            })
        }

        function crearInversiones(){
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

                }
            })
        }

        function limpiezaSelect(){
            var select1 = document.getElementById('select1');
            var select2 = document.getElementById('select2');
            select1.innerHTML = '';
            select2.innerHTML = '';
        }
    </script>
@stop