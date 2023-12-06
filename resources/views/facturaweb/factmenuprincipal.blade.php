@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Menu Facturacion</i></div>
                        <div class="panel-body">
                            <button onclick="callFactura()">Nueva Factura</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@stop

@section('extra-javascript')
    <link rel="stylesheet" href="../../js/tabulador/tabulator.css">
    <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @include('facturaweb.factura')
    @include('facturaweb.listararticulos')

@stop
