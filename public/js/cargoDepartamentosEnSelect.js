$(document).ready(function() {
    $("#actividad").change(function(evento){
        category_id = $(this).val();
        $('#departamentos').empty();
        $('#departamentos').prop('disabled', false);
        $.ajax({
            type: 'get',
            url:  '/api/departamentos',
            data: 'category_id='+category_id,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success:function(datos, textStatus, jqXHR){
                $.each(datos,function(i, value ){
                    $('#departamentos').append('<option value=' + value['id']+ '>' + value['tipo']['name'] + '</option>');
                }); // each
                $('#departamentos' ).change();
            },
            error:function(datos){
                alert("Este callback maneja los errores " + datos);
            }

        }); // ajax


    }); // change`
});