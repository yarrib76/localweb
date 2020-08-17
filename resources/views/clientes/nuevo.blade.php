@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Alta de Cliente</i></div>
                        <div class="panel-body">
                            @include('errors.basic')
                            <form action='{{ route ('clientes.store')}}' method="post" >
                                @include('clientes.form')
                            <div class="col-sm-8 col-sm-offset-4">
                                <button type="submit" class="btn btn-primary" name="agregar"><i class="fa fa-btn fa-plus"></i> Agregar</button>
                                <a href="/clientes" type="submit" class="btn btn-primary" name="agregar"> Salir</a>
                            </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
@stop
