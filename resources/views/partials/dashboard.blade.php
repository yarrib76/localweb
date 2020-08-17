@if ($membresias)
<h3>Mis Flotas:</h3>
@foreach ($membresias as $membresia)
<ul class="list-group">
    <li class="list-group-item">{!! HTML::linkRoute('flota.show', $membresia->flota()->nombre . " (" . $membresia->flota()->tipo() . ")", $membresia->flota()->slug ) !!}</li>
</ul>

@endforeach
{!! HTML::linkRoute('flota.create', ' Agregar otra flota', null, array('class' => 'btn btn-primary fa fa-plus') ) !!}


@else
    <h3>Todavia no ha creado ninguna flota.</h3>
    {!! HTML::linkRoute('flota.create', ' Crear', null, array('class' => 'btn btn-primary fa fa-plus') ) !!}
@endif

