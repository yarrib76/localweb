@extends('layouts.master')
@section('contenido')
    <div class="container">
                <h4>Ingrese Fecha</h4>
                <input type="text" id="fecha" name="fecha" value="{{$año}} " />
                <select name="listaFecha" onChange="combo(this, 'fecha')">
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                    <option value="2028">2028</option>
                    <option value="2029">2029</option>
                    <option value="2030">2030</option>
                </select>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><i class="fa fa-cog">Clientes</i></div>
                            <div class="panel-body">
                                    <table id="reporte" class="table table-striped table-bordered records_list">
                                        <thead>
                                        <tr>
                                            <th>Clientes</th>
                                            <th>Facturado</th>
                                            <th>Meses</th>
                                            <th>Accion</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($clientes as $cliente)
                                            <tr>
                                                <td>{{$cliente->Cliente}}</td>
                                                <td>{{$cliente->Total}}</td>
                                                <td><p><span class="w3-badge">{{$cliente->Meses}}</span></p></td>
                                                <td>
                                                    <button value="Graficar" class="btn btn-info" onclick="obtengoFacturacionMensual({{$cliente->Id}},'{{$cliente->Cliente}}');"><i class="fa fa-pie-chart" aria-hidden="true"></i></button>
                                                    <input type="button" value="Articulos" class="btn btn-info" onclick="obtengoArticulos('{{$cliente->Id}}','{{$año}}','{{$cliente->Cliente}}');">
                                                    <button value="Fact" class="btn btn-info" onclick="obtengoFacturas('{{$cliente->Id}}','{{$año}}','{{$cliente->Cliente}}');"><i class="fa fa-money" aria-hidden="true"></i></button>
                                                    <button value="Contacto" class="btn btn-info" onclick="obtengoDatosCliente('{{$cliente->Id}}');"><i class="fa fa-phone" aria-hidden="true"></i></button>
                                                    @if(!empty($cliente->comentarios))
                                                        <button id="btnConLlamados" value="Comentario" class="btn btn-success" onclick="comentario('{{$cliente->Id}}','{{$cliente->Cliente}}');"><i class="fa fa-book"></i></button>
                                                    @else
                                                    <button id="btnSinLlamados" value="Comentario" class="btn btn-success" onclick="comentario('{{$cliente->Id}}','{{$cliente->Cliente}}');"><i class="fa fa-book"></i></button>
                                                    @endif
                                                </td>
                                                 <!--   {!! Html::linkRoute('biclientearticulos.index', 'Articulos', ['Cliente_ID'=>$cliente->Id,'anio' => $año] , ['class' => 'btn btn-primary'] ) !!}
                                                    {!! Html::linkRoute('biclientefacturas.index', 'Facturas', ['Cliente_ID'=>$cliente->Id,'anio' => $año] , ['class' => 'btn btn-primary'] ) !!}</td> -->
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 25%;
            top: 15%;
            width: 50%; /* Full width */
            height: 80%; /* Full height */
            overflow: auto ; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }


        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 100%;
            height: 100%;
            top: -10%;
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

        .well {
            background: none;
            height: 420px;
        }
    </style>
    <!-- The Modal -->
    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="col-xs-12 col-xs-offset-0 well">
                <div id="piechart_3d" style="width: 500px; height: 300px;"></div>
            </div>
        </div>

    </div>

    <!-- The Modal Articulos-->
    <div id="myModalArticulos" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h5 id="cliente"></h5>
            <table id="articulos" class="table table-striped table-bordered records_list">
                <thead>
                <tr>
                    <th>Articulo</th>
                    <th>Descripcion</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <td>Sin Informacion</td>
                <td>Sin Informacion</td>
                <td>Sin Informacion</td>
                </tbody>
            </table>
        </div>
    </div>
    <!-- The Modal Articulos-->
    <div id="myModalFacturas" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h5 id="cliente"></h5>
            <table id="facturas" class="table table-striped table-bordered records_list">
                <thead>
                <tr>
                    <th>Factura</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Accion</th>
                </tr>
                </thead>
                <tbody>
                <td>Sin Informacion</td>
                <td>Sin Informacion</td>
                <td>Sin Informacion</td>
                <td>Sin Informacion</td>
                </tbody>
            </table>
        </div>
    </div>
    <!-- The Modal Articulos-->
    <div id="myModalArticulosByFactura" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h5 id="NroFactura"></h5>
            <table id="articulosbyfactura" class="table table-striped table-bordered records_list">
                <thead>
                <tr>
                    <th>Articulo</th>
                    <th>Descripcion</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <td>Sin Informacion</td>
                <td>Sin Informacion</td>
                <td>Sin Informacion</td>
                </tbody>
            </table>
        </div>
    </div>
    <!-- The Modal Articulos-->
    <div id="myModalDatosClientes" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h4 align="center">DATOS DEL CLIENTE</h4>
            <h5 id="DatosNombre">Nombre:</h5>
            <h5 id="DatosApellido">Apellido:</h5>
            <h5 id="DatosMail">Mail:</h5>
            <h5 id="DatosTelefono">Telefono:</h5>
            <h5 id="DatosLocalidad">Localidad:</h5>
            <h5 id="DatosProvincia">Provincia:</h5>
            <h5 id="DatosEncuesta">Encuesta:</h5>
        </div>
    </div>
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        /* The Modal (background) */
        #myModalComentarios {
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
        #modal-content-comentarios {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 3px solid #888;
            width: 70%;
            overflow-y: auto;
        }
    </style>
    <!-- The Modal Comentarios-->
    <div id="myModalComentarios" class="modal">

        <!-- Modal content -->
        <div id="modal-content-comentarios" class="modal-content">
            <span class="close1">&times;</span>
            <h5 id="cliente"></h5>
            <div id="general">
                <div id="nuevomensajes">
                    <textarea id="textarea" class="textarea is-warning" type="text" placeholder="Escriba una nota"  rows="3"></textarea>
                    <div id="botones">
                        <button id="agregar"  class="btn btn-primary" onclick="agregarNota({{$user_id}});"><i class="fa fa-check"></i></button>
                        <button id="botoncerrar" class="btn btn-success" onclick="cerrar();"><i class="fa fa-close"></i></button>
                    </div>
                </div>
                <div id="mensajes">
                    <div class="col-xs-12 col-xs-offset-0 well">
                        <table id="comentarios" class="table table table-scroll table-striped">
                            <thead>
                            <tr>

                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('extra-javascript')
    <!--Le agrgue para que aparezca el boton Excel-->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>


    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="../../css/numredondos.css">
    <!-- DataTables -->

    <script type="text/javascript">
        var globalCliente_id
        $(document).keyup(function(e) {
            if (e.keyCode == 27) { // escape key maps to keycode `27`
                cerrar()
            }
        });
        $(document).ready( function () {
            $('#reporte').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ],
                        "lengthMenu": [ [8,  16, 32, -1], [8, 16, 32, "Todos"] ],
                        "columns": [
                            { "width": "30%" },
                            null,
                            null,
                            null
                        ],
                        language: {
                            search: "Buscar:",
                            "thousands": ",",
                            processing:     "Traitement en cours...",
                            lengthMenu:    "Mostrar _MENU_ clientes",
                            info:           "Mostrando del  _START_ al _END_ de _TOTAL_ clientes",
                            infoEmpty:      "0 clientes",
                            infoFiltered:   "(Filtrando _MAX_ clientes en total)",
                            infoPostFix:    "",
                            loadingRecords: "Chargement en cours...",
                            zeroRecords:    "No se encontraron clientes para esa busqueda",
                            emptyTable:     "No existen clientes",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Proximo",
                                last:       "Ultimo"
                            }
                        },
                        order: [2,'asc']
                    }

            );
            //Asigno DataTable para que exista vacìa
            table1 =  $('#articulos').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ],

                    }
            );
            //Asigno DataTable para que exista vacìa
            table2 =  $('#facturas').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ],

                    }
            );
            //Asigno DataTable para que exista vacìa
            table3 =  $('#articulosbyfactura').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ],

                    }
            );
        } );

    </script>
    <script type="text/javascript">
        function grafico(json, id_cliente,nbreCliente) {
            console.log(json)
            var cliente = json;
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(donut_chart);
            function donut_chart() {
                var data = google.visualization.arrayToDataTable(cliente);
                var options = {
                    title: 'Facturaciòn del cliente: ' + nbreCliente + ' año ' + fecha.value,
                    is3D: true,

                }
                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                chart.draw(data, options);
            }
            // Get the modal
            var modal = document.getElementById('myModal');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

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
                }
            }
        }
        function obtengoFacturacionMensual(id_cliente,nbreCliente){
            $.ajax({
                url: 'biclientes?id_cliente=' + id_cliente + "&anio=" + fecha.value,
                dataType : "json",
                success : function(json) {
                    grafico(json, id_cliente,nbreCliente);
                }
            });
        }
        function combo(listaFecha, fecha) {
            fecha = document.getElementById(fecha);
            var idx = listaFecha.selectedIndex;
            var content = listaFecha.options[idx].innerHTML;
            fecha.value = content;
            window.location.replace("../api/biclientes?anio=" + fecha.value);

        }

        function cerrar(){
            // Get the modal
            var modalComentario = document.getElementById('myModal');
            // When the user clicks on <span> (x), close the modal
            modalComentario.style.display = "none";
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalComentario) {
                    modalComentario.style.display = "none";
                }
            }
        }

        function obtengoArticulos(cliente_id,anio,cliente){
            var table = $("#articulos");
            table.children().remove()
            table.append("<thead><tr><th>Articulo</th><th>Detalle</th><th>Total</th></tr></thead>")
            table.append("<tbody>")
            $.ajax({
                url: '/biclientearticulos?Cliente_ID=' + cliente_id + '&anio=' + anio ,
                dataType : "json",
                success : function(json) {
                    if (json[0] != ""){
                        $.each(json, function(index, json){
                            table.append("<tr><td>"+json['Articulo']+"</td><td>"+json['Descripcion']+"</td><td>"+json['Total']+"</td></tr>");
                        });
                        table.append("</tbody>")
                        dataTable()
                    }else {
                        table.append("<tr><td>"+ "Sin Informacion" +"</td><td>"+"Sin Informacion"+"</td><td>"+"Sin Informacion"+"</td>"+"</td></tr>");
                    }
                }
            });
            // Get the modal
            var modal = document.getElementById('myModalArticulos');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[1];

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
                }
            }
            $(".modal-content #cliente").html( cliente + " Año " + anio);
        }
        function dataTable(){
            //Si exsiste la table1 la elimino para volver a crear con la nueva informacion
            table1.destroy()
            table1 =  $('#articulos').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ]
                    }
            );

        }
        function obtengoFacturas(cliente_id,anio,cliente){
            var tableFact = $("#facturas");
            tableFact.children().remove()
            tableFact.append("<thead><tr><th>Factura</th><th>Total</th><th>Fecha</th><th>Accion</th></tr></thead>")
            tableFact.append("<tbody>")
            $.ajax({
                url: '/biclientefacturas?Cliente_ID=' + cliente_id + '&anio=' + anio ,
                dataType : "json",
                success : function(json) {
                    if (json[0] != ""){
                        $.each(json, function(index, json){
                            tableFact.append("<tr><td>"+json['Nrofactura']+"</td><td>"+json['Total']+"</td><td>"+json['Fecha']+"</td>" +
                                    "<td>" + "<input type='button' value='Articulos' class='btn btn-info' onclick='obtengoArticulosByFactura(" + json['Nrofactura'] + ")'" + "</td>" + "</tr>");
                        });
                        tableFact.append("</tbody>")
                        dataTableFactura()
                    }else {
                        tableFact.append("<tr><td>"+ "Sin Informacion" +"</td><td>"+"Sin Informacion"+"</td><td>"+"Sin Informacion"+"</td>"+
                                "<td>"+"Sin Informacion" + "</td></tr>");
                    }
                }
            });
            // Get the modal
            var modal = document.getElementById('myModalFacturas');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[2];

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
                }
            }
            $(".modal-content #cliente").html( cliente + " Año " + anio);
        }
        function dataTableFactura(){
            //Si exsiste la table2 la elimino para volver a crear con la nueva informacion
            table2.destroy()
            table2 =  $('#facturas').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ]
                    }
            );
        }
        function obtengoArticulosByFactura(nrofactura){
            var tablebyFact = $("#articulosbyfactura");
            tablebyFact.children().remove()
            tablebyFact.append("<thead><tr><th>Articulo</th><th>Detalle</th><th>Total</th></tr></thead>")
            tablebyFact.append("<tbody>")
            $.ajax({
                url: '/biclientearticulosbyfactura?nroFactura=' + nrofactura ,
                dataType : "json",
                success : function(json) {
                    if (json[0] != ""){
                        $.each(json, function(index, json){
                            tablebyFact.append("<tr><td>"+json['Articulo']+"</td><td>"+json['Descripcion']+"</td><td>"+json['Total']+"</td></tr>");
                        });
                        tablebyFact.append("</tbody>")
                        dataTableByFactura()
                    }else {
                        tablebyFact.append("<tr><td>"+ "Sin Informacion" +"</td><td>"+"Sin Informacion"+"</td><td>"+"Sin Informacion"+"</td>"+"</td></tr>");
                    }
                }
            });
            // Get the modal
            var modal = document.getElementById('myModalArticulosByFactura');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[3];

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
                }
            }
            $(".modal-content #NroFactura").html("Articulos Factura " + nrofactura );
        }
        function dataTableByFactura(){
            //Si exsiste la table2 la elimino para volver a crear con la nueva informacion
            table3.destroy()
            table3 =  $('#articulosbyfactura').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ]
                    }
            );
        }
        function obtengoDatosCliente(cliente_id){
            $.ajax({
                url: '/api/datoscliente?cliente_id=' + cliente_id ,
                dataType : "json",
                success : function(json) {
                    $(".modal-content #DatosNombre").html( "Nombre: " + json[0]['nombre']);
                    $(".modal-content #DatosApellido").html( "Apellido: " + json[0]['apellido']);
                    $(".modal-content #DatosMail").html( "Mail: " + json[0]['mail']);
                    $(".modal-content #DatosTelefono").html( "Telefono: " + json[0]['telefono']);
                    $(".modal-content #DatosLocalidad").html( "Localidad: " + json[0]['localidad']);
                    $(".modal-content #DatosProvincia").html( "Provincia: " + json[0]['provincias']['nombre']);
                    $(".modal-content #DatosEncuesta").html( "Encuesta: " + json[0]['encuesta']);

                }
            });
            // Get the modal
            var modal = document.getElementById('myModalDatosClientes');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[4];

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
                }
            }
        }

        function comentario(cliente_id,cliente){
            globalCliente_id = cliente_id
            var table = $("#comentarios");
            table.children().remove()
            table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
            $.ajax({
                url: '/api/registrosllamadas?cliente_id=' + cliente_id,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['nombre']+"</td><td>"+json['comentario']+
                                "</td><td>"+json['fecha']+"</td>"+ "</tr>");
                    });
                }
            });
            // Get the modal
            var modalComentario = document.getElementById('myModalComentarios');

            // Get the <span> element that closes the modal
            var spanComentario = document.getElementsByClassName("close1")[0];

            // When the user clicks the button, open the modal
            modalComentario.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            spanComentario.onclick = function() {
                modalComentario.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalComentario) {
                    modalComentario.style.display = "none";
                }
            }
            $(".modal-content #cliente").html( cliente);
        }
        function agregarNota(user_id){
            var textarea = $.trim($("textarea").val());
            if (textarea != ""){
                $.ajax({
                    url: '/api/agregarregistrollamadas?cliente_id=' + globalCliente_id + "&" +
                    'user_id=' + user_id + "&" + 'textarea=' + textarea,
                    dataType : "json",
                    success : function(json) {
                        console.log(json)
                        document.getElementById("textarea").value = "";
                        refreshfunctionComentario()
                    }
                });
            } else alert("Debe agregar una nota")

        }

        function refreshfunctionComentario(){
            var table = $("#comentarios");
            table.children().remove()
            table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
            $.ajax({
                url: '/api/registrosllamadas?cliente_id=' + globalCliente_id,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['nombre']+"</td><td>"+json['comentario']+
                                "</td><td>"+json['fecha']+"</td>"+ "</tr>");
                    });
                }
            });
        }
        function cerrar(){
            // Get the modal
            var modalComentario = document.getElementById('myModalComentarios');
            // When the user clicks on <span> (x), close the modal
            modalComentario.style.display = "none";
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalComentario) {
                    modalComentario.style.display = "none";
                }
            }
            document.getElementById("textarea").value = "";
        }
    </script>
    <body>
    <style type="text/css">
        #piechart_3d{
        }
        #textarea{
            width: 100%;
        }
        @-webkit-keyframes greenPulse {
            from { background-color: #749a02; -webkit-box-shadow: 0 0 9px #333; }
            50% { background-color: #91bd09; -webkit-box-shadow: 0 0 18px #91bd09; }
            to { background-color: #749a02; -webkit-box-shadow: 0 0 9px #333; }
        }
        #btnConLlamados {
            -webkit-animation-name: greenPulse;
            -webkit-animation-duration: 2s;
            -webkit-animation-iteration-count: infinite;
        }
    </style>
    </body>

@stop