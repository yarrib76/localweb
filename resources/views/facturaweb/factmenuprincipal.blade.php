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
    <!-- <link rel="stylesheet" href="../../js/tabulador/tabulator.css">
    <script type="text/javascript" src="../../js/tabulador/tabulator.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../../js/tabulador/tabulator5-5-2min.css" rel="stylesheet">
    <script type="text/javascript" src="../../js/tabulador/tabulator5-5-2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>

    @include('facturaweb.factura')
    @include('facturaweb.listararticulos')
    @include('facturaweb.listaclientes')

    <script>

    </script>

@stop
