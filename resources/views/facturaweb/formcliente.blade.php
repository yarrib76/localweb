<div id="myModalClienteNuevo" class="modal">
    <!-- Modal Ingreso -->
    <div id="modal-content-cliente-nuevo" class="modal-content">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <span id="close-cliente-nuevo" class="close">&times;</span>

                    <div class="panel-heading"><i class="fa fa-cog">Nuevo Cliente</i></div>
                    <div class="panel-body">
                        <div class="col-lg-15" style="margin-top:2px;">
                            <div class="col-sm-8 col-sm-offset-3">
                                <h5 id="errorMail" style="display: none; color: red;">
                                    El mail ya existe en el sistema
                                </h5>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Nombre" name="Nombre" required="required">
                                </div>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Apellido" name="Apellido" required="required">
                                </div>

                                <div class="col-sm-9">
                                    <input type="number" class="form-control" placeholder="Cuit" name="Cuit" >
                                </div>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Direccion" name="Direccion">
                                </div>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Localidad" name="Localidad">
                                </div>

                                <div class="col-sm-9">
                                    <label>Provincia</label>
                                    <select id="provincia" class="form-control" name="Provincia_id" ></select>
                                </div>

                                <div class="col-sm-9">
                                    <input type="number" class="form-control" placeholder="Codigo Postal" name="codigo_postal" required="required">
                                </div>

                                <div class="col-sm-9">
                                    <input type="email" class="form-control" placeholder="Mail" name="Mail" required="required">
                                </div>

                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Telefono" name="Telefono">
                                </div>

                                <div class="col-sm-9">
                                    <label>Encuesta</label>
                                    <select id="encuesta_id" class="form-control" name="Encuesta" >
                                        <option value="Ninguna">Ninguna</option>
                                        <option value="Google">Google</option>
                                        <option value="Instagram">Instagram</option>
                                        <option value="FaceBook">FaceBook</option>
                                        <option value="TikTok">TikTok</option>
                                        <option value="Reels(Tips a la Vista)">Reels(Tips a la Vista)</option>
                                        <option value="Recomendado">Recomendado</option>
                                        <option value="Volante">Volante</option>
                                        <option value="Caminando">Caminando</option>
                                        <option value="Vivo">Vivo</option>
                                        <option value="No Responde">No Responde</option>
                                    </select>
                                </div>
                                <div>
                                    <button class="btn-info" onclick="cuardarCliente()">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<style>
    #myModalClienteNuevo {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 50px; /* Location of the box */
        left: 0;
        top: -5%;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }
    /* Modal Content */
    #modal-content-cliente-nuevo {
        background-color: rgba(243, 255, 242, 0.91);
        margin: auto;
        padding: 100px;
        border: 1px solid #888;
        width: 100%;
        height: 100%;
        overflow-y: auto;
    }

    /* The Close Button */
    #close-cliente-nuevo {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    #close-cliente-nuevo:hover,
    #close-cliente-nuevo:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    var modalClienteNuevo = document.getElementById('myModalClienteNuevo');
    // Get the <span> element that closes the modal
    var spanClienteNuevo = document.getElementById("close-cliente-nuevo");
    function cargoModalClienteNuevo(){
        limpiezaCampos()
        cargaComboProvincias()
        // When the user clicks the button, open the modal
        modalClienteNuevo.style.display = "block";

        // When the user clicks on <span> (x), close the modal
        spanClienteNuevo .onclick = function() {
            limpiezaCampos()
            modalClienteNuevo.style.display = "none";
        }
    }
    function cargaComboProvincias(){
        $('#provincia').empty(); // limpia el select
        $.ajax({
            type: 'get',
            url: '/api/provinciasSelect',
            //    data: {radio_id:category_id , flota_id:flota_id},
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (datos, textStatus, jqXHR) {
                $.each(datos, function (i, value) {
                    $('#provincia').append("<option value='" + value['id'] + "'>" + value['nombre'] + '</option>');
                }); // each
            },
            error: function (datos) {
                console.log("Este callback maneja los errores " + datos);
            }

        }); // ajax
    }

    function cuardarCliente(){
        var datosCliente = {
            nombre: document.getElementsByName('Nombre')[0].value,
            apellido: document.getElementsByName('Apellido')[0].value,
            cuit: document.getElementsByName('Cuit')[0].value,
            direccion: document.getElementsByName('Direccion')[0].value,
            localidad: document.getElementsByName('Localidad')[0].value,
            provincia_id: document.getElementsByName('Provincia_id')[0].value,
            cod_postal: document.getElementsByName('codigo_postal')[0].value,
            mail: document.getElementsByName('Mail')[0].value,
            telefono: document.getElementsByName('Telefono')[0].value,
            encuesta: document.getElementsByName('Encuesta')[0].value,
        }
        console.log (datosCliente)
        respuesta = validaDatos(datosCliente)
        if (respuesta){
            $.ajax({
                url: "crearCliente",
                method: "post",
                data: datosCliente,
                success: function (json){
                    // Ocultamos el error por si estaba visible
                    $("#errorMail").hide();
                    alert("El Cliente Se Creo Con Exito")
                    limpiezaCampos()
                    //Vualve a carar el Tabulator clientes de archivo listaclientes.blade.php
                    getClientes()
                    modalClienteNuevo.style.display = "none";
                },
                error: function(xhr, status, error) {
                    let msg = "";
                    try {
                        let res = JSON.parse(xhr.responseText);
                        msg = res.error || error;
                    } catch(e) {
                        msg = error;
                    }

                    // Si el error es "El cliente ya existe"
                    if (msg.includes("ya existe")) {
                        $("#errorMail").show();
                    } else {
                        alert(msg);
                    }
                }
            })
        }
    }

    function validaDatos(datosCliente){
        var aprobado = true
        if (datosCliente['nombre'] == "") {
            alert("Debe Ingresar un Nombre")
            aprobado = false
        }
        if (datosCliente['apellido'] == "") {
            alert("Debe inggresar un Apellido")
            aprobado = false
        }

        if (datosCliente['mail'] == "") {
            alert("Debe inggresar un Mail")
            aprobado = false
        }

        return aprobado
    }

    function limpiezaCampos(){
        document.getElementsByName('Nombre')[0].value = ""
        document.getElementsByName('Apellido')[0].value = ""
        document.getElementsByName('Cuit')[0].value = ""
        document.getElementsByName('Direccion')[0].value = ""
        document.getElementsByName('Localidad')[0].value = ""
        document.getElementsByName('codigo_postal')[0].value = ""
        document.getElementsByName('Mail')[0].value = ""
        document.getElementsByName('Telefono')[0].value = ""
        document.getElementById("encuesta_id").value = "Ninguna";
        $("#errorMail").hide();
    }
</script>
