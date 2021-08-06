<div class="col-lg-15" style="margin-top:2px;">
    <div class="col-sm-8 col-sm-offset-3">
            <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="Nombre" value="{{$cliente->nombre}}" name="Nombre" required="required">
            </div>

            <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="Apellido" value="{{$cliente->apellido}}" name="Apellido" required="required">
            </div>

            <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="Apodo" value="{{$cliente->apodo}}" name="Apodo">
            </div>

            <div class="col-sm-9">
                <input type="number" class="form-control" placeholder="Cuit" value="{{$cliente->cuit}}" name="Cuit" >
            </div>

            <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="Direccion" value="{{$cliente->direccion}}" name="Direccion">
            </div>

            <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="Localidad" value="{{$cliente->localidad}}" name="Localidad">
            </div>
            <!--
            <div class="col-sm-9">
                <label>Provincia</label>
                <input type="text" class="form-control" placeholder="Provincia" value="{{$cliente->provincia}}" name="Provincia">
            </div>
            -->
            <div class="col-sm-9">
                <label>Provincia</label>
                <select id="provincia" class="form-control" name="Provincia_id" ></select>
            </div>
            <div class="col-sm-9">
                <input type="email" class="form-control" placeholder="Mail" name="Mail" value="{{$cliente->mail}}" required="required">
            </div>

            <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="Telefono" value="{{$cliente->telefono}}" name="Telefono">
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
                </select>
            </div>

        <input type="hidden" class="form-control" value="{{$cliente->id_clientes}}" name="id">

    </div>
</div>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script>
    //Ejecuta cuando carga la pagina
    $(document).ready ( function(){
        $('#encuesta_id').val("{{$cliente->encuesta}}")
        var provincia_id = "{{$cliente['provincias']->id}}"
        //     category_id = $(this).val();
        //  $('#moviles').empty();
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
                //Selecciono en el combo como default la provincia que tiene definido
                $("#provincia").val(provincia_id);
               },
            error: function (datos) {
                console.log("Este callback maneja los errores " + datos);
            }

        }); // ajax
    });

</script>

