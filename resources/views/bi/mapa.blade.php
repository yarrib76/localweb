@extends('layouts.master')
@section('contenido')

    <div class="container">

        <h1>Facturacion {{$año}}</h1>
        <input type="text" id="fecha" name="fecha" value="{{$año}} " />
        <select name="listaFecha" onChange="combo(this, 'fecha')">
            <option value="2016">2013</option>
            <option value="2016">2014</option>
            <option value="2016">2015</option>
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
        <div class="mapcontainer">
            <div class="map">
            </div>
            <div class="areaLegend">
            </div>
        </div>
        <table id="reporte" class="table table-striped table-bordered records_list">
            <thead>
            <tr>
                <th>Provincia</th>
                <th>Porcentaje</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datos as $dato)
                <tr>
                    <td>{{$dato->Provincia}}</td>
                    <td>{{$dato->Porcentaje}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h4>Nº Pedido: </h4>
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="clientes" class="table table table-scroll table-striped">
                    <thead>
                    <tr>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <style type="text/css">
        body {
            color: #5d5d5d;
            font-family: Helvetica, Arial, sans-serif;
        }
        h1 {
            font-size: 30px;
            margin: auto;
            margin-top: 50px;
        }
        .container {
            max-width: 360px;
            margin: auto;
        }
        /* Specific mapael css class are below
         * 'mapael' class is added by plugin
        */
        .mapael .map {
            position: relative;
        }
        .mapael .mapTooltip {
            position: absolute;
            background-color: #fff;
            moz-opacity: 0.70;
            opacity: 0.70;
            filter: alpha(opacity=70);
            border-radius: 10px;
            padding: 10px;
            z-index: 1000;
            max-width: 200px;
            display: none;
            color: #343434;
        }
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
            width: 70%;
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

    </style>

@stop
@section('extra-javascript')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js" charset="utf-8"></script>
    <script src="../../js/jquery.mapael.js" charset="utf-8"></script>
    <script src="../../js/maps/argentina.js" charset="utf-8"></script>

    <!-- DataTables-->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <!-- DataTables -->

    <script type="text/javascript">

        var jsonFinal = []
        var $año = document.getElementById('fecha').value
        $(document).ready( function () {
            $.ajax({
                url: '/mapadatos?año=' + $año,
                dataType: "json",
                success: function (json) {
                    //Creo el JSON con la funcion ya que desde PHP no la puedo enviar
                    for (let i in json){
                        jsonFinal[i] = {
                            value: json[i]['value'],
                            tooltip: {content: json[i]['tooltip']['content']},
                            eventHandlers: { click: function (){consultaProvincia(json[i]['id_provincia'])}},
                            };
                    }
                    cargaMapa(jsonFinal)
                }
            })
            $('#reporte').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ],
                        order: [1,'desc']
                    }

            );
        });
        function cargaMapa (json) {
          //  json1 = [{"chubut":{ "value": "3000", eventHandlers: { click: function (){consultaProvincia()}},"tooltip":{"content": "Facturacion 3000"}}}];
            $(function () {
                $(".mapcontainer").mapael({
                    map: {
                        name: "argentina",
                        defaultArea: {
                            attrs: {
                                stroke: "#fff",
                                "stroke-width": 1
                            },
                            attrsHover: {
                                "stroke-width": 2
                            }
                        }
                    },
                    legend: {
                        area: {
                            title: "",
                            slices: [
                                {
                                    min: 1,
                                    attrs: {
                                        fill: "#97e766"
                                    },
                                    label: "Facturacion en la Region"
                                },
                            ]
                        }
                    },

                areas: json
                });
            });

        };
        function consultaProvincia(provincia_id){
            //Aca tengo que hacer las consulta por ajax de los clientes que compraron por provincia
            var table = $("#clientes");
            table.children().remove()
            table.append("<thead><tr><th>Cliente</th><th>Total</th><th>Localidad</th></tr></thead>")
            table.append("<tbody>")
            $.ajax({
                url: '/rankclientes?provincia_id=' + provincia_id + '&año=' + document.getElementById('fecha').value,
                dataType : "json",
                success : function(json) {
                    var nombreProvincia = json[0]['Provincia']
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['Cliente']+"</td><td>"+json['Total']+
                                "</td><td>"+json['Localidad']+"</td>"+ "</tr>");
                    });
                    table.append("</tbody>")
                    $(".modal-content h4").html( nombreProvincia);
                }
            });
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

        function combo(listaFecha, fecha) {
            fecha = document.getElementById(fecha);
            var idx = listaFecha.selectedIndex;
            var content = listaFecha.options[idx].innerHTML;
            fecha.value = content;
            window.location.replace("../mapa?año=" + fecha.value);
        }
    </script>

@stop