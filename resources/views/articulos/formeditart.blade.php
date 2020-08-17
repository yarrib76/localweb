<div class="col-lg-20" style="margin-top:2px;">
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="col-xs-9 col-sm-9 col-md-9">
            <input type="text" class="form-control" placeholder="Numero de Articulo" name="Articulo" value="{{$articulo->Articulo}}" disabled="true">
        </div>

        <div class="col-sm-9">
            <input type="text" class="form-control" placeholder="Detalle" name="Detalle" value="{{$articulo->Detalle}}" required="required">
        </div>

        <div class="col-sm-9">
            <label>
                <input type="number" class="form-control" placeholder="Cantidad Actual" name="CantidadActual" value="{{$articulo->Cantidad}}" disabled="true">
                <input type="checkbox" name="RestaArti" id="Resta" value="1">
                Resta Articulo
            </label>
            <input type="number" class="form-control" placeholder="Cantidad" name="Cantidad" min="-99999999" max="99999999" >
            Precio Origen
            <input type="number" step="any" class="form-control" placeholder="Precio de Origen" value="{{$articulo->PrecioOrigen}}" name="PrecioOrigen" required="required">
            Precio Convertido
            @if($articulo->PrecioConvertido <> 0)
                <input type="number" step="any" class="form-control" placeholder="Precio Convertido" value="{{$articulo->PrecioConvertido}}" name="PrecioConvertido" id="PrecioConvertido" required="required">
            @else
                <input type="number" step="any" class="form-control" placeholder="Precio Convertido"  name="PrecioConvertido" id="PrecioConvertido" required="required">
            @endif
            @if ($articulo->Moneda == "uSs")
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
            @endif
            @if ($articulo->Moneda == "ARG")
                <label>
                    <input type="radio" name="Opciones" id="Dolares" value="opcion_dolares" >
                    Dolares
                </label>
                <label>
                    <input type="radio" name="Opciones" id="Pesos" value="opcion_pesos" checked>
                    Pesos
                </label>
                <label>
                    <input type="radio" name="Opciones" id="Manual" value="opcion_manual">
                    Manual
                </label>
            @endif
            @if (empty ($articulo->Moneda))
                <label>
                    <input type="radio" name="Opciones" id="Dolares" value="opcion_dolares" >
                    Dolares
                </label>
                <label>
                    <input type="radio" name="Opciones" id="Pesos" value="opcion_pesos" >
                    Pesos
                </label>
                <label>
                    <input type="radio" name="Opciones" id="Manual" value="opcion_manual" checked>
                    Manual
                </label>
            @endif
        </div>
    </div>
    <div class="col-sm-9">
        @if ($articulo->PrecioManual <> 0)
            <input type="number" step="any" class="form-control" placeholder="Precio Manual" value="{{$articulo->PrecioManual}}" name="Manual" id="InputManual" disabled="true" required="required">
        @else
            <input type="number" step="any" class="form-control" placeholder="Precio Manual" name="Manual" id="InputManual" disabled="true" required="required">
        @endif
        @if ($articulo->Gastos <> 0)
            <input type="number" step="any" class="form-control" placeholder="Gastos" value="{{$articulo->Gastos}}" name="Gastos" id="Gastos" disabled="true" required="required">
        @else
            <input type="number" step="any" class="form-control" placeholder="Gastos"  name="Gastos" id="Gastos" disabled="true" required="required">
        @endif
        @if ($articulo->Ganancia)
            <input type="number" step="any" class="form-control" placeholder="Ganancia" value="{{$articulo->Ganancia}}" name="Ganancia" id="Ganancia" disabled="true" required="required">
        @else
            <input type="number" step="any" class="form-control" placeholder="Ganancia"  name="Ganancia" id="Ganancia" disabled="true" required="required">
        @endif
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
    <h4>Observaciones</h4>
    <textarea name="txtobservaciones" id="txtobservaciones" class="textarea is-warning" type="text"  rows="5"></textarea>
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
        var proveedor = "{{$articulo->Proveedor}}"
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
                    //Selecciono en el combo como default el proveedor que tiene definido
                    $("#proveedores").val(proveedor);

                    if (document.getElementById('Manual').checked){
                        document.getElementById('InputManual').disabled = false
                        document.getElementById('Gastos').disabled = false
                        document.getElementById('Ganancia').disabled = false
                        document.getElementById('PrecioConvertido').disabled = true
                    }
                    llenarForm(proveedor)
                  //  document.getElementById('PaisProveedor').setAttribute('value',datos[0]['Pais'])
                  //  document.getElementById('GastosProveedor').setAttribute('value',datos[0]['Gastos']);
                  //  document.getElementById('GananciaProveedor').setAttribute('value',datos[0]['Ganancia']);
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