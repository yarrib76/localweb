@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><h4>Articulos Remanentes</h4></div>
                    <div class="panel-body">
                        <div class="col-xs-4 col-sm-4 col-md-10 ">
                            <div class="col-sm-3">
                                Fecha
                                <input type="date" class="form-control" placeholder="Fecha" id="Fecha" required="required">
                            </div>
                            <div class="col-lg-2">
                                Maximo Vendidos
                                <input type="number" step="any" class="form-control"  id="MaxVendido" required="required">
                            </div>
                            <div class="col-lg-2">
                                Minimo Stock
                                <input type="number" step="any" class="form-control"  id="MinStock" required="required">
                            </div>
                            <div class="col-lg-2">
                                Procesar Calculos
                                <td><label id="procesar" class="btn btn-primary" onclick="procesarCalculos();"><i class="fa fa-check"></i></label></td>
                            </div>
                        </div>
                        <table id="artremanentes" class="table table-striped table-bordered records_list">
                            <thead>

                            <tr>
                                <th>Articulo</th>
                                <th>Detalle</th>
                                <th>Vendido</th>
                                <th>Stock</th>
                                <th>Comprados</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
                                <td>Sin Informacion</td>
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
            table1 =  $('#artremanentes').DataTable({
                        buttons: [
                            'excel'
                        ],

                    }
            );
        } );
        function procesarCalculos(){
            var fecha = document.getElementById("Fecha").value;
            var maxVendido = document.getElementById("MaxVendido").value;
            var minStock = document.getElementById("MinStock").value;
            var table = $("#artremanentes");
            table.children().remove()
            table.append("<thead><tr><th>Articulo</th><th>Detalle</th><th>Vendidos</th><th>Stock</th><th>Comprados</th></tr></thead>")
            table.append("<tbody>")
            $.ajax({
                url: 'consultaartremanentes?fecha=' + fecha + '&maxVendidos=' + maxVendido + '&minStock=' + minStock ,
                dataType : "json",
                success : function(json) {
                    if (json[0] != ""){
                        $.each(json, function(index, json){
                            table.append("<tr><td>"+json['Articulo']+"</td><td>"+json['Detalle']+"</td><td>"+json['Vendidos']+"</td>" +
                                    "<td>"+json['Stock']+"</td><td>"+json['Comprados']+"</td></tr>");
                        });
                        table.append("</tbody>")
                        dataTable()
                    }else {
                        table.append("<tr><td>"+ "Sin Informacion" +"</td><td>"+"Sin Informacion"+"</td><td>"+"Sin Informacion"+"</td>" +
                                "<td>"+"Sin Informacion"+"</td><td>"+"Sin Informacion"+"</td></tr>");
                    }
                }
            });
        }
        function dataTable(){
            //Si exsiste la table1 la elimino para volver a crear con la nueva informacion
            table1.destroy()
            table1 =  $('#artremanentes').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                'excel'
                            ]
                        }
                );

        }

    </script>
@stop