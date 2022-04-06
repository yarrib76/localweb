@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div id="uno">
            <div class="panel-body">
                <table id="reporte" class="table table-striped table-bordered records_list">
                    <tbody>
                    <tr>
                        <td>
                            <div id = "RecuadroPanel" class="panel panel-primary">
                                <div id = "Empaquetados" class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-right">
                                            <div class="huge">
                                                <h4>Vencidos</h4> <h4>2</h4>
                                                <h4>Pendientes</h4> <h4>2</h4>
                                                <h4>SinTransporte</h4> <h4>2</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="jobs-wrapper">
                                    <a href="#">
                                        <div class="panel-footer" data-panel="job-details">
                                            <span class="pull-left">Empaquetados</span>
                                            <a href="/facturados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div id = "RecuadroPanel" class="panel panel-primary">
                                <div id = "CarritosAbandonados" class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-2 text-right">
                                            <div class="huge">
                                                <h4>SinAsignar</h4> <h4>2</h4>
                                                <h4>Pendientes</h4> <h4>5</h4>
                                                <h4>SinNotas</h4> <h4>2</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="jobs-wrapper">
                                    <a href="#">
                                        <div class="panel-footer" data-panel="job-details">
                                            <span class="pull-left">Carritos Abandonados</span>
                                            <a href="/facturados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div id = "RecuadroPanel" class="panel panel-primary">
                                <div id = "Facturados" class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-archive fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge">
                                                <h2>Prueba</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="jobs-wrapper">
                                    <a href="#">
                                        <div class="panel-footer" data-panel="job-details">
                                            <span class="pull-left">Empaquetados</span>
                                            <a href="/facturados" target="_blank"><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span></a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                     </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="dos">

        </div>
    </div>
    <style>
        #Empaquetados{
            background: #36ffb1;
            color: #fff;
            width: 278px;
        }
        #CarritosAbandonados{
            background: #28b3ff;
            color: #fff;
            width: 278px;
        }
        #jobs-wrapper {
            width: 278px;
        }
        #RecuadroPanel {
            width: 280px;
        }
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: silver;
        }
        #uno{ border:1px solid black;
            width:100%;
            display:inline-block;
            margin:auto;
            height:45.5%;
            background-color: #caffca;
        }
        #dos{ border:1px solid black;
            width:100%;
            display:inline-block;
            height:40.5%;
            background-color:green;
        }
        #tres{ border:1px solid black;
            width:49.5%;
            display:inline-block;
            height:49.5%;
            background-color:yellow;
        }
        #cuatro{ border:1px solid black;
            width:49.5%;
            display:inline-block;
            height:49.5%;
            background-color:red;
        }
    </style>
@stop
@section('extra-javascript')
    <script type="text/javascript">
        function refresh (){
            location.reload();
        }
    </script>

@stop