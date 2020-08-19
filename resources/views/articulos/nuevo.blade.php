@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Crear Articulo</i></div>
                        <div class="panel-body">
                            @include('errors.basic')
                            <form action='{{ route ('articulos.store')}}' method="post" >
                                @include('articulos.form')
                            <div class="col-sm-offset-3 col-sm-3">
                                <input name="orden_compra" type="hidden" value={{{$nroOrden}}}>
                                <button type="submit" class="btn btn-primary" name="agregar"><i class="fa fa-btn fa-plus"></i> Agregar</button>
                            </div>
                                <div class="col-sm-offset-1 col-sm-3">
                                    <a href="/articulos" type="submit" class="btn btn-primary" name="agregar"> Salir</a>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
@stop
