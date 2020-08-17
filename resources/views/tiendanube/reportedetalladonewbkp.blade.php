@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <!-- Verifica con que tienda tiene que sincronizar:
                    Demo Nacha = 972788
                    Samira SRL = 938857
                    Donatella = 963000
                    Viamore = 1043936
                    -->
                    <div class="panel-heading"><i>Corria en E-Comerce Nº {{$id_corrida}}, Proveedor: {{$proveedor}}, Nombre: {{$nombre_ejecutor}}</i>
                        <button class="btn btn-primary" onclick="volver()"><i class="fa fa-arrow-left"></i></button>
                        <!--Verifico en que local estoy para enviar el Codido correcto de Tienda Nube-->
                        @if (substr(Request::url('http://donatella.dyndns.org'),0,27) == 'http://donatella.dyndns.org')
                            <button class="btn btn-primary" onclick="sincro('963000',{{$id_corrida}})">Sincro</button>
                        @elseif (substr(Request::url('http://samirasrl.dyndns.org'),0,27) == 'http://samirasrl.dyndns.org')
                            <button class="btn btn-primary" onclick="sincro('938857',{{$id_corrida}})">Sincro</button>
                        @elseif (substr(Request::url('http://viamore.dyndns.org'),0,25) == 'http://viamore.dyndns.org')
                            <button class="btn btn-primary" onclick="sincro('1043936',{{$id_corrida}})">Sincro</button>
                        @elseif (substr(Request::url('http://dona.com'),0,15) == 'http://dona.com')
                            <button class="btn btn-primary" onclick="sincro('972788',{{$id_corrida}})">Sincro</button>
                        @endif
                    </div>
                        <div class="panel-body">
                        <table id="reporte" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Product_id</th>
                                <th>Articulo_id</th>
                                <th>Articulo</th>
                                <th>Status</th>
                                <th>Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($statusEcomerce as $statusEcomerces)
                                <tr>
                                    <td>{{$statusEcomerces->product_id}}</td>
                                    <td>{{$statusEcomerces->articulo_id}}</td>
                                    <td>{{$statusEcomerces->articulo}}</td>
                                    <td>{{$statusEcomerces->status}}</td>
                                    <td>{{$statusEcomerces->fecha}}</td>
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
        #myModal {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 11%;
            height: 20%;
            overflow-y: auto;
        }
        #myModalFinish {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 30%;
            height: 50%;
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

    <div id="myModalFinish" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="col-xs-12 col-xs-offset-0 well">
                <h3 align="center">Reporte</h3>
                <table id="pedidos" class="table table table-scroll table-striped">
                    <thead>
                    <tr>
                        <td><h4 id="ProcesadosOK"></h4></td>
                    </tr>
                    <tr>
                        <td><h4 id="ProcesadosError"></h4></td>
                    </tr>
                    <tr>
                        <td><h4 id="ProcesadosNoRequerido"></h4></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="button" value="Cerrar" class="btn btn-success" onclick="cerrarFinish()"></td>
                    </tr>
                    </thead>
                </table>
            </div>

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
    // Get the modal
    var modalFinish = document.getElementById('myModalFinish');
    var modal = document.getElementById('myModal');
    var modalError = document.getElementById('myModalError');
    $(document).ready( function () {
        $('#reporte').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'excel'
                    ]
                }

        );
    } );
    function sincro(store_id, id_corrida){
        // When the user clicks the button, open the modal
        modal.style.display = "block";
        console.log(store_id);
        $.ajax({
            url: '/api/tiendanubesincroArticulos?id_corrida=' + id_corrida + "&" + 'store_id=' + store_id,
            dataType : "json",
            success : function(json) {
                console.log(json[0]["No Requiere"])
                modal.style.display = "none";
                // When the finish process, open the modalFinish
                modalFinish.style.display = "block";
                $(".modal-content #ProcesadosOK").html( "Procesados OK: " + json[0]["OK"]);
                $(".modal-content #ProcesadosError").html( "Procesados Con Error: " + json[0]["Error"]);
                $(".modal-content #ProcesadosNoRequerido").html( "Sin Cambios: " + json[0]["No Requiere"]);
            },
            error: function (json) {
                //close the modal
                console.log(json)
                modal.style.display = "none";
                // When the finish process, open the modalError
                modalError.style.display = "block";
            }
        });
    }
    function volver(){
        window.history.back();
    }
    function cerrarFinish(){
        //close the modal
        modalFinish.style.display = "none";
        location.reload();
    }
    function cerrarError(){
        //close the modal
        modalError.style.display = "none";
        location.reload();
    }
</script>
@stop