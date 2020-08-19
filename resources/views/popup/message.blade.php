@extends('layouts.master')
@section('contenido')

@stop
@section('extra-javascript')
    <script type="text/javascript">
        $(document).ready ( function() {
            window.alert("Proceso Finalizado")
            window.close();
        });
    </script>
@stop