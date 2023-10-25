@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Reporte Inversion</i></div>
                    <div>
                        <select multiple id = "select_multi" onclick="toggleSelection(this)"></select>
                        <button id="enviar_seleccion" class="btn btn-success">Consultar</button>
                        Total <input id="Total" type="number" disabled>
                    </div>
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
        $(document).ready( function () {
            var table;
            llenarSelectProveedor();
        });
        //evento click para enviar los valores seleccionados del select
        $("#enviar_seleccion").click(function() {
            eliminarTabla()
            //obtiene los valores seleccionados del select
            var valoresSeleccionados = $("#select_multi").val();
            var headers = {
                'Content-Type': 'application/json'
            }
            var datos = { valores: valoresSeleccionados };
            //realiza la petición ajax para enviar los datos
            $.ajax({
                url: '/consultaInversion',
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
                        $("#select_multi").append("<option value='" + item.nombre + "'>" + item.nombre + "</option>");
                    });
                },
                error: function (datos) {
                    console.log("Este callback maneja los errores " + datos);
                }

            }); // ajax
        }
            //La función sombrea lo seleccionado del "select multiple" cuando hacen click
            var selectedValues = [];
            function toggleSelection(elem) {
                if (!selectedValues.includes(elem.value)) {
                    selectedValues.push(elem.value);
                    elem.setAttribute("selected", "selected");
                } else {
                    selectedValues.splice(selectedValues.indexOf(elem.value), 1);
                    elem.removeAttribute("selected");
                }
                updateSelection();
            }
        function updateSelection() {
            var options = document.querySelectorAll("option");
            options.forEach(function (option) {
                if (selectedValues.includes(option.value)) {
                    option.selected = true
                } else {
                    option.selected = false
                }
            });
        }
        function eliminarTabla(){
            if(typeof table != "undefined"){
                table.destroy()
            }
        }

    </script>
@stop