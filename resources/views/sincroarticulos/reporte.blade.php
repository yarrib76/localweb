@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Verificar Articulos</i>
                    </div>
                    <select id="select">
                        <option>Selecciona un Local</option>
                        @if (substr(Request::url('http://donatella.dyndns.org'),0,27) == 'http://donatella.dyndns.org')
                            <option>Samira</option>
                            <option>Viamore</option>
                        @elseif (substr(Request::url('http://samirasrl.dyndns.org'),0,27) == 'http://samirasrl.dyndns.org')
                            <option>Donatella</option>
                            <option>Viamore</option>
                        @elseif (substr(Request::url('http://viamore.dyndns.org'),0,25) == 'http://viamore.dyndns.org')
                            <option>Samira</option>
                            <option>Donatella</option>
                        @elseif (substr(Request::url('http://viam.dyndns.org'),0,22) == 'http://viam.dyndns.org')
                            <option>Viamore</option>

                        @endif
                    </select>
                    <button class="btn btn-primary" onclick="verificar()"><span class="glyphicon glyphicon-refresh"></span></button>
                    <div class="panel-body">
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Articulo</th>
                                <th>Detalle</th>
                                <th>PrecioOrigen</th>
                                <th>PrecioConvertido</th>
                                <<th>Moneda</th>
                                <th>Proveedor</th>
                            </tr>
                            </thead>
                            <tbody>
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
        #myModalError {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 30%;
            height: 50%;
            overflow-y: auto;
        }
    </style>
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <img src="refresh/load.gif" height="100" width="100">
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
<!-- DataTables -->
<script type="text/javascript">
    var modalError = document.getElementById('myModalError');
    var artInsert;
    //Asigno DataTable para que exista vacìa
    table1 =  $('#reporte').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],

            }
    );

    function verificar() {
        var selectLocal = document.getElementById("select");
        selectLocal = selectLocal.options[selectLocal.selectedIndex].text
        if (selectLocal == "Selecciona un Local") {
            window.alert("Debe Seleccionar un Local")
        } else {
            // Get the modal
            var modal = document.getElementById('myModal');
            // When the user clicks the button, open the modal
            modal.style.display = "block";
            var table = $("#reporte");
            table.children().remove()
            table.append("<thead><tr><th>Articulo</th><th>Detalle</th><th>PrecioOrigen</th><th>Moneda</th><th>Proveedor</th></tr></thead>")
            table.append("<tbody>")
            $.ajax({
                url: '/api/getartsinc?' + 'Local=' + selectLocal,
                dataType: "json",
                success: function (json) {
                    artInsert = json
                    if (json[0] != "") {
                        $.each(json, function (index, json) {
                            table.append("<tr><td>" + json['Articulo'] + "</td><td>" + json['Detalle'] + "</td><td>"
                                    + json['PrecioOrigen'] + "</td><td>"
                                    + json['PrecioConvertido'] + "</td><td>"
                                    + json['Moneda'] + "</td><td>"
                                    + json['Proveedor'] + "</td>");
                        });
                        table.append("<td>" + "<input type='button' id='boton' value='Sincro' class='btn btn-success' onclick=sincroArt()>" + "<td></tr>")
                        table.append("</tbody>")
                        dataTable()
                        //close the modal
                        modal.style.display = "none";
                    } else {
                        table.append("<tr><td>" + "Sin Informacion" + "</td><td>" + "Sin Informacion" + "</td><td>" + "Sin Informacion" + "</td><td>" + "Sin Informacion" + "</td>" + "</td></tr>");
                    }
                },
                error: function () {
                    console.log("Hola Mundo")
                    //close the modal
                    modal.style.display = "none";
                    // When the finish process, open the modalError
                    modalError.style.display = "block";
                }
            })
        }
    }
    function dataTable(){
        //Si exsiste la table1 la elimino para volver a crear con la nueva informacion
        table1.destroy()
        table1 =  $('#reporte').DataTable({
                    dom: 'Bfrtip',
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

    function sincroArt(){
        var selectLocal = document.getElementById("select");
        selectLocal = selectLocal.options[selectLocal.selectedIndex].text
        switch (selectLocal){
            case 'Viamore':
                $url = ("http://viamore.dyndns.org:8081/api/inArtisinc?");
                break;
            case 'Samira':
                $url = ("http://samirasrl.dyndns.org:8081/api/inArtisinc?");
                break;
            case 'Donatella':
                $url = ("http://donatella.dyndns.org:8081/api/inArtisinc?");
                break;
        }
        // Get the modal
        var modal = document.getElementById('myModal');
        // When the user clicks the button, open the modal
        modal.style.display = "block";
        var count = 0;
        for (i = 0; i < artInsert.length; i++ ){
            $.ajax({
                url: $url + 'Articulo=' + artInsert[i].Articulo
                + "&" + 'Detalle=' + artInsert[i].Detalle
                + "&" + 'ProveedorSKU=' + artInsert[i].ProveedorSKU
                + "&" + 'PrecioOrigen=' + artInsert[i].PrecioOrigen
                + "&" + 'PrecioConvertido=' + artInsert[i].PrecioConvertido
                + "&" + 'Moneda=' + artInsert[i].Moneda
                + "&" + 'Proveedor=' + artInsert[i].Proveedor,
                dataType: "json",
                success: function (json) {
                },
                error: function (json){
                    count++;
                    //El IF lo utilizo para poder finalizar el proceso. Esta en el error porque una vez dado de alta
                    //el articulo sale por error (no se por que je).
                    //Cuando count tiene la misma cantidad de articulos a insertar artInsert.length, cierra el gif
                    if (count == artInsert.length ){
                        console.log(count);
                        modal.style.display = "none";
                        location.reload();
                    }
                }
            })
        }
    }

</script>
@stop