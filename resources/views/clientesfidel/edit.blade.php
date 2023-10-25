@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Parametros</i></div>
                        <div class="panel-body">
                            @include('errors.basic')
                            @include('clientesfidel.formeditar')
                        </div>
                </div>
            </div>
        </div>
    </div>

@stop