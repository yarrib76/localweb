$(document).ready(function() {
    $("#actividad").change(function(evento){
        category_id = $(this).val();
        $('#inquilinos').empty();
        $('#inquilinos').prop('disabled', false);
        $.ajax({
            type: 'get',
            url:  '/api/usuario_inquilinos',
            data: 'category_id='+category_id,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success:function(datos, textStatus, jqXHR){
                $.each(datos,function(i, value ){
                    $('#inquilinos').append('<option value=' + value['id']+ '>' + value['tipo']['name'] + '</option>');
                }); // each
                $('#inquilinos' ).change();
            },
            error:function(datos){
                alert("Este callback maneja los errores " + datos);
            }

        }); // ajax


    }); // change`
});