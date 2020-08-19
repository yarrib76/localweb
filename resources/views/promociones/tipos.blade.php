@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15 ">
                <div class="panel panel-primary">
                    <div class="panel-heading">Promociones {{$tipo}} para: {{$cliente}} </div>
                    <div class="panel-body">
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            {{$tipo}}
                            <tr>
                                <th>Nro Promocion</th>
                                <th>Fecha Creacion</th>
                                <th>Fecha Vencimiento</th>
                                <th>Promocion</th>
                                <th>Codigo Autorizacion</th>
                                @if($tipo === "finalizadas")
                                    <th>Factura</th>
                                @endif
                                @if($tipo === "totales")
                                    <th>Estado</th>
                                @endif
                                @if($tipo === "en espera" or $tipo === "activas" or $tipo === "totales")
                                    <th>Accion</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($promociones as $promocion)
                                <tr>
                                    <td>{{$promocion->Promocion_Id}}</td>
                                    <td>{{$promocion->FechaCreacion}}</td>
                                    <td>{{$promocion->FechaVencimiento}}</td>
                                    <td>{{$promocion->Detalle}}</td>
                                    <td>{{$promocion->CodAutorizacion}}</td>
                                    @if($tipo === "totales")
                                        @if($promocion->Estado === 1 )
                                            <td>En Espera</td>
                                        @endif
                                        @if($promocion->Estado === 2 )
                                            <td>Vencida/Activa</td>
                                        @endif
                                        @if($promocion->Estado === 3 )
                                            <td>Finalizada</td>
                                        @endif
                                    @endif
                                    @if($tipo === "finalizadas")
                                        <td>{{$promocion->NroFactura}}</td>
                                    @endif
                                    @if($tipo === "en espera")
                                        <td><input type="button" id="activar" value="Activar" class="btn btn-primary" onclick="activar({{$promocion->Promocion_Id}})"></td>
                                    @endif
                                    @if ($tipo === "activas")
                                        <td><input type="button" id="finalizar" value="Finalizar" class="btn btn-primary" onclick="finalizar({{$promocion->Promocion_Id}})"></td>
                                    @endif
                                    @if ($tipo === "totales")
                                        <td><input type="button" id="eliminar" value="Eliminar" class="btn btn-primary" onclick="eliminarPromo({{$promocion->Promocion_Id}})"></td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a href="/panelpromocion" type="submit" class="btn btn-primary" name="agregar"> Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal Comentarios-->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h4>Promocion</h4>
            <div class="col-xs-12 col-xs-offset-0 well">
                <label id="Factura"></label>
                <input id="factura" type="number" step="any" class="form-control" placeholder="NroFactura" name="factura">
            </div>
            <input type="button" id="finalizar" value="Finalizar" class="btn btn-success" onclick="guardarPromo();">
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
        var promo_id
        $(document).ready( function () {
            var table =  $('#reporte').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                                ]
                    }

            );
        } );

        function activar (promocion_id){
            $.ajax({
                url: 'activarpromocion?nropromocion=' + promocion_id,
                dataType: "json",
                success: function (json) {
                    location.reload();
                }
            });
        }

        function finalizar(promocion_id){
            // Get the modal
            var modal = document.getElementById('myModal');
            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal
            modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            $(".modal-content h4").html("Promocion Nº:" + promocion_id)
            promo_id = promocion_id
        }

        function guardarPromo(){
            if (document.getElementById("factura").value === ""){
                alert("Debe ingresar un numero de factura")
            } else {
                $.ajax({
                    url: 'finalizarpromocion?nropromocion=' + promo_id + '&&nrofactura=' + document.getElementById("factura").value,
                    dataType: "json",
                    success: function (json) {
                        location.reload();
                    }
                });
            }
        }
        function eliminarPromo(promocion_id){
            if (confirm("Esta seguro que quiere eliminar la promocion Nº " + promocion_id + "?")) {
                $.ajax({
                    url: 'eliminarpromocion?nropromocion=' + promocion_id,
                    dataType: "json",
                    success: function (json) {
                        location.reload();
                    }
                });
            }
        }
    </script>
@stop
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
        width: 20%;
        height: 40%;
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
        height: 120px;
    }
</style>