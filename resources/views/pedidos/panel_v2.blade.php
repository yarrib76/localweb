@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Pedidos</i><button class="btn btn-primary" onclick="refresh()"><span class="glyphicon glyphicon-refresh"></span></button></div>
                        <div class="panel-body">
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr></tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6">
                                                <div id = "RecuadroPanel" class="panel panel-primary">
                                                    <div id = "Facturados" class="panel-heading">
                                                        <div class="row">
                                                            <div class="col-xs-3">
                                                                        <i class="fa fa-money fa-5x"></i>
                                                            </div>
                                                            <div class="col-xs-9 text-right">
                                                                <div class="huge">
                                                                    <h2><?php echo $facturados[0]->count ?></h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="jobs-wrapper">
                                                        <a href="#">
                                                            <div class="panel-footer" data-panel="job-details">
                                                                <span class="pull-left">Facturados</span>
                                                                <a href="/facturados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6">
                                                <div id = "RecuadroPanel" class="panel panel-primary">
                                                    <div id = "Proceso" class="panel-heading">
                                                        <div class="row">
                                                            <div class="col-xs-3">
                                                                <i class="fa fa-cogs fa-5x"></i>
                                                            </div>
                                                            <div class="col-xs-9 text-right">
                                                                <div class="huge">
                                                                    <h2><?php echo $procesos[0]->count ?></h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="jobs-wrapper">
                                                        <a href="#">
                                                            <div class="panel-footer" data-panel="job-details">
                                                                <span class="pull-left">En Proceso</span>
                                                                <a href="/procesados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6">
                                                <div id = "RecuadroPanel" class="panel panel-primary">
                                                    <div id = "PedidosPagos" class="panel-heading">
                                                        <div class="row">
                                                            <div class="col-xs-3">
                                                                <i class="fa fa-university fa-5x"></i>
                                                            </div>
                                                            <div class="col-xs-9 text-right">
                                                                <div class="huge">
                                                                    <h2><?php echo $pedidosPagados[0]->count ?></h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="jobs-wrapper">
                                                        <a href="#">
                                                            <div class="panel-footer" data-panel="job-details">
                                                                <span class="pull-left">Ya Estan Pagos</span>
                                                                <a href="/pedidospagos" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>    
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6">
                                                <div id = "RecuadroPanel" class="panel panel-primary">
                                                    <div id = "Empaquetados" class="panel-heading">
                                                        <div class="row">
                                                            <div class="col-xs-3">
                                                                <i class="fa fa-archive fa-5x"></i>
                                                            </div>
                                                            <div class="col-xs-9 text-right">
                                                                <div class="huge">
                                                                    <h2><?php echo $empaquetados[0]->count ?></h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="jobs-wrapper">
                                                        <a href="#">
                                                            <div class="panel-footer" data-panel="job-details">
                                                                <span class="pull-left">Empaquetados</span>
                                                                <a href="/empaquetados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6">
                                                <div id = "RecuadroPanel" class="panel panel-primary">
                                                    <div id = "Cancelados" class="panel-heading">
                                                        <div class="row">
                                                            <div class="col-xs-3">
                                                                <i class="fa fa-ban fa-5x"></i>
                                                            </div>
                                                            <div class="col-xs-9 text-right">
                                                                <div class="huge">
                                                                    <h2><?php echo $cancelados[0]->count ?></h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="jobs-wrapper">
                                                        <a href="#">
                                                            <div class="panel-footer" data-panel="job-details">
                                                                <span class="pull-left">Cancelados</span>
                                                                <a href="/cancelados"  target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6">
                                                <div id = "RecuadroPanel" class="panel panel-primary">
                                                    <div id = "Todos" class="panel-heading">
                                                        <div class="row">
                                                            <div class="col-xs-3">
                                                                <i class="fa fa-square fa-5x"></i>
                                                            </div>
                                                            <div class="col-xs-9 text-right">
                                                                <div class="huge">
                                                                    <h2><?php echo $todos[0]->count ?></h2>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="jobs-wrapper">
                                                        <a href="#">
                                                            <div class="panel-footer" data-panel="job-details">
                                                                <span class="pull-left">Todos</span>
                                                                <a href="/todos" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #Facturados{
            background: #36ffb1;
            color: #fff;
            width: 200px;
        }
        #Proceso{
            background: #ffe941;
            color: #fff;
            width: 200px;
        }
        #PedidosPagos {
            background: #400915;
            color: #fff;
            width: 200px;
        }
        #Empaquetados{
            background: #38d1ff;
            color: #fff;
            width: 200px;
        }
        #Cancelados{
            background: #ff445e;
            color: #fff;
            width: 200px;
        }
        #Todos{
            background: rgba(5, 10, 32, 0.91);
            color: #fff;
            width: 200px;
        }
        #jobs-wrapper {
            width: 200px;
        }
        #RecuadroPanel {
            width: 202px;
        }
    </style>
    @include('idle.pages')
@stop
@section('extra-javascript')
    <script type="text/javascript">
        function refresh (){
            location.reload();
        }
    </script>

@stop