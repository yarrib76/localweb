<div class="col-lg-15" style="margin-top:2px;">
    <div class="col-sm-8 col-sm-offset-3">
        <div class="col-sm-9">
            Cliente
            <input type="text" class="form-control" placeholder="Cliente" id="Cliente" name="Cliente" required="required" readonly>
            <input type="hidden" class="form-control" id="Cliente_id" name="Cliente_id">
        </div>
        <label id="agregar" class="btn btn-primary" onclick="buscarCliente();"><i class="fa fa-user"></i></label>

        <div class="col-sm-9">
            Fecha de Vencimiento
            <input type="date" class="form-control" placeholder="Fecha Vencimiento" name="FechaVencimiento" required="required">
        </div>
        <div class="col-sm-9">
            Codigo Autorizacion
            <input type="checkbox" name="CodManual" onchange="codigoManual(this, '{{$codAuto}}')" value="Bike">Codigo Manual<br>
            <input type="text" class="form-control" name="CodAuto" id="CodAuto" value="{{$codAuto}}" readonly >
        </div>
        <div class="col-sm-9">
            Promocion
            <textarea id="promocion" name="promocion" class="textarea is-warning" type="text" placeholder="Escriba la promocion" required="required" ></textarea>
        </div>
    </div>
</div>

<!-- The Modal Comentarios-->
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <table id="reporte" class="table table-striped table-bordered records_list">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Seleccionar</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{$cliente->nombre}},{{$cliente->apellido}}</td>
                    <td><label id="selecionCliente" class="btn btn-primary" onclick="seleccionarCliente('{{$cliente->nombre}}','{{$cliente->apellido}}','{{$cliente->id_clientes}}');"><i class="fa fa-check"></i></label></td>
                </tr>
            @endforeach
            </tbody>
        </table>
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
        width: 50%;
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

    .textarea{
        width: 305px;
        height: 120px;
        border: 3px solid #cccccc;
        padding: 5px;
        font-family: Tahoma, sans-serif;
        background-position: bottom right;
        background-repeat: no-repeat;
        resize: none;
    }
</style>
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>

    <!-- DataTables -->

    <script type="text/javascript">
        // Get the modal
        var modal = document.getElementById('myModal');

        $(document).ready( function () {
            $('#reporte').DataTable({

                        "lengthMenu": [ [8,  16, 32, -1], [8, 16, 32, "Todos"] ],
                        language: {
                            search: "Buscar:",
                            "thousands": ",",
                            processing:     "Traitement en cours...",
                            lengthMenu:    "Mostrar _MENU_ clientes",
                            info:           "Mostrando del  _START_ al _END_ de _TOTAL_ clientes",
                            infoEmpty:      "0 moviles",
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
                        }
                    }

            );
        } );

        function buscarCliente(){
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
        function seleccionarCliente(nombre,apellido,cliente_id){
            document.getElementById("Cliente").value = nombre + "," + apellido;
            document.getElementById("Cliente_id").value = cliente_id;
            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];
            modal.style.display = "none";
        }

        function codigoManual(elemento, codAuto){
            if (elemento.checked){
                document.getElementById("CodAuto").value = "";
                document.getElementById("CodAuto").readOnly = false;
            }else {
                document.getElementById("CodAuto").value = codAuto;
                document.getElementById("CodAuto").readOnly = true;
            }
        }
    </script>
@stop
