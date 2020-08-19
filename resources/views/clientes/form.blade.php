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
    </div>

</div>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
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
