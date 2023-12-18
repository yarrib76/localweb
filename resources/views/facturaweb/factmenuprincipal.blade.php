@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Menu Facturacion</i></div>
                        <div class="panel-body">
                            @if ($control == 'Autorizado')
                                <button onclick="callFactura()">Nueva Factura</button>
                            @endif
                            <button onclick="window.location.href = '/pedidoWeb'">Pedidos</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@stop

@section('extra-javascript')

    <script src="../../js/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="../../js/tabulador/tabulator5-5-2min.css" rel="stylesheet">
    <script type="text/javascript" src="../../js/tabulador/tabulator5-5-2.min.js"></script>
    <script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="../../js/jspdf/jspdf.umd.min.js"></script>
    <script src="../../js/jspdf/jspdf.plugin.autotable.min.js"></script>

    @include('facturaweb.factura')
    @include('facturaweb.listararticulos')
    @include('facturaweb.listaclientes')
    @include('facturaweb.listapedidos')
    <script>

    </script>

@stop
