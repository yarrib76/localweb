<div class="col-lg-20" style="margin-top:2px;">
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="col-xs-2 col-sm-2 col-md-2 ">
            <h4>7798</h4>
        </div>
        <div class="col-xs-9 col-sm-9 col-md-9">
            <input type="text" class="form-control" placeholder="Numero de Articulo" name="Articulo" pattern="\d*" maxlength="8" minlength="8" required="required">
        </div>

        <div class="col-sm-9">
            <input type="text" class="form-control" placeholder="Detalle" name="Detalle" required="required">
        </div>

        <div class="col-sm-9">
            <input type="number" class="form-control" placeholder="Cantidad" name="Cantidad" min="-99999999" max="99999999" required="required">
            <input type="number" step="any" class="form-control" placeholder="Precio de Origen" name="PrecioOrigen" required="required">
            <input type="number" step="any" class="form-control" placeholder="Precio Convertido" name="PrecioConvertido" id="PrecioConvertido" required="required">
            <label>
                <input type="radio" name="Opciones" id="Dolares" value="opcion_dolares" checked>
                Dolares
            </label>
            <label>
                <input type="radio" name="Opciones" id="Pesos" value="opcion_pesos">
                Pesos
            </label>
            <label>
                <input type="radio" name="Opciones" id="Manual" value="opcion_manual">
                Manual
            </label>
        </div>
    </div>
    <div class="col-sm-9">
        <input type="number" step="any" class="form-control" placeholder="Precio Manual" name="Manual" id="InputManual" disabled="true" required="required">
        <input type="number" step="any" class="form-control" placeholder="Gastos" name="Gastos" id="Gastos" disabled="true" required="required">
        <input type="number" step="any" class="form-control" placeholder="Ganancia" name="Ganancia" id="Ganancia" disabled="true" required="required">

        <label>Proveedor</label>
        <select id="proveedores" class="form-control" name="proveedor_name" onchange="llenarForm(value)" ></select>
        <label>Pais</label>
        <input type="text" class="form-control" name="PaisProveedor" id="PaisProveedor" readonly>
        <label>Gastos</label>
        <input type="number" step="any" class="form-control" name="GastosProveedor" id="GastosProveedor" readonly>
        <label>Ganancia</label>
        <input type="number" step="any" class="form-control" name="GananciaProveedor" id="GananciaProveedor" readonly>
    </div>
    <div class="panel-heading">Numero De Orden: {{{$nroOrden}}}</div>
    @include('articulos.calculadora')

</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>
    $("#Dolares").click(function() {
        $("#PrecioConvertido").prop("disabled", false);
        $("#InputManual").prop("disabled", true);
        $("#Gastos").prop("disabled", true);
        $("#Ganancia").prop("disabled", true);
        $("#GastosProveedor").prop("disabled", false);
        $("#GananciaProveedor").prop("disabled", false);
    });
    $("#Pesos").click(function() {
        $("#PrecioConvertido").prop("disabled", false);
        $("#InputManual").prop("disabled", true);
        $("#Gastos").prop("disabled", true);
        $("#Ganancia").prop("disabled", true);
        $("#GastosProveedor").prop("disabled", false);
        $("#GananciaProveedor").prop("disabled", false);
    });
    $("#Manual").click(function() {
        $("#InputManual").prop("disabled", false);
        $("#Gastos").prop("disabled", false);
        $("#Ganancia").prop("disabled", false);
        $("#GastosProveedor").prop("disabled", true);
        $("#GananciaProveedor").prop("disabled", true);
        $("#PrecioConvertido").prop("disabled", true);
    });
</script>

<script>
    //Ejecuta cuando carga la pagina
    $(document).ready ( function(){
            //     category_id = $(this).val();
            //  $('#moviles').empty();
            $.ajax({
                type: 'get',
                url: '/api/proveedoresSelect',
                //    data: {radio_id:category_id , flota_id:flota_id},
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (datos, textStatus, jqXHR) {
                    $.each(datos, function (i, value) {
                        $('#proveedores').append("<option value='" + value['Nombre'] + "'>" + value['Nombre'] + '</option>');
                    }); // each
                    if (document.getElementById('Manual').checked){
                        document.getElementById('InputManual').disabled = false
                        document.getElementById('Gastos').disabled = false
                        document.getElementById('Ganancia').disabled = false
                        document.getElementById('PrecioConvertido').disabled = true
                    }
                    document.getElementById('PaisProveedor').setAttribute('value',datos[0]['Pais'])
                    document.getElementById('GastosProveedor').setAttribute('value',datos[0]['Gastos']);
                    document.getElementById('GananciaProveedor').setAttribute('value',datos[0]['Ganancia']);
                },
                error: function (datos) {
                    console.log("Este callback maneja los errores " + datos);
                }

            }); // ajax
        });

    function llenarForm (value){
        $.ajax({
            type: 'get',
            url: '/api/proveedoresSelect',
            data: {proveedor_name:value},
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (datos, textStatus, jqXHR) {
                $.each(datos, function (i, value) {
                    document.getElementById('PaisProveedor').setAttribute('value',value['Pais'])
                    document.getElementById('GastosProveedor').setAttribute('value',value['Gastos']);
                    document.getElementById('GananciaProveedor').setAttribute('value',datos[0]['Ganancia']);
                    //  $('#proveedores').append("<option value='" + value['Nombre'] + "'>" + value['Nombre'] + '</option>');
                }); // each

            },
            error: function (datos) {
                console.log("Este callback maneja los errores " + datos);
            }

        }); // ajax
    }
</script>