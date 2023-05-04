@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Panel de Usuarios</div>
                        <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Codigo</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($usuarios as $usuario)
                                    <tr>
                                        <td>{{$usuario->name}}</td>
                                        <td>{{$usuario->email}}</td>
                                        <td>{{$usuario->rol}}</td>
                                        <td>{{$usuario->codigo}}</td>
                                        <td><button class="btn btn-primary" onclick="modal('{{$usuario->id}}', '{{$usuario->name}}', '{{$usuario->email}}', '{{$usuario->rol}}', '{{$usuario->codigo}}', '{{$usuario->foto}}')">Editar</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .round-button {
            display:block;
            width:53px;
            height:53px;
            line-height:20px;
            border:2px solid #29966c;
            border-radius: 50%;
            color: #2945ff;
            text-align:center;
            text-decoration:none;
            background: #FFFFFF;
            box-shadow: 0 0 3px gray;
            font-size:10px;
            font-weight:bold;
        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
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
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            overflow-y: auto;
            border-radius: 10%;
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
    <div id="myModal" class="modal">
        <!-- Modal Ingreso -->
        <div id="modal-content" class="modal-content">
            <span id="close" class="close">&times;</span>
            <h4 align="center">Usuario</h4>
            <table>
                <tr>
                    <td>
                        <img id="imgFotoPersonal" src="" width="80" height="80">
                        <form id="formulario-imagen" enctype="multipart/form-data">
                            <input type="file" name="imagen" id="imagen" style="visibility:hidden;">
                            <button id="btnSubirImagen" type="button" class="btn btn-info" onclick="subirImagen()">Subir</button>
                            <label for="imagen" class="btn btn-info">Archivo</label>
                        </form>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="text" class="form-control" id="nombre" required="required" style= "font-size:15px" placeholder="Nombre">
                        <input type="text" class="form-control" id="email" style= "font-size:15px" placeholder="Email">
                    <!--    <input type="text" class="form-control" id="rol" style= "font-size:15px" placeholder="Rol"> -->
                        <select id="rol_select" class="form-control" name="rol_select_name"></select>
                        <div class="col-xs-2 col-sm-2 col-md-2 ">
                            <h4>7799</h4>
                        </div>
                        <div class="col-xs-7 col-sm-7 col-md-7">
                            <input type="text" class="form-control" id="codio" style= "font-size:15px" pattern="\d*" maxlength="8" minlength="8" placeholder="Codigo Barras" onfocus="limpiarInput()">
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <button id="btnGenerador" class="round-button" onclick="generarCodigoBarras()">Generar</button>
                        </div>
                        <h3>Codigo de Barras</h3>
                        <!-- <img id="imgCodigoBarras" src="" width="150" height="80"> -->
                        <img id="imgCodigoBarras">
                    </td>
                </tr>
            </table>
            <button style="margin-left: 35%" class='btn btn-primary' onclick="guardar()">Guardar</button>
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
        var imgCodigoBarras = document.getElementById('imgCodigoBarras')
        var nombreInput = document.getElementById('nombre')
        var emailInput = document.getElementById('email')
        var rolSelect = document.getElementById('rol_select')
        var codigoInput = document.getElementById('codio')
        var btnGenerador = document.getElementById('btnGenerador')
        var btnSubirImagen = document.getElementById('btnSubirImagen')
        var imagenInput = document.getElementById('imagen')
        var imgFotoPersonal = document.getElementById('imgFotoPersonal')
        var fotoPersonal
        let codigoBarrasGuardado;
        let codigoBarrasConBit;
        var user_id;
        $(document).ready( function () {
            cargaSelectRol()
            var table =  $('#reporte').DataTable({
                "lengthMenu": [ [8,  16, 32, -1], [8, 16, 32, "Todos"] ],
                language: {
                    search: "Buscar:",
                    "thousands": ",",
                    processing:     "Traitement en cours...",
                    lengthMenu:    "Mostrar _MENU_ Usuarios",
                    info:           "Mostrando del  _START_ al _END_ de _TOTAL_ Usuarios",
                    infoEmpty:      "0 Usuarios",
                    infoFiltered:   "(Filtrando _MAX_ Usuarios en total)",
                    infoPostFix:    "",
                    loadingRecords: "Chargement en cours...",
                    zeroRecords:    "No se encontraron usuarios para esa busqueda",
                    emptyTable:     "No existen usuarios",
                    paginate: {
                        first:      "Primero",
                        previous:   "Anterior",
                        next:       "Proximo",
                        last:       "Ultimo"
                    }
                }
            });
        });
        function modal(usuario_id, nombre, email, rol, codigo, foto){
            llenarInput(usuario_id, nombre, email, rol, codigo, foto)
            imgCodigoBarras.hidden = true
            btnGenerador.disabled  = true
            btnSubirImagen.disabled = true
            imagenInput.addEventListener('input', function (evt) {
                btnSubirImagen.disabled = false
            });
            user_id = usuario_id
            // Get the modal
            var modal = document.getElementById('myModal');

            // Get the <span> element that closes the modal
            var span = document.getElementById("close");

            // When the user clicks the button, open the modal
            modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    location.reload()
                }
            }
        }
        function llenarInput(usuario_id, nombre, email, rol, codigo, foto){
            nombreInput.value = nombre
            emailInput.value = email
            rolSelect.value = rol
            codigoBarrasGuardado = codigo
            codigoInput.value = codigo.slice(4,-1)
            imgFotoPersonal.src = "imagenes/" + foto
            fotoPersonal = foto
            console.log(fotoPersonal)
        }

        function generarCodigoBarras(){
            imgCodigoBarras.hidden = false
            imgCodigoBarras.src = ""
            var codigoInput = document.getElementById('codio')
            $.ajax({
                url:'/getcodigo?codigo=' + codigoInput.value,
                dataType: "json",
                success: function (json) {
                    /* Utiliza el codigo de barras de laravel, no puedo insertar la variable json lo reemplazo por el web
                    imgCodigoBarras.src="data:image/png;base64,{{DNS1D::getBarcodePNG(7799123456780, 'EAN13',1,40)}}"
                    */
                    /*Utilizo la creación del codigo de barras provisto a travez de una pagina Web
                     https://free-barcode.com/howto/addbarcodetowebpage.asp?gclid=CjwKCAjwxr2iBhBJEiwAdXECw2e8JMIQm0tc_zrLUcJvh1J-YeK9ldSc8diR8YpQ4Bls5aVndPmnhxoCNzEQAvD_BwE
                     */
                    codigoBarrasConBit = json
                    imgCodigoBarras.src=src="https://www.free-barcode.com/barcodemaker.asp?bc1="+ json + "&bc2=0&bc3=3&bc4=0.6&bc5=0&bc6=1&bc7=Arial&bc8=15&bc9=1"
                },
            });
        }

        function guardar(){
            if (btnGenerador.disabled == true){
                codigoBarras = codigoBarrasGuardado
            }else codigoBarras = codigoBarrasConBit
            $.ajax({
                url:'/guardarPersonal?nombre=' + nombreInput.value + '&email=' + emailInput.value + '&codigo=' + codigoBarras + '&user_id=' + user_id + '&fotoPersonal=' + fotoPersonal + '&tipo_role=' + rolSelect.value,
                dataType: "json",
                success: function (json){
                    alert("Guardado Correctamente")
                    location.reload()
                }
            });
        }

        function limpiarInput(){
           // codigoInput.value = codigoInput.value.slice(0,- 1)
            btnGenerador.disabled = false
        }

        function subirImagen() {
            var formData = new FormData($('#formulario-imagen')[0]);
            $.ajax({
                url: '/guardar-imagen',
                type: 'POST',
                data: formData,
                dataType: 'json',
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response)
                    imgFotoPersonal.src = "imagenes/" + response
                    fotoPersonal = response
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }

        function cargaSelectRol(){
            $.ajax({
                type: 'get',
                url: '/api/relesWebSelect',
                //    data: {radio_id:category_id , flota_id:flota_id},
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (datos, textStatus, jqXHR) {
                    $.each(datos, function (i, value) {
                        $('#rol_select').append("<option value='" + value['tipo_role'] + "'>" + value['tipo_role'] + '</option>');
                    });
                    //Selecciono en el combo como default el proveedor que tiene definido
                    $("#rol_select").val(rolSelect.value);

                }
            })
        }
    </script>
@stop