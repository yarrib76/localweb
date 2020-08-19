@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Exportar Articulos Tienda Nube</i></div>
                </div>
                <div class="panel-body">
                    <div class="container">
                        <a href="{{ URL::to('downloadExcel/xls') }}"><button class="btn btn-success">Bajar Excel xls</button></a>
                        <a href="{{ URL::to('downloadExcel/xlsx') }}"><button class="btn btn-success">Bajar Excel xlsx</button></a>
                        <a href="{{ URL::to('downloadExcel/csv') }}"><button class="btn btn-success">Bajar CSV</button></a>
                    </div>
                </div>
              </div>
            </div>
        </div>
    <style>
        span h5 {
            color: #fff;
            display:table;
            margin:0 auto;
        }
    </style>
@stop