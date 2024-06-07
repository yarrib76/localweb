 <div id="myModalChatIA" class="modal">
        <!-- Modal content -->
     <div id="modal-content-chatia" class="modal-content">
            <h5 id="nombreCliente"></h5>
            <div id="general">
                <div id="nuevomensajeIA">
                    <textarea id="textareaIA" class="textarea is-warning" type="text" placeholder="Escriba una nota"  rows="3" cols="125"></textarea>
                    <div id="botones">
                        <button id="agregarIA"  class="btn btn-primary" onclick="agregarChatIA();"><i class="fa fa-check"></i></button>
                        <button id="botoncerrarIA" class="btn btn-success" onclick="cerrarChatIA();"><i class="fa fa-close"></i></button>
                    </div>
                </div>
                <div id="mensajes">
                    <div class="col-xs-12 col-xs-offset-0 well">
                        <table id="chatia" class="table table table-scroll table-striped";>
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
         height: 90%; /* Full height */
         overflow-y: auto;
     }
     #textareaIA{
         width: 470px;
         height: 120px;
         border: 3px solid #cccccc;
         padding: 5px;
         font-family: Tahoma, sans-serif;
         background-position: bottom right;
         background-repeat: no-repeat;
         resize: none;
     }

 </style>

    <script src="https://cdn.jsdelivr.net/npm/luxon@2.4.0/build/global/luxon.min.js"></script>
    <script>
        var global_id_cliente;
        var textareaIA = document.getElementById('textareaIA')
        var table = $("#chatia");
        function consulta_ia(id_cliente,nombreCliente, apellidoCliente){
            global_id_cliente = id_cliente
            textareaIA.value = "";
            table.children().remove()
            table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
            // Get the modal
            var modalChatIA = document.getElementById('myModalChatIA');

            // When the user clicks the button, open the modal
            modalChatIA.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            //spanComentario.onclick = function() {
            //    modalChatIA.style.display = "none";
            //}
            $(".modal-content #nombreCliente").html( "Cliente: " + nombreCliente + ", " + apellidoCliente);

        }
        function agregarChatIA(){
            if (textareaIA.value != ""){
                refreshfunctionComentarioIA(textareaIA.value)
                textareaIA.value = "";
            } else alert("Debe agregar una notas")
        }
        function refreshfunctionComentarioIA(consulta){
            // let fechaActual = new Date();
            let fechaActual = luxon.DateTime.local();
            let horaFormateada = fechaActual.toFormat('HH:mm:ss');
            // Formatear la fecha en el formato deseado
            let fechaFormateada = fechaActual.toFormat('dd/MM/yyyy');
            table.append("<tr><td>"+"Yamil"+"</td><td>"+consulta+
                    "</td><td>"+fechaFormateada+ " " + horaFormateada +"</td>"+ "</tr>");
            $.ajax({
                url: '/testia?consultaHumana=' + consulta + "&cliente_id=" + global_id_cliente,
                dataType : "json",
                success: function(json) {
                    table.append("<tr><td>"+"Mia IA"+"</td><td>"+json+
                            "</td><td>"+fechaFormateada+ " " + horaFormateada +"</td>"+ "</tr>");
                },
            });
        }

        function cerrarChatIA(){
            // Get the modal
            var modalChatIA = document.getElementById('myModalChatIA');
            // When the user clicks on <span> (x), close the modal
            modalChatIA.style.display = "none";
        }

    </script>

