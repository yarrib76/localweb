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
                    @elseif (substr(Request::url('http://donalab.dyndns.org'),0,25) == 'http://donalab.dyndns.org')
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
                                <th>TotalVendido</th>
                                <th>TotalStock</th>
                                <th>PrecioVenta</th>
                                <th>Imagen</th>
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
        #LocalName {
            margin-top: 10px;
            margin-bottom: 100px;
            margin-right: 150px;
            margin-left: 500px;
            color: #00bcd4;
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
    var proveedor;
    var web = 'NO';
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
                        table.append("<tr><td>" + json['Articulo'] + "</td><td>"
                                + json['Detalle'] + "</td><td>"
                                + json['TotalVendido'] + "</td><td bgcolor=" + colorCelda + ">"
                                + json['TotalStock'] + "</td><td>"
                                + json['PrecioVenta'] + "</td><td>"
                                + '<img src= ' + json['imagessrc'] + " " + 'height="52" width="52" onclick=verImagen(' + "'" + json['imagessrc']+ "'" + ')>' + "</td>");
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