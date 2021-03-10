@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-15">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i>Productividad</i></div>
                    <div class="col-sm-3">
                        Fecha Inicio
                        <input type="date" class="form-control" placeholder="Fecha" id="FechaInicio" required="required">
                    </div>
                    <div class="col-sm-3">
                        Fecha Fin
                        <input type="date" class="form-control" placeholder="Fecha" id="FechaFin" required="required">
                    </div>
                    <button onclick="verificar()" class="buttonViamore">Ejecutar</button>
                    <div class="col-sm-15">
                        <div class="col-sm-2">
                            Efectivo
                            <input type="number" id="Efectivo" class="form-control" name="Efectivo" disabled = true >
                        </div>
                        <div class="col-sm-2">
                            Pedidos Pendientes
                            <input type="number" id="MercadoPago" class="form-control" name="MercadoPago" disabled = true >
                        </div>
                        <div class="col-sm-2">
                            Dias De Trabajo
                            <input type="number" id="TransferenciaBco" class="form-control" name="TransferenciaBco" disabled = true >
                        </div>
                    </div>
                    <div class="panel-body">
                        <table id="reporteViamore" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Vendedora</th>
                                <th>Pedidos</th>
                                <th>Dias</th>
                                <th>Promedio Pedidos</th>
                                <th>Promedio Facturado</th>
                                <th>Promedio Articulos</th>
                            </tr>
                            </thead>
                            <tbody>
                            <td>Sin Informacion</td>
                            <td>Sin Informacion</td>
                            <td>Sin Informacion</td>
                            <td>Sin Informacion</td>
                            <td>Sin Informacion</td>
                            <td>Sin Informacion</td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <style>
        .buttonViamore {
            display: inline-block;
            border-radius: 4px;
            background-color: #5088f4;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 20px;
            padding: 20px;
            width: 120px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
        }
        .button span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }
        .button span:after {
            content: '\00bb';  /* Entidades CSS. Para usar entidades HTML, use &#8594;*/
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
        }
        .button:hover span {
            padding-right: 25px;
        }
        .button:hover span:after {
            opacity: 1;
            right: 0;
        }
        #PromedioTotal,#pedidosPedientes,#DiasDeTrabajo {
            width:100px;
            text-align: center;
            font-weight:bold;
        }
    </style>

@stop
@section('extra-javascript')
    <script type="text/javascript">

        $(document).ready( function () {
            $('#reporte').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'excel'
                        ],
                        order: [0,'desc']
                    }

            );
        } );
    </script>
@stop