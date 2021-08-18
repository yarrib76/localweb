@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Editar Cliente</i></div>
                        <div class="panel-body">
                            @include('errors.basic')

                            {!! Form::model($cliente,[ 'route' => ['clientes.update', 'id' => $cliente->id_clientes], 'method' => 'POST','class' => 'form-horizontal', 'files' => true]) !!}
                            @include('clientes.formeditar')
                                {!! csrf_field() !!}
                                <input name="_method" type="hidden" value="PATCH">
                            <div class="col-sm-8 col-sm-offset-4">
                                <button type="submit" class="btn btn-primary" name="modificar"><i class="fa fa-btn fa-plus"></i> Modificar</button>
                                <a href="javascript:window.open('','_self').close();" type="submit" class="btn btn-primary" name="salir"> Salir</a>
                            </div>
                            {!! Form::close() !!}
                        </div>
                </div>
            </div>
        </div>
    </div>

@stop