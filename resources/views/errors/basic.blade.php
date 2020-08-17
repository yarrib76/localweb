@if (count($errors) > 0)
<div class="alert alert-danger">
		<strong>Ouhhh!</strong> Hubo algun problema con los datos ingresados:<br><br>
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif
