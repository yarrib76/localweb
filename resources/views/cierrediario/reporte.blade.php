@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog"> Cierre De Todas las Caja</i></div>
                    <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cierresDiarios as $cierreDiario)
                                    <tr>
                                        <td>{{$cierreDiario->Fecha}}</td>
                                        <td>{{$cierreDiario->Total}}</td>
                                        <td>{{$cierreDiario->Estado}}</td>
                                        <td>
                                            {!! Html::linkRoute('facturaWeb.index', 'Ver', ['fecha'=>$cierreDiario->Fecha] , ['class' => 'btn btn-primary'] ) !!}
                                            <button onclick="btnGastos('{{$cierreDiario->Fecha}}')" class="btn-info" id="btnGastos" >Gastos</button>
                                            @if ($cierreDiario->Estado == "Caja Abierta")
                                                <button onclick="cierreCaja('{{$cierreDiario->Fecha}}')" class="btn-primary" id="cierreCaja" >CerrarCaja</button>
                                            @else <button onclick="cierreCaja('{{$cierreDiario->Fecha}}')" class="btn-primary" id="cierreCaja" disabled = true >CerrarCaja</button>
                                            @endif
                                        </td>
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
        #myModalGastos {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 120%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        /* Modal Content */
        #modal-content-gastos {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 3px solid #888;
            width: 70%;
            height: 80%;
            overflow-y: auto;
        }
        /* The Close Button */
        #close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        #close:hover,
        #close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <div id="myModalGastos" class="modal">
        <!-- Modal content -->
        <div id="modal-content-gastos" class="modal-content">
            <span id="close">&times;</span>
            <h4 id="Fecha">Fecha:</h4>
            <div id="general">
                <div class="col-xs-12 col-xs-offset-0 well">
                    <table id="tabla_gastos" class="table table table-scroll table-striped">
                        <thead>
                            <tr>
                                <th>Gasto</th>
                                <th>Descripcion</th>
                                <th>Importe</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                    </table>
                </div>
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

        $(document).ready( function () {
            $('#reporte').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ],
                        order: [0,'desc']
                    }

            );
        } );

        function btnGastos(fecha){
            var table = $("#tabla_gastos");
            table.children().remove()
            table.append("<thead><tr><th>Gasto</th><th>Descripcion</th><th>Importe</th><th>Fecha</th></tr></thead>")
            $.ajax({
                url: 'api/listaGastosFecha?fecha=' + fecha,
                method: 'get',
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (json){
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['Nbr_Gasto']+"</td><td>"+json['Detalle']+
                                "</td><td>"+json['Importe']+"</td>"+"</td><td>"+json['Fecha']+"</td>"+ "</tr>");
                    });
                    // Get the modal
                    var modalGastos = document.getElementById('myModalGastos');

                    // Get the <span> element that closes the modal
                    var spanGastos = document.getElementById("close");

                    // When the user clicks the button, open the modal
                    modalGastos.style.display = "block";

                    // When the user clicks on <span> (x), close the modal
                    spanGastos.onclick = function() {
                        modalGastos.style.display = "none";
                    }

                    // When the user clicks anywhere outside of t he modal, close it
                    window.onclick = function(event) {
                        if (event.target == modalGastos) {
                            modalGastos.style.display = "none";
                        }
                    }
                    $(".modal-content #Fecha").html( "Fecha: " + fecha);
                },
                error: function(xhr, status, error){
                }
            })
        }

        function cierreCaja(fecha) {
            var resultado = confirm('Esta seguro que desea cerrar la caja con fecha: ' + fecha)
            if (resultado){
                $.ajax({
                    url: 'cierreCaja?fecha=' + fecha,
                    method: 'get',
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (json) {
                    },
                    error: function (xhr, status, error) {
                    }
                })
                var rows = document.getElementById('reporte').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                for (i = 0; i < rows.length; i++) {
                    rows[i].cells[3].addEventListener("click", function() {
                        if (event.target.id === 'cierreCaja') {
                            //Paso a la variable la fila seleccionada
                            posicionTable = this.parentNode.rowIndex;
                            console.log(posicionTable);
                            reporte.rows[posicionTable].cells[2].innerHTML = "Caja Cerrada";
                            event.target.disabled = true;
                        }

                    })
                }
                alert("Caja " + fecha + " Cerrada")
            }
        }
    </script>
@stop