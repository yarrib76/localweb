@extends('layouts.master')
@section('contenido')
<div class="alert alert-danger">
		<strong>Ouhhh!</strong> El Articulo ya Existe<br><br>
</div>
<button class="btn btn-primary" class="fa fa-btn fa-plus" onclick="goBack()">Volver</button>

<script>
	function goBack() {
		window.history.back();
	}
</script>
@stop