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
                <input type="number" class="form-control" placeholder="Monto Minimo De Compra" id="montoMinimo" >
            </div>
        <input type="checkbox" id="estado" name="estado" value="estado">
        <label class="tab-one" for="tab-one"></label><br>
    </div>
    <div class="col-sm-8 col-sm-offset-5">
        <button id="botonComent" value="Comentario" class="btn btn-success" onclick="guardar()"> Guardar</button>
        <button id="botonComent" value="Comentario" class="btn btn-success"> Salir</button>
    </div>
</div>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script>
    //Ejecuta cuando carga la pagina
    $(document).ready ( function(){
        //Defino el valor para el Select
        $('#nocompra_id').val("{{$parametros[0]->cant_meses_ult_compra}}")
        $('#fidelizacion_id').val("{{$parametros[0]->cant_meses_ult_fidelizacion}}")
        document.getElementById('montoMinimo').value = {{$parametros[0]->monto_minimo_promedio}}
         if ({{$parametros[0]->estado == 0}}){
            $(".tab-one").text("Habilitado");
            document.getElementById("estado").checked = true;
        }else {
            $(".tab-one").text("Deshabilitado");
            document.getElementById("estado").checked = false;
        }

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
        //Cambia el Texto del Label CheckBox
        $('#estado').on('change', function() {
            if ($(this).prop('checked')) {
                $(".tab-one").text("Habilitado");
            } else {
                $(".tab-one").text("Deshabilitado");
            }
        })
    });
    function guardar(){
        var selectNocompra = document.getElementById('nocompra_id');
        var selectNoFidel = document.getElementById('fidelizacion_id');
        var opcionNoCompra = selectNocompra.options[selectNocompra.selectedIndex].value;
        var opcionNoFidel = selectNoFidel.options[selectNoFidel.selectedIndex].value;
        var montoMinimoPromedio = document.getElementById('montoMinimo').value;
        var estado;
        if (document.getElementById("estado").checked == true){
            estado = 1;
        } else estado = 0
        var datos = '{"noCompra" : "' + opcionNoCompra + '",'
                +'"noFidel"  : "' + opcionNoFidel + '",'
                +'"montoMinimo" : "' + montoMinimoPromedio + '",'
                +'"estado" : "' + estado + '"} '
                ;
        console.log(datos)
        var datos =  JSON.stringify(datos)
        console.log(datos);
        $.ajax({
            'url': "/clientesFidelizacion/guardarParametros",
            'method': 'post',
            data: {parametros: datos  },
            success: function (json) {
                modal.style.display = "none";
            }
        })
    }

</script>

