<div class="col-lg-15" style="margin-top:2px;">
    <div class="col-sm-8 col-sm-offset-3">
            <div class="col-sm-9">
                <input type="text" class="form-control" placeholder="Nombre" value="{{$nota->titulo}}" name="Titulo" required="required">
            </div>

            <div class="col-sm-9">
                <textarea id="textarea" name = "body" class="textarea is-warning" type="text" placeholder="Escriba una nota"  rows="5"></textarea>

            </div>
            <div class="col-sm-9">
                <label>Roles</label>
                <select id="rolesWeb" class="form-control" name="id_rolesweb" ></select>
            </div>

        <input type="hidden" class="form-control" value="{{$nota->id_notas_adhesivas}}" name="id">

    </div>
</div>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script>
    //Ejecuta cuando carga la pagina
    $(document).ready ( function(){
        var provincia_id = "{{$nota->id_roles}}"
        //     category_id = $(this).val();
        //  $('#moviles').empty();
        $.ajax({
            type: 'get',
            url: '/api/relesWebSelect',
            //    data: {radio_id:category_id , flota_id:flota_id},
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (datos, textStatus, jqXHR) {
                $.each(datos, function (i, value) {
                    $('#rolesWeb').append("<option value='" + value['id_roles'] + "'>" + value['tipo_role'] + '</option>');
                }); // each
                //Selecciono en el combo como default la provincia que tiene definido
                $("#rolesWeb").val(provincia_id);
               },
            error: function (datos) {
                console.log("Este callback maneja los errores " + datos);
            }

        }); // ajax
        document.getElementById("textarea").value = "{{$nota->body}}";
    });

</script>

