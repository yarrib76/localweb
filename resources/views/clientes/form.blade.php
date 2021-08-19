<div class="col-lg-15" style="margin-top:2px;">
    <div class="col-sm-8 col-sm-offset-3">
        <div class="col-sm-9">
            <input type="text" class="form-control" placeholder="Nombre" name="Nombre" required="required">
        </div>

        <div class="col-sm-9">
            <input type="text" class="form-control" placeholder="Apellido" name="Apellido" required="required">
        </div>

        <div class="col-sm-9">
            <input type="text" class="form-control" placeholder="Apodo" name="Apodo">
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
                <option value="Recomendado">Recomendado</option>
                <option value="Volante">Volante</option>
                <option value="Caminando">Caminando</option>
                <option value="Vivo">Vivo</option>
            </select>
        </div>

    </div>

</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    //Ejecuta cuando carga la pagina
    $(document).ready ( function(){
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
    });
</script>
