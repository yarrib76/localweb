@extends('layouts.master')
@section('contenido')
    <div class="container">

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

    @include('pedidoweb.pedido')
    @include('facturaweb.listararticulos')
    @include('facturaweb.listaclientes')
    @include('facturaweb.listapedidos')
    <script>
        $(document).ready ( function(){
            callPedido()
        });
    </script>

@stop
