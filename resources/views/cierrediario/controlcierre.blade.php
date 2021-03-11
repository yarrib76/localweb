@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Verificacion de Cierre Caja</i></div>
                    <div class="col-sm-3">
                        Fecha Inicio
                        <input type="date" class="form-control" placeholder="Fecha" id="Fecha" required="required">
                    </div>
                    <button onclick="cierreDiarios()" class="buttonViamore">Ejecutar</button>
                    <div class="panel-body">
                        <table id="reporteCierre" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Tipo Pago</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
    </div>
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
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <!-- DataTables -->
    <script type="text/javascript">
            var table;
            $(document).ready( function () {
                document.getElementById('Fecha').value = new Date().toISOString().slice(0, 10);
            })
            function cierreDiarios() {
                eliminarTabla()
                var fecha = document.getElementById("Fecha").value;
                $.ajax({
                    'url': "/controlcierreconsulta?fecha=" + fecha,
                    'method': "GET",
                    'contentType': 'application/json',
                    success: function (json) {
                        for (var i = 0, ien = json.length; i < ien; i++) {
                            json[i]['tipo_pago_imagen'] = "<img src=/refresh/" + json[i]['tipo_pago_imagen'] + " " + "height='50' width='50'" + ">"
                            json[i]['cantidad'] = "<a href='/controlcierrefactura?fecha=" + fecha + "&id_tipo_pago=" + json[i]['id_tipo_pago'] + " '>" + json[i]['cantidad']
                        }

                        table = $('#reporteCierre').DataTable({
                                    dom: 'Bfrtip',
                                    "autoWidth": false,
                                    buttons: [
                                        'excel'
                                    ],
                                    order: [0, 'desc'],
                                    "aaData": json,
                                    "columns": [
                                        {"data": "tipo_pago_imagen"},
                                        {"data": "cantidad"},
                                        {"data": "Total"}
                                    ]
                                }
                        );
                        //     modal.style.display = "none";
                    },
                })
        }
            function eliminarTabla(){
                if(typeof table != "undefined") {
                    table.destroy()
                }
            }
    </script>
@stop