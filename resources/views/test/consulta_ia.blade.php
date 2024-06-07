@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Principal</i></div>
                        <div class="panel-body">
                            <button onclick="consulta_ia()">Llamo a Mia</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        /* The Modal (background) */
        #myModalChatIA {
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
        #modal-content-chatia {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 3px solid #888;
            width: 70%;
            height: 80%;
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
    </style>

    <div id="myModalChatIA" class="modal">
        <!-- Modal content -->
        <div id="modal-content-chatia" class="modal-content">
            <span class="close1">&times;</span>
            <h5 id="cliente"></h5>
            <div id="general">
                <div id="nuevomensajes">
                    <textarea id="textarea" class="textarea is-warning" type="text" placeholder="Escriba una nota"  rows="3" cols="125"></textarea>
                    <div id="botones">
                        <button id="agregar"  class="btn btn-primary" onclick="agregarNota();"><i class="fa fa-check"></i></button>
                        <button id="botoncerrar" class="btn btn-success" onclick="cerrar();"><i class="fa fa-close"></i></button>
                    </div>
                </div>
                <div id="mensajes">
                    <div class="col-xs-12 col-xs-offset-0 well">
                        <table id="chatia" class="table table table-scroll table-striped">
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
    <script src="https://cdn.jsdelivr.net/npm/luxon@2.4.0/build/global/luxon.min.js"></script>
    <script>
        var table = $("#chatia");
        function consulta_ia(){
            $("textarea").val("");
            table.children().remove()
            table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
            // Get the modal
            var modalChatIA = document.getElementById('myModalChatIA');

            // Get the <span> element that closes the modal
            var spanComentario = document.getElementsByClassName("close1")[0];

            // When the user clicks the button, open the modal
            modalChatIA.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            //spanComentario.onclick = function() {
            //    modalChatIA.style.display = "none";
            //}
           // $(".modal-content #cliente").html( "Cliente: " + cliente);
        }
        function agregarNota(){
            var textarea = $.trim($("textarea").val());
            if (textarea != ""){
                refreshfunctionComentario(textarea)
                $("textarea").val("");
            } else alert("Debe agregar una nota")
        }
        function refreshfunctionComentario(consulta){
            // let fechaActual = new Date();
            let fechaActual = luxon.DateTime.local();
            let horaFormateada = fechaActual.toFormat('HH:mm:ss');
            // Formatear la fecha en el formato deseado
            let fechaFormateada = fechaActual.toFormat('dd/MM/yyyy');
            table.append("<tr><td>"+"Yamil"+"</td><td>"+consulta+
                    "</td><td>"+fechaFormateada+ " " + horaFormateada +"</td>"+ "</tr>");
            $.ajax({
                url: '/testia?consultaHumana=' + consulta,
                dataType : "json",
                success: function(json) {
                    table.append("<tr><td>"+"Mia IA"+"</td><td>"+json+
                            "</td><td>"+fechaFormateada+ " " + horaFormateada +"</td>"+ "</tr>");
                },
            });
        }

        function cerrar(){
            // Get the modal
            var modalChatIA = document.getElementById('myModalChatIA');
            // When the user clicks on <span> (x), close the modal
            modalChatIA.style.display = "none";
        }

    </script>
@stop
