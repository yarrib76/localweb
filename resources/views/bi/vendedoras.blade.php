@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Productividad</i></div>
                        <div class="col-sm-3">
                            Fecha Inicio
                            <input type="date" class="form-control" placeholder="Fecha" id="FechaInicio" required="required">
                        </div>
                        <div class="col-sm-3">
                            Fecha Fin
                            <input type="date" class="form-control" placeholder="Fecha" id="FechaFin" required="required">
                        </div>
                        <button onclick="verificar()" class="buttonViamore">Ejecutar</button>
                    <div class="col-sm-15">
                        <div class="col-sm-2">
                            Promedio Total
                            <input type="number" id="PromedioTotal" class="form-control" name="PromedioTotal" disabled = true >
                        </div>
                        <div class="col-sm-2">
                            Pedidos Pendientes
                            <input type="number" id="pedidosPedientes" class="form-control" name="pedidosPedientes" disabled = true >
                        </div>
                        <div class="col-sm-2">
                            Dias De Trabajo
                            <input type="number" id="DiasDeTrabajo" class="form-control" name="DiasDeTrabajo" disabled = true >
                        </div>
                    </div>
                    <div class="panel-body">
                            <table id="reporteViamore" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Vendedora</th>
                                    <th>Pedidos</th>
                                    <th>Dias</th>
                                    <th>Promedio Pedidos</th>
                                    <th>Promedio Facturado</th>
                                    <th>Promedio Articulos</th>
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
    var proveedor;
    fechaInicio = document.getElementById("FechaInicio").valueAsDate = new Date();
    fechaFin = document.getElementById("FechaFin").valueAsDate = new Date();
    //Asigno DataTable para que exista vacìa
    table1 =  $('#reporteViamore').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ],

            }
    );


    function verificar() {
     {
        // Get the modal
        var modal = document.getElementById('myModal');
        // When the user clicks the button, open the modal
        var fechaInicio = document.getElementById("FechaInicio").value;
        var fechaFin = document.getElementById("FechaFin").value;
        modal.style.display = "block";
        var table = $("#reporteViamore");
        var sumaPromedio = 0;
        table.children().remove()
        table.append("<thead><tr><th>Articulo</th><th>Detalle</th><th>TotalVendido</th><th>TotalStock</th><th>PrecioVenta</th></tr></thead>")
        table.append("<tbody>")
        $.ajax({
            url: '/api/vendedoras?'  + '&FechaDesde=' + fechaInicio
            + '&FechaHasta=' + fechaFin ,
            dataType: "json",
            success: function (json) {
                if (json[0] != "") {
                    $.each(json, function (index, json) {
                        table.append("<tr><td>" + json['ResulVendedora'] + "</td><td>"
                                + json['cantPedidos'] + "</td><td>"
                                + json['Dias'] + "</td><td>"
                                + json['Promedio'] + "</td><td>"
                                + json['PromedioFacturado'] + "</td><td>"
                                + json['PromedioCantArticulos'] + "</td>");
                        sumaPromedio = sumaPromedio + parseFloat(json['Promedio']);
                    });
                    table.append("</tr>")
                    table.append("</tbody>")
                    document.getElementById("PromedioTotal").value = sumaPromedio;
                    pedidosPendientes(sumaPromedio);
                    dataTable()
                    //close the modal
                    modal.style.display = "none";
                } else {
                    table.append("<tr><td>" + "Sin Informacion" + "</td><td>" + "Sin Informacion" + "</td><td>" + "Sin Informacion" + "</td>" + "</td></tr>");
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
    }
    function dataTable(){
        //Si exsiste la table1 la elimino para volver a crear con la nueva informacion
        table1.destroy()
        table1 =  $('#reporteViamore').DataTable({
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
    function pedidosPendientes(sumaPromedio){
        $.ajax({
            url: '/api/pedidosPendientes',
            dataType: "json",
            success: function (json) {
                document.getElementById("pedidosPedientes").value = json[0]['pedidosPendientes'];
                suma = json[0]['pedidosPendientes'] / sumaPromedio;
                document.getElementById("DiasDeTrabajo").value = suma.toFixed(1);
            }
        });

    }
</script>

    <style>
        .buttonViamore {
            display: inline-block;
            border-radius: 4px;
            background-color: #5088f4;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 20px;
            padding: 20px;
            width: 120px;
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
        #PromedioTotal,#pedidosPedientes,#DiasDeTrabajo {
            width:100px;
            text-align: center;
            font-weight:bold;
        }
    </style>
@stop