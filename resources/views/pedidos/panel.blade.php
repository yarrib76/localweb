@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Pedidos</i></div>
                    <div class="panel-body">
                        <div>
                            <div id="AlertDiv">
                                <a href="/facturados" target="_blank" class="button button1">Facturados</a>
                                <a href="/procesados" target="_blank" class="button button2">Procesando</a>
                                <a href="/empaquetados" target="_blank" class="button button3">Empaquetados</a>
                                <a href="/cancelados" target="_blank" class="button button4">Cancelados</a>
                                <a href="/todos" target="_blank" class="button button5">Todos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 16px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            cursor: pointer;
        }

        .button1 {
            background-color: white;
            color: black;
            border: 2px solid #4CAF50;
            padding: 30px 56px;
        }

        .button1:hover {
            background-color: #4CAF50;
            color: white;
        }

        .button2 {
            background-color: white;
            color: black;
            border: 2px solid #FFFF00;
            padding: 30px 54px;
        }

        .button2:hover {
            background-color: #FFFF00;
            color: black;
        }

        .button3 {
            background-color: white;
            color: black;
            border: 2px solid #0000FF;
            padding: 30px 42px;
        }

        .button3:hover {
            background-color: #0000FF;
            color: white;
        }

        .button4 {
            background-color: white;
            color: black;
            border: 2px solid #FF0000;
            padding: 30px 53px;
        }

        .button4:hover {background-color: #FF0000;}

        .button5 {
            background-color: white;
            color: black;
            border: 2px solid #555555;
            padding: 30px 76px;
        }

        .button5:hover {
            background-color: #555555;
            color: white;
        }
        #AlertDiv {
            margin: auto;
            left: 65px;
            width: 175px;
        }

    </style>
@stop
@section('extra-javascript')
    <script type="text/javascript">
    </script>
@stop