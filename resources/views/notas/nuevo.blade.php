@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Crar Nota Adhesiva</i></div>
                        <div class="panel-body">
                            @include('errors.basic')
                            <form action='{{ route ('notasadmin.store')}}' method="post" >
                                @include('notas.form')
                            <div class="col-sm-8 col-sm-offset-4">
                                <button type="submit" class="btn btn-primary" name="agregar"><i class="fa fa-btn fa-plus"></i> Agregar</button>
                                <a href="/notasadmin" type="submit" class="btn btn-primary" name="agregar"> Salir</a>
                            </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
@stop
