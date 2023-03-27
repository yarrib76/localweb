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
        <div class="col-sm-6">
            <input type="number" class="form-control" placeholder="Cantidad Clientes Por Vendedora" id="cant_clientes_vendedora" >
        </div>
        <input type="checkbox" id="estado" name="estado" value="estado">
        <label class="tab-one" for="tab-one"></label><br>
    </div>
    <div class="col-sm-8 col-sm-offset-5">
        <button id="botonComent" value="Comentario" class="btn btn-success" onclick="guardar()"> Guardar</button>
        <button id="botonComent" value="Comentario" class="btn btn-success" onclick="botonsalir()"> Salir</button>
    </div>
</div>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script>
    //Ejecuta cuando carga la pagina
    $(document).ready ( function(){
        //Defino el valor para el Select
        $('#nocompra_id').val("{{$parametros[0]->cant_meses_ult_compra}}")
        $('#fidelizacion_id').val("{{$parametros[0]->cant_meses_ult_fidelizacion}}")
        $('#cant_clientes_vendedora').val("{{$parametros[0]->cant_clientes_por_vendedora}}")
        document.getElementById('montoMinimo').value = "{{$parametros[0]->monto_minimo_promedio}}"
         if ("{{$parametros[0]->estado}}" === 1){
            $(".tab-one").text("Habilitado");
            document.getElementById("estado").checked = true;
        }else {
            $(".tab-one").text("Deshabilitado");
            document.getElementById("estado").checked = false;
        }

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
        var cant_clientes_vendedora = document.getElementById('cant_clientes_vendedora').value;
        var estado;
        if (document.getElementById("estado").checked == true){
            estado = 1;
        } else estado = 0

        datos = []
        datos[0]=opcionNoCompra;
        datos[1]=opcionNoFidel;
        datos[2]=montoMinimoPromedio;
        datos[3]=estado;
        datos[4]=cant_clientes_vendedora;
        console.log(datos)
        var datos =  JSON.stringify(datos)
        $.ajax({
            'url': "/clientesFidelizacion/guardarParametros",
            'method': 'post',
            data: {parametros: datos  },
            success: function (json) {
                alert("Los Parametros Fueron Guardados")
            }
        })
    }

    function botonsalir(){
        window.location.href = '/panelcontrol'
    }
</script>

