@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Menu Facturacion</i></div>
                        <div class="panel-body">
                            <table style="margin: 0 auto;">
                                <tr>
                                    @if ($control == 'Autorizado')
                                        <td style="text-align: center; padding-right: 20px;">
                                            <button onclick="callFactura()" class="fa fa-calculator" id="btnFactura">Factura</button>
                                        </td>
                                    @endif
                                    <td style="text-align: center; padding-left: 20px;">
                                        <button onclick="window.location.href = '/pedidoWeb'" class="fa fa-book" id="btnPedidos">Pedidos</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #btnFactura {
            width: 100px; /* Ajusta el tamaño según lo necesario */
            height: 100px; /* Ajusta el tamaño según lo necesario */
            border-radius: 30%;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Agrega una sombra para el efecto 3D */
            background-color: #18af85; /* Color verde para el botón */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #btnPedidos {
            width: 100px; /* Ajusta el tamaño según lo necesario */
            height: 100px; /* Ajusta el tamaño según lo necesario */
            border-radius: 30%;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Agrega una sombra para el efecto 3D */
            background-color: #077aaf; /* Color verde para el botón */
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>


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
    @include('facturaweb.formcliente')
    @include('facturaweb.calculadora')

    <script>

    </script>

@stop
