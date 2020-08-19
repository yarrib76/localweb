@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Crear Promocion</i></div>
                        <div class="panel-body">
                            @include('errors.basic')
                            <form action='{{ route ('promocion.store')}}' method="post" >
                                @include('promociones.form')
                            <div class="col-sm-offset-4 col-sm-3">
                                <button type="submit" class="btn btn-primary" name="agregar"><i class="fa fa-btn fa-plus"></i> Agregar</button>
                            </div>
                                <div class="col-sm-offset-0 col-sm-3">
                                    <a href="/panelpromocion" type="submit" class="btn btn-primary" name="agregar"> Salir</a>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
@stop
