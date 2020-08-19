@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><h4>Seguimiento Clientes</h4></div>
                    <div class="panel-body">
                        <div class="col-xs-4 col-sm-4 col-md-10 ">
                            <div class="col-sm-3">
                                Fecha Inicio
                                <input type="date" class="form-control" placeholder="Fecha" id="FechaInicio" required="required">
                            </div>
                            <div class="col-sm-3">
                                Fecha Fin
                                <input type="date" class="form-control" placeholder="Fecha" id="FechaFin" required="required">
                            </div>
                            <div class="col-lg-2">
                                Procesar
                                <td><label id="procesar" class="btn btn-primary" onclick="procesarCalculos();"><i class="fa fa-check"></i></label></td>
                            </div>
                        </div>
                        <table id="seguimientoClientes" class="table table-striped table-bordered records_list">
                            <thead>

                            <tr>
                                <th>Cliente</th>
                                <th>Accion</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
                            </tr>
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
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 100%;
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

        .table-scroll tbody {
            position: absolute;
            overflow-y: scroll;
            height: 350px;
        }

        .table-scroll tr {
            width: 100%;
            table-layout: fixed;
            display: inline-table;
        }

        .table-scroll thead > tr > th {
            border: none;
        }

        #general{
            margin: auto;
            margin-top: 10px;
            width: auto;
            height: auto;
        }
        #mensajes{
            width: 550px;
            height: 300px;
        }
        #nuevomensajes{
            float: right;
            width: 300px;
            height: 300px;
        }
        .textarea{
            width: 300px;
            height: 120px;
            border: 3px solid #cccccc;
            padding: 5px;
            font-family: Tahoma, sans-serif;
            background-position: bottom right;
            background-repeat: no-repeat;
            resize: none;
        }
    </style>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Nº Cliente: </h3>
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="historial" class="table table table-scroll table-striped">
                    <thead>
                    <tr>

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
        var table1
        $(document).ready( function () {
            //Asigno DataTable para que exista vacìa
            table1 =  $('#seguimientoClientes').DataTable({
                        "lengthMenu": [ [7,  16, 32, -1], [8, 16, 32, "Todos"] ],
                        buttons: [
                            'excel'
                        ],

                    }
            );
            var date = new Date();

            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();

            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;

            var today = year + "-" + month + "-" + day;
            $("#FechaFin").attr("value", today);
        } );
        function procesarCalculos(){
            var fechaInicio = document.getElementById("FechaInicio").value;
            var fechaFin = document.getElementById("FechaFin").value;
            var table = $("#seguimientoClientes");
            table.children().remove()
            table.append("<thead><tr><th>Cliente</th><th>Accion</th></tr></thead>")
            table.append("<tbody>")
            $.ajax({
                url: '/api/biseguimiento?anioInicio=' + fechaInicio + '&anioFin=' + fechaFin ,
                dataType : "json",
                success : function(json) {
                    if (json[0] != ""){
                        $.each(json, function(index, json){
                            table.append("<tr><td>"+json['Cliente']+"</td><td><input type='button' value='Ver Historial' class='btn btn-success' onclick= historial(" + json['ID'] + ",'"+ json['Cliente'].replace(/\s/g, '') +"'"+")></td></tr>");
                        });
                        table.append("</tbody>")
                        dataTable()
                    }else {
                        table.append("<tr><td>"+ "Sin Informacion" +"</td><td>"+ "Sin Informacion" +"</td></tr>");
                    }
                }
            });
        }
        function dataTable(){
            //Si exsiste la table1 la elimino para volver a crear con la nueva informacion
            table1.destroy()
            table1 =  $('#seguimientoClientes').DataTable({
                        "lengthMenu": [ [7,  16, 32, -1], [8, 16, 32, "Todos"] ],
                            dom: 'Bfrtip',
                            buttons: [
                                'excel'
                            ]
                        }
                );

        }

        function historial(cliente_id, cliente){
            var table = $("#historial");
            table.children().remove()
            table.append("<thead><tr><th>Fecha</th><th>NroFactura</th><th>Total</th></tr></thead>")
            $.ajax({
                url: '/api/biseguimiento?cliente_id=' + cliente_id,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['Fecha']+"</td><td>"+json['NroFactura']+
                                "</td><td>"+json['Total']+"</td>></tr>");
                    });
                }
        })
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
            $(".modal-content h3").html("Cliente :" + cliente);
        };


    </script>
@stop