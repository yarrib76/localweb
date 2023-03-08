<div class="col-lg-15" style="margin-top:2px;">
    <div class="col-sm-8 col-sm-offset-3">
        <div class="col-sm-4">
            <label>No Compra Hace Mas De (meses):</label>
            <select id="nocompra_id" class="form-control" name="Compra" >
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label>No Se Fideliza Hace Mas De (meses):</label>
            <select id="fidelizacion_id" class="form-control" name="Fidelizacion" >
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
        </div>

            <div class="col-sm-9">
                <input type="number" class="form-control" placeholder="Monto Minimo De Compra" name="montoMinimo" >
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
                //Selecciono en el combo como default la provincia que tiene definido
                $("#provincia").val(provincia_id);
               },
            error: function (datos) {
                console.log("Este callback maneja los errores " + datos);
            }

        }); // ajax
    });

</script>

