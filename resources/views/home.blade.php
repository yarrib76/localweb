@if (Auth::user()->id_roles == 2){
    <meta http-equiv="Refresh" content="0; url='/notasadhesivas'" />
@else
@extends('layouts.master')
@section('contenido')

<div class="container">
	<div class="row">
		<div class="col-md-1100 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading">Dashboard</div>
                    <table id="home" class="table table-striped table-bordered records_list">
                        <tr>
                            <th>
                                <div class="panel-body">
                                    <iframe src="/dashboard" width="1090" height="750" frameborder="2"></iframe>
                                </div>
                            </th>
                    </table>
			</div>
		</div>
	</div>
</div>
@endsection
@endif