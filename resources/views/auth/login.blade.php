@extends('layouts.master')

@section('contenido')
<div class="container">
<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">Ingresar</div>
			<div class="panel-body">

				@include('errors.basic')

				<form class="form-horizontal" role="form" method="POST" action="/auth/login">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label for="correo" class="col-sm-3 control-label">Correo Electronico</label>
						<div class="col-sm-6">
							<input type="email" id="correo" name="email" class="form-control" placeholder="Correo" autocapitalize="off" value="{{ old('email') }}">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-3 control-label">Password</label>
						<div class="col-sm-6">
							<input type="password" name="password" class="form-control" placeholder="Password">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember"> Recordarme
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-3">
							<button type="submit" class="btn btn-primary" name="ingresar"><i class="fa fa-btn fa-sign-in" ></i>Ingresar</button>
						</div>
						<div class="col-sm-3">
							<div id="forgot-password-link" class="btn btn-link"><a href="/password/email">Olvide mi password</a></div>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>
</div>
@stop
