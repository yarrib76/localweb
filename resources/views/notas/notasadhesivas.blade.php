@extends('layouts.master')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <ul id="notasUl">
                    <li id="notasLi">
                        <a id="notasA" href="#">
                            <h2 id="notasH2">Instagram</h2>
                            <p id="notasP">Revisar los mensajes y las publicaciones</p>
                        </a>
                    </li>
                    <li id="notasLi">
                        <a id="notasA" href="#">
                            <h2 id="notasH2">FaceBook</h2>
                            <p id="notasP">Revisar los mensajes pendientes</p>
                        </a>
                    </li>
                    <li id="notasLi">
                        <a id="notasA" href="#">
                            <h2 id="notasH2">Pagos Pendientes</h2>
                            <p id="notasP">Verificar que los pagos esten facturados</p>
                        </a>
                    </li>
                    <li id="notasLi">
                        <a id="notasA" href="#">
                            <h2 id="notasH2">Pedidos</h2>
                            <p id="notasP">Verificar si hay pedidos para imprimir / asignar</p>
                        </a>
                    </li>
                    <li id="notasLi">
                        <a id="notasA" href="#">
                            <h2 id="notasH2">Carrritos Abandonados</h2>
                            <p id="notasP">Verificar si hay carritos Abandonados</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <link href="http://fonts.googleapis.com/css?
family=Reenie+Beanie:regular"
          rel="stylesheet"
          type="text/css" >
    <style>
        *{
            margin:0;
            padding:0;
        }
        body{
            background:#666;
            color:#fff;
        }
        #notasH2,#notasP{
            font-size:100%;
            font-weight:normal;
        }
        #notasUl,#notasLi{
            list-style:none;
        }
        #notasUl{
            overflow:hidden;
            padding:3em;
        }
        #notasUl #notasLi:nth-child(even) #notasA{
            -o-transform:rotate(4deg);
            -webkit-transform:rotate(4deg);
            -moz-transform:rotate(4deg);
            position:relative;
            top:5px;
            background:#cfc;
        }
        #notasUl #notasLi:nth-child(3n) #notasA{
            -o-transform:rotate(-3deg);
            -webkit-transform:rotate(-3deg);
            -moz-transform:rotate(-3deg);
            position:relative;
            top:-5px;
            background:#ccf;
        }

        #notasUl #notasLi #notasA{
            text-decoration:none;
            color:#000;
            background:#ffc;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
            text-decoration:none;
            color:#000;
            background:#ffc;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
            /* Firefox */
            -moz-box-shadow:5px 5px 7px rgba(33,33,33,1);
            /* Safari+Chrome */
            -webkit-box-shadow: 5px 5px 7px rgba(33,33,33,.7);
            /* Opera */
            box-shadow: 5px 5px 7px rgba(33,33,33,.7);
            -webkit-transform:rotate(-6deg);
            -o-transform:rotate(-6deg);
            -moz-transform:rotate(-6deg);
        }
        #notasUl #notasLi #notasA:hover,#notasUl #notasLi #notasA:focus{
            -moz-box-shadow:10px 10px 7px rgba(0,0,0,.7);
            -webkit-box-shadow: 10px 10px 7px rgba(0,0,0,.7);
            box-shadow:10px 10px 7px rgba(0,0,0,.7);
            -webkit-transform: scale(1.25);
            -moz-transform: scale(1.25);
            -o-transform: scale(1.25);
            position:relative;
            z-index:5;
        }
        #notasUl #notasLi{
            margin:1em;
            float:left;
        }
        #notasUl #notasLi #notasH2{
            font-size:190%;
            font-weight:bold;
            padding-bottom:10px;
        }
        #notasUl #notasLi #notasA{
            font-family:"Reenie Beanie",arial,sans-serif;
            font-size:160%;
        }

    </style>

@stop