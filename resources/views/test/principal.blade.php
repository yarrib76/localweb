@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Principal</i></div>
                        <div class="panel-body">
                            <button onclick="cargoModalArticulos()">Llamo Modal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


@section('extra-javascript')

    <link rel="stylesheet" href="../../js/tabulador/tabulator5-5-2min.css" rel="stylesheet">
    <script type="text/javascript" src="../../js/tabulador/tabulator5-5-2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    @include('test.tabulatortest')
@stop
