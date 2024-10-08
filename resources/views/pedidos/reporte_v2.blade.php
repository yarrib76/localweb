@extends('layouts.master')
@section('contenido')

    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-cog">Pedidos  {{$estado}}</i><button class="btn btn-primary" onclick="refresh()"><span class="glyphicon glyphicon-refresh"></span></button>
                    </div>
                    <div class="panel-body">
                        <div>
                      <!--      Seleccionar Columnas :
                            <a class="toggle-vis" data-column="0">NroPedido</a> -
                            <a class="toggle-vis" data-column="1">Cliente</a> -
                            <a class="toggle-vis" data-column="2">Fecha</a> -
                            <a class="toggle-vis" data-column="3">Vendedora</a> -
                            <a class="toggle-vis" data-column="4">Factura</a> -
                            <a class="toggle-vis" data-column="5">Total</a> -
                            <a class="toggle-vis" data-column="6">OrdenWeb</a> -
                            <a class="toggle-vis" data-column="7">Estado</a>
                     !-->

                        </div>
                            <table id="reporte" class="table table-striped table-bordered records_list">
                                <thead>
                                <tr>
                                    <th>Pedido</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Vendedora</th>
                                    @if($estado != "Pagos")
                                        <th>Factura</th>
                                    @else
                                        <th>Fecha_Pago</th>
                                    @endif
                                    <th>Total</th>
                                    <th>OrdenWeb</th>
                                    <th>TotalWeb</th>
                                    <th>Transporte</th>
                                    <th>Instancia</th>
                                    <th>Estado</th>
                                    <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{$a = 1}}
                                @foreach($pedidos as $pedido)
                                    <tr>
                                        @if(!empty($pedido->cantArtiEnPedidos))
                                            <td><a href='/pedidoeficienteindex/?nroPedido={{$pedido->nropedido}}&vendedora={{$pedido->vendedora}}&cliente_nombre={{$pedido->nombre}}&cliente_apellido={{$pedido->apellido}}' class="badgeActivo" data-badge="{{$pedido->cantArtiEnPedidos}}"> {{$pedido->nropedido}}  </a></td><td><a onclick="encuesta('{{$pedido->id_clientes}}','{{$pedido->nombre}}','{{$pedido->apellido}}')">{{$pedido->nombre}}, {{$pedido->apellido}}</a></td>
                                        @else
                                            <td><a href='/pedidoeficienteindex/?nroPedido={{$pedido->nropedido}}&vendedora={{$pedido->vendedora}}&cliente_nombre={{$pedido->nombre}}&cliente_apellido?{{$pedido->apellido}}'> {{$pedido->nropedido}}  </a></td><td><a onclick="encuesta('{{$pedido->id_clientes}}','{{$pedido->nombre}}','{{$pedido->apellido}}')">{{$pedido->nombre}}, {{$pedido->apellido}}</a></td>
                                        @endif
                                        @if ($pedido->empaquetado == 1 and $pedido->estado <> 2)
                                            @if ($pedido->vencimiento == 2)
                                                <td bgcolor="#FF0000" data-order = "{{$pedido->fechaParaOrdenFact}}">{{$pedido->FechaFactura}}</td>
                                            @else
                                                <td data-order = "{{$pedido->fechaParaOrdenFact}}">{{$pedido->FechaFactura}}</td>
                                            @endif
                                        @else
                                            <td data-order = "{{$pedido->fechaParaOrden}}">{{$pedido->fecha}}</td>
                                        @endif
                                        <td>{{$pedido->vendedora}}</td>
                                        @if($estado != "Pagos")
                                            <td>{{$pedido->nrofactura}}</td>
                                        @else
                                             <td>{{$pedido->fecha_pago}}</td>
                                        @endif
                                        <td>{{$pedido->total}}</td>
                                        @if ($pedido->ordenweb != Null)
                                            <td>{{$pedido->ordenweb}}</td>
                                        @else
                                            <td>Sin Orden</td>
                                        @endif
                                        <td>{{$pedido->totalweb}}</td>
                                        <td>{{$pedido->transporte}}</td>
                                        @if ($pedido->instancia == 0)
                                            <td>Pendiente</td>
                                        @elseif($pedido->instancia == 1)
                                            <td>Iniciado</td>
                                        @elseif($pedido->instancia == 2)
                                            <td>Finalizado</td>
                                        @endif
                                        @if($pedido->estado == 0 and $pedido->empaquetado == 1)
                                            <td bgcolor="#87CEFA">Empaquetado</td>
                                            <td><button type="button" value="botonVer" class="btn btn-info" onclick="cargoTablaPopup({{$pedido->nropedido}});"><i class="fa fa-eye"></i></button>
                                                <button type="button" value="botoncancel" disabled class="btn btn-warning" onclick="calcelarPedido({{$pedido->nropedido}});" ><i class="fa fa-eraser"></i></button>
                                                <input type="button" value="Entregado" id="botonEntregado{{$a++}}" class="btn btn-primary" onclick="pedidoEntregado({{$pedido->nropedido}},{{$a - 1}});">
                                                <button id="boton{{$a}}" value="Agregar Transporte" class="btn btn-danger" onclick="modificoTransporte({{$pedido->nropedido}},'{{$pedido->transporte}}',{{$a - 1}});"><i class="fa fa-bus"></i></button>
                                                @if(!empty($pedido->comentarios))
                                                    <button id="botonComent" value="Comentario" class="btn btn-success" onclick="comentario({{$pedido->id}},'{{$pedido->nropedido}}','{{$pedido->nombre}}','{{$pedido->apellido}}');"><i class="fa fa-book"></i></button>
                                                @else
                                                    <button id="botonSinComent" value="Comentario" class="btn btn-success" onclick="comentario({{$pedido->id}},'{{$pedido->nropedido}}','{{$pedido->nombre}}','{{$pedido->apellido}}');"><i class="fa fa-book"></i></button>
                                                @endif
                                            </td>
                                        @elseif($pedido->estado == 0)
                                            <td bgcolor="#00FF00">Facturado</td>
                                            <td><button type="button" value="botonVer" class="btn btn-info" onclick="cargoTablaPopup({{$pedido->nropedido}});"><i class="fa fa-eye"></i></button>
                                            <button type="button" value="botoncancel"  disabled class="btn btn-warning" onclick="calcelarPedido({{$pedido->nropedido}});" ><i class="fa fa-eraser"></i></button>
                                                @if(!empty($pedido->comentarios))
                                                    <button id="botonComent" value="Comentario" class="btn btn-success" onclick="comentario({{$pedido->id}},'{{$pedido->nropedido}}','{{$pedido->nombre}}','{{$pedido->apellido}}');"><i class="fa fa-book"></i></button>
                                                @else
                                                    <button id="botonSinComent" value="Comentario" class="btn btn-success" onclick="comentario({{$pedido->id}},'{{$pedido->nropedido}}','{{$pedido->nombre}}','{{$pedido->apellido}}');"><i class="fa fa-book"></i></button>
                                                @endif
                                                <button type="button" id="botonEncuesta" class="btn btn-info" onclick="encuesta({{$pedido->nropedido}});"><i class="fa fa-facebook-square"></i></button>
                                            </td>
                                        @elseif($pedido->estado == 1)
                                            <td bgcolor="#FFFF00">Procesando</td>
                                                <td><button type="button" id="botonVer" class="btn btn-info" onclick="cargoTablaPopup({{$pedido->nropedido}});"><i class="fa fa-eye"></i></button>
                                                <button type="button" id="botonCancel" class="btn btn-warning" onclick="calcelarPedido({{$pedido->nropedido}});" ><i class="fa fa-eraser"></i></button>
                                                <button id="boton{{$a++}}" value="Agregar Transporte" class="btn btn-danger" onclick="modificoTransporte({{$pedido->nropedido}},'{{$pedido->transporte}}',{{$a - 1}});"><i class="fa fa-bus"></i></button>
                                            @if(!empty($pedido->comentarios))
                                                    <button id="botonComent" value="Comentario" class="btn btn-success" onclick="comentario({{$pedido->id}},'{{$pedido->nropedido}}','{{$pedido->nombre}}','{{$pedido->apellido}}');"><i class="fa fa-book"></i></button>
                                                @else
                                                    <button id="botonSinComent" value="Comentario" class="btn btn-success" onclick="comentario({{$pedido->id}},'{{$pedido->nropedido}}','{{$pedido->nombre}}','{{$pedido->apellido}}');"><i class="fa fa-book"></i></button>
                                                @endif
                                            @if($pedido->instancia == 0)
                                                <button type="button" id="botonInstancia{{$a}}" class="btn btn-info" onclick="cambioInstancia({{$pedido->nropedido}},1,{{$a}});">Inicio</button>
                                                <button type="button" id="botonInstanciaFin{{$a}}" style="display:none" class="btn btn-info" onclick="cambioInstancia({{$pedido->nropedido}},2,{{$a}});">Fin</button>
                                            @elseif($pedido->instancia == 1)
                                                 <button type="button" id="botonInstancia{{$a}}" class="btn btn-info" onclick="cambioInstancia({{$pedido->nropedido}},2,{{$a}});">Fin</button>
                                            @endif
                                            @if($pedido->pagado == 0)
                                                <button type="button" id="botonNoPago{{$a}}" class="btn btn-danger" onclick="cambioPago('{{$pedido->nropedido}}',0,'{{$a}}');"><i class="fa fa-frown-o"></i></button>
                                                <button type="button" id="botonPago{{$a}}" style="display:none" class="btn btn-success" onclick="cambioPago('{{$pedido->nropedido}}',1,'{{$a}}');"><i class="fa fa-smile-o"></i></button>
                                                @else
                                                        <button type="button" id="botonNoPago{{$a}}" style="display:none"  class="btn btn-danger" onclick="cambioPago('{{$pedido->nropedido}}',0,'{{$a}}');"><i class="fa fa-frown-o"></i></button>
                                                        <button type="button" id="botonPago{{$a}}" class="btn btn-success" onclick="cambioPago('{{$pedido->nropedido}}',1,'{{$a}}');"><i class="fa fa-smile-o"></i></button>
                                                @endif
                                                    <button id="botonCheckOut" value="CheckOut" class="btn btn-success" onclick="checkOut({{$pedido->id}},'{{$pedido->nropedido}}','{{$pedido->nombre}}','{{$pedido->apellido}}');"><i class="fa fa-check"></i></button>
                                                    <button class="btn btn-info" onclick="consulta_ia('{{$pedido->id}}','{{$user_id}}','{{$pedido->id_clientes}}','{{$pedido->nombre}}','{{$pedido->apellido}}')">IA</i></button>
                                                </td>
                                        @else
                                            <td bgcolor="#FF0000">Cancelado</td>
                                            <td><button type="button" id="botonVer" class="btn btn-info" onclick="cargoTablaPopup({{$pedido->nropedido}});"><i class="fa fa-eye"></i></button>
                                            <button type="button" id="botoncancel"  disabled class="btn btn-warning" onclick="calcelarPedido({{$pedido->nropedido}});" ><i class="fa fa-eraser"></i></button>
                                                @if(!empty($pedido->comentarios))
                                                    <button id="botonComent" value="Comentario" class="btn btn-success" onclick="comentario({{$pedido->id}},'{{$pedido->nropedido}}','{{$pedido->nombre}}','{{$pedido->apellido}}');"><i class="fa fa-book"></i></button>
                                                @else
                                                    <button id="botonSinComent" value="Comentario" class="btn btn-success" onclick="comentario({{$pedido->id}},'{{$pedido->nropedido}}','{{$pedido->nombre}}','{{$pedido->apellido}}');"><i class="fa fa-book"></i></button>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }


        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 100%;
            overflow-y: auto;
        }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .well {
            background: none;
            height: 420px;
        }

        .table-scroll tbody {
            position: absolute;
            overflow-y: scroll;
            height: 350px;
        }

        .table-scroll tr {
            table-layout: fixed;
            width: 100%;
            display: inline-table;
        }

        .table-scroll thead > tr > th {
            border: none;
        }

        #general{
            margin: auto;
            margin-top: 10px;
            width: auto;
            height: auto;
        }
        #mensajes{
            width: 550px;
            height: 300px;
        }
        #nuevomensajes{
            float: right;
            width: 300px;
            height: 300px;
        }
        .textarea{
            width: 300px;
            height: 120px;
            border: 3px solid #cccccc;
            padding: 5px;
            font-family: Tahoma, sans-serif;
            background-position: bottom right;
            background-repeat: no-repeat;
            resize: none;
        }
        .badgeActivo {
            position:relative;
        }
        .badgeActivo[data-badge]:after {
            content:attr(data-badge);
            position:absolute;
            top:0px;
            right:-40px;
            font-size:.7em;
            background:yellow;
            color:black;
            width:18px;height:18px;
            text-align:center;
            line-height:18px;
            border-radius:50%;
            box-shadow:0 0 1px #333;
        }
    </style>

    <style>
        @-webkit-keyframes greenPulse {
            from { background-color: #749a02; -webkit-box-shadow: 0 0 9px #333; }
            50% { background-color: #91bd09; -webkit-box-shadow: 0 0 18px #91bd09; }
            to { background-color: #749a02; -webkit-box-shadow: 0 0 9px #333; }
        }
        #botonComent {
            -webkit-animation-name: greenPulse;
            -webkit-animation-duration: 2s;
            -webkit-animation-iteration-count: infinite;
        }
    </style>
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        /* The Modal (background) */
        #myModalComentarios {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        /* Modal Content */
        #modal-content-comentarios {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 3px solid #888;
            width: 70%;
            overflow-y: auto;
        }
    </style>
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}

        /* The Modal (background) */
        #myModalTransporte {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }


        /* Modal Content */
        #modal-content-transporte {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            height: 35%;
            overflow-y: auto;
        }

        #myModalEncuesta {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }


        /* Modal Content */
        #modal-content-encuesta {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            height: 50%;
            overflow-y: auto;
        }

    </style>
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        /* The Modal (background) */
        #myModalCheckOut {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        /* Modal Content */
        #modal-content-checkOut {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 3px solid #888;
            width: 70%;
            overflow-y: auto;
        }
    </style>
    <!-- The Modal -->
    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Nº Pedido: </h3>
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="pedidos" class="table table table-scroll table-striped">
                    <thead>
                    <tr>

                    </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
    <!-- The Modal Comentarios-->
    <div id="myModalComentarios" class="modal">

        <!-- Modal content -->
        <div id="modal-content-comentarios" class="modal-content">
            <span class="close1">&times;</span>
            <h3>Nº Pedido: </h3>
            <h5 id="cliente"></h5>
            <div id="general">
                <div id="nuevomensajes">
                    <textarea id="textarea" class="textarea is-warning" type="text" placeholder="Escriba una nota" rows="10"></textarea>
                    <div id="botones">
                        <button id="agregar"  class="btn btn-primary" onclick="agregarNota({{$user_id}});"><i class="fa fa-check"></i></button>
                        <button id="botoncerrar" class="btn btn-success" onclick="cerrar();"><i class="fa fa-close"></i></button>
                    </div>
                </div>
                <div id="mensajes">
                    <div class="col-xs-12 col-xs-offset-0 well">
                        <table id="comentarios" class="table table table-scroll table-striped">
                            <thead>
                                <tr>

                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div id="myModalTransporte" class="modal">
        <!-- Modal content -->
        <div id="modal-content-transporte" class="modal-content">
            <span id="closeTransporte" class="close">&times;</span>
            <h4>Articulo</h4>
            <div class="col-xs-12 col-xs-offset-0 well-sm">
                <label id="Trans"></label>
                <!-- <input id="Transporte" type="text" step="any" class="form-control" placeholder="Ingrese Transporte" name="Transporte">  Se reemplaza por el Select-->
                <select id="Transporte_Select" type="text" step="any" class="form-control" placeholder="Ingrese Transporte" name="Transporte"></select>
            </div>
            <input type="button" id="guardar" value="Guardar" class="btn btn-success" onclick="guardarTransporte();">
        </div>

    </div>
    <div id="myModalEncuesta" class="modal">
        <!-- Modal content -->
        <div id="modal-content-encuesta" class="modal-content">
            <span id="closeEncuesta" class="close">&times;</span>
            <h4>Encuesta</h4>
            <div class="col-xs-12 col-xs-offset-0 well-sm">
                <label id="Trans"></label>
                <p>Encuesta, Seleccione una Opción:</p>
                <div>
                    <select id="encuesta_id" class="form-control" name="Encuesta" >
                        <option value="Ninguna">Ninguna</option>
                        <option value="Google">Google</option>
                        <option value="Instagram">Instagram</option>
                        <option value="FaceBook">FaceBook</option>
                        <option value="TikTok">TikTok</option>
                        <option value="Reels(Tips a la Vista)">Reels(Tips a la Vista)</option>
                        <option value="Recomendado">Recomendado</option>
                        <option value="Volante">Volante</option>
                        <option value="Caminando">Caminando</option>
                        <option value="Vivo">Vivo</option>
                        <option value="No Responde">No Responde</option>
                    </select>
                </div>
            </div>
            <input type="button" id="guardar" value="Guardar" class="btn btn-success" onclick="guardarEncuesta();">
        </div>
    </div>
    <!-- The Modal CheckOut-->
    <div id="myModalCheckOut" class="modal">
        <!-- Modal content -->
        <div id="modal-content-checkOut" class="modal-content">
            <span id="closeCheckOut" class="close">&times;</span>
            <h3>Nº Pedido: </h3>
            <h5 id="cliente"></h5>
            <div id="general">
                <h4>Articulos en la Tienda y no en el sistema</h4>
                <div id="div_checkOutInTN">
                    <div class="col-xs-12 col-xs-offset-0 well">
                        <table id="checkOutInTN" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Nropedido</th>
                                <th>Ordenweb</th>
                                <th>Articulo</th>
                                <th>Detalle</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Stock</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div id="general">
                <h4>Articulos en el Sistema y no en  la Tienda </h4>
                <div id="div_checkOutInLocalSystem">
                    <div class="col-xs-12 col-xs-offset-0 well">
                        <table id="checkOutInLocalSystem" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Nropedido</th>
                                <th>Ordenweb</th>
                                <th>Articulo</th>
                                <th>Detalle</th>
                                <th>Cantidad</th>
                                <th>PrecioVenta</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div id="general">
                <h4>Articulos con diferencia de Precio o Cantidad</h4>
                <div id="div_checkOutDiff">
                    <div class="col-xs-12 col-xs-offset-0 well">
                        <table id="checkOutInDiff" class="table table-striped table-bordered records_list">
                            <thead>
                            <tr>
                                <th>Nropedido</th>
                                <th>Articulo</th>
                                <th>Detalle</th>
                                <th>Tncantidad</th>
                                <th>TnPrecio</th>
                                <th>CantidadLocal</th>
                                <th>PrecioLocal</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pedidos.formcancelados')

@stop
@section('extra-javascript')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.6/integration/font-awesome/dataTables.fontAwesome.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>


    <!-- DataTables -->

    <script type="text/javascript">
        var glonalNroControlPedido
        var globalNroPedido
        var globalid_cliente;
        // Get the modal
        var modalcheckOut = document.getElementById('myModalCheckOut');
        var tableCheckInTN
        var tableCheckInLocalSystem
        var tableCheckInDiff
        var clickeado = false; // Flag para evitar múltiples ejecuciones
        $(document).keyup(function(e) {
            if (e.keyCode == 27) { // escape key maps to keycode `27`
                cerrar()
            }
        });
        $(document).ready( function () {
            $(document).ready( function () {
                var table =  $('#reporte').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                'excel'
                            ],
                            order: [2,'desc']

                        }

                );
                $('a.toggle-vis').on( 'click', function (e) {
                    e.preventDefault();

                    // Get the column API object
                    var column = table.column( $(this).attr('data-column') );

                    // Toggle the visibility
                    column.visible( ! column.visible() );
                } );
            } );
            cargoSelectTransportes()
        } );


        function cargoTablaPopup(nroPedido){
            var table = $("#pedidos");
            table.children().remove()
            table.append("<thead><tr><th>Articulo</th><th>Detalle</th><th>Cantidad</th><th>Vendedora</th></tr></thead>")
            $.ajax({
                url: '/api/listaPedidosWeb?nroPedido=' + nroPedido,
                dataType : "json",
                success : function(json) {
                    console.log(json)
                    $.each(json, function(index, json){
                        console.log(json['Vendedora'])
                        table.append("<tr><td>"+json['Articulo']+"</td><td>"+json['Detalle']+
                                     "</td><td>"+json['Cantidad']+"</td><td>"+json['Vendedora']+"</td></tr>");
                    });
                }
            });
            // Get the modal
            var modal = document.getElementById('myModal');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal
                modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            $(".modal-content h3").html("Pedido Nº:" + nroPedido);
        }

        function calcelarPedido (nroPedido){
            globalNroPedido = nroPedido
            if (confirm("Esta seguro que quiere cancelar el pedido Nº " + nroPedido + "?")){
                $.ajax({
                    url: '/api/cancelarPedidoPropuesta?nroPedido=' + nroPedido,
                    dataType : "json",
                    success : function(json) {
                        cargarModalCancelados(nroPedido,json[0]['descripcion'],json[1]['puntos'] ,json[2]['nombreCliente']);
                    }
                });
            } else {

            }
        }

        function cancelacionDifinitiva(){
             $.ajax({
                 url: '/api/cancelarPedido?nroPedido=' + globalNroPedido,
                 dataType : "json",
                 success : function(json) {
                     location.reload();
                 }
             });
        }
        function cargarModalCancelados (nroPedido,propuesta,puntos,cliente) {
            // Get the modal
            var modalCancelados = document.getElementById('myModalCancelados');

            // Get the <span> element that closes the modal
            var spanCancelados = document.getElementById("closeCancelados");

            // When the user clicks the button, open the modal
            modalCancelados.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            spanCancelados.onclick = function() {
                modalCancelados.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalCancelados) {
                    modalCancelados.style.display = "none";
                }
            }
            $(".modal-content h3").html("Pedido Nº:" + nroPedido);
            if (puntos == 0){
                $(".modal-content #propuesta").html("El cliente " + cliente + " no tiene propuestas disponibles");
            }
            if (puntos == -1){
                $(".modal-content #propuesta").html("El " + cliente + " " + propuesta);
            }
            if (puntos > 0) {
                $(".modal-content #propuesta").html("Ofrecer al cliente " + cliente + " la siguiente propuesta, " +  propuesta);
            }
        }

        function pedidoEntregado(nroPedido,posicionBoton){
            $.ajax({
                url: '/api/pedidoenviado?nroPedido=' + nroPedido,
                dataType : "json",
                success : function(json) {
                   // location.reload();
                    document.getElementById("botonEntregado" + posicionBoton).disabled = true;
                }
            });

            //Elimino el pedído en la tabla miCorreo
            $.ajax({
                    url: "/miCorreoEliminarDesdeEmpaquetados?nroPedido=" + nroPedido,
                    type: "post"
                })
        }

        function comentario(controlpedidos_id,nroPedido,nombre_cliente,apellido_cliente){
            glonalNroControlPedido = controlpedidos_id
            var table = $("#comentarios");
            table.children().remove()
            table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
            $.ajax({
                url: '/api/comentarios?controlpedidos_id=' + controlpedidos_id,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['nombre']+"</td><td>"+json['comentario']+
                                "</td><td>"+json['fechaFormateada']+"</td>"+ "</tr>");
                    });
                }
            });
            // Get the modal
            var modalComentario = document.getElementById('myModalComentarios');

            // Get the <span> element that closes the modal
            var spanComentario = document.getElementsByClassName("close1")[0];

            // When the user clicks the button, open the modal
            modalComentario.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            spanComentario.onclick = function() {
                modalComentario.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalComentario) {
                    modalComentario.style.display = "none";
                }
            }
            $(".modal-content h3").html("Pedido Nº:" + nroPedido);
            $(".modal-content #cliente").html( nombre_cliente + "," + apellido_cliente);
        }

        function cerrar(){
            // Get the modal
            var modalComentario = document.getElementById('myModalComentarios');
            // When the user clicks on <span> (x), close the modal
                modalComentario.style.display = "none";
            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalComentario) {
                    modalComentario.style.display = "none";
                }
            }
            document.getElementById("textarea").value = "";
        }

        function agregarNota(user_id){
            var textarea = $.trim($("textarea").val());
            if (textarea != ""){
                $.ajax({
                    url: '/api/agregarcomentarios?nroControlPedido=' + glonalNroControlPedido + "&" +
                    'user_id=' + user_id + "&" + 'textarea=' + textarea,
                    dataType : "json",
                    success : function(json) {
                        console.log(json)
                        document.getElementById("textarea").value = "";
                        refreshfunctionComentario()
                    }
                });
            } else alert("Debe agregar una nota")

        }

        function refreshfunctionComentario(){
            var table = $("#comentarios");
            table.children().remove()
            table.append("<thead><tr><th>Usuario</th><th>Comentarios</th><th>Fecha</th></tr></thead>")
            $.ajax({
                url: '/api/comentarios?controlpedidos_id=' + glonalNroControlPedido,
                dataType : "json",
                success : function(json) {
                    $.each(json, function(index, json){
                        table.append("<tr><td>"+json['nombre']+"</td><td>"+json['comentario']+
                                "</td><td>"+json['fechaFormateada']+"</td>"+ "</tr>");
                    });
                }
            });
        }
        var modal = document.getElementById('myModalTransporte');
        function modificoTransporte(nroPedido,transporte,posicionBoton){
            // Get the <span> element that closes the modal
            var span = document.getElementById("closeTransporte");

            // When the user clicks the button, open the modal
            modal.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            $(".modal-content h4").html("Pedido Nº:" + nroPedido);
           // document.getElementById("Transporte").innerHTML = "Transporte: " + transporte Se reemplaza por el Select
            //Cargo las variables con los datos que llegan la llamda del metodo
            posicionBot = posicionBoton
            nroPedi = nroPedido
            //Identifico la filla accedida para cambiar el valor del Transporte
            var rows = document.getElementById('reporte').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            for (i = 0; i < rows.length; i++) {
                rows[i].onclick = function() {
                    //Paso a la variable la fila seleccionada
                    posicionTable = this.rowIndex
                    //Me fijo el valor que tiene la fila en el cambio webSku y se lo asigno al Input
                    //que esta en el model con el ID WebSku
                    newTransporte = reporte.rows[posicionTable].cells[8].innerHTML
                   // document.getElementById("Transporte").value = newTransporte Se reemplaza por el Select
                    $('#Transporte_Select').val(newTransporte)
                }
            }
        }
        function guardarTransporte() {
            $.ajax({
                url: 'api/transortePedido?nropedido=' + nroPedi + '&&transporte=' + document.getElementById("Transporte_Select").value,
                dataType: "json",
                success: function (json) {
                    modal.style.display = "none";
                    //El "json" es la respuesta del valor que se cambio pot la API del webSky
                    //Luego se lo cargo a la tabla en le posición "posicionTable"
                    reporte.rows[posicionTable].cells[8].innerHTML = json ;
                }
            });
        }
        function refresh (){
            location.reload();
        }


        function cambioInstancia (nroPedido,instancia,posicionBoton){
            // Reiniciar el flag al llamar la función
            clickeado = false;
            var rows = document.getElementById('reporte').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            for (i = 0; i < rows.length; i++) {
                rows[i].onclick = function() {
                    if (!clickeado) {
                        // Marcamos el flag como true para evitar múltiples clics
                        clickeado = true;
                        //Paso a la variable la fila seleccionada
                        posicionTable = this.rowIndex
                        console.log(instancia)
                        if (reporte.rows[posicionTable].cells[8].innerHTML !== "" || instancia !== 2){
                            $.ajax({
                                url: 'api/instanciaPedidos?nroPedido=' + nroPedido + '&&instancia=' + instancia,
                                dataType: "json",
                                success: function (json) {
                                    modal.style.display = "none";
                                    //El "json" es la respuesta del valor que se cambio por la API del webSky
                                    //Luego se lo cargo a la tabla en le posición "posicionTable"
                                    reporte.rows[posicionTable].cells[9].innerHTML = json;
                                    if (json == 'Iniciado'){
                                        document.getElementById("botonInstanciaFin" + posicionBoton).style.display = 'block'
                                        document.getElementById("botonInstancia" + posicionBoton).style.display = 'none'
                                    }else {
                                        if (document.getElementById("botonInstanciaFin" + posicionBoton) != null){
                                            document.getElementById("botonInstanciaFin" + posicionBoton).disabled = true
                                        } else {
                                            document.getElementById("botonInstancia" + posicionBoton).disabled = true
                                        }
                                    }

                                }
                            });
                        }else alert('Para poder finalizar un pedido debe completar el campo Transporte')
                        // Sobrescribir el evento click para que no se vuelva a ejecutar
                        this.onclick = null;
                    }
                }
            }
        }

        function checkOut (controlpedidos_id,nroPedido,nombre_cliente,apellido_cliente){
            console.log(nroPedido)
            eliminarTablas()
            checkOutInTN(nroPedido)
            checkOutInLocalSystem(nroPedido)
            checkOutDiff(nroPedido)

            // Get the <span> element that closes the modal
            var spancheckOut = document.getElementById('closeCheckOut')

            // When the user clicks the button, open the modal
                modalcheckOut.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            spancheckOut.onclick = function() {
                modalcheckOut.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modalcheckOut) {
                    modalcheckOut.style.display = "none";
                }
            }
            $(".modal-content h3").html("Pedido Nº:" + nroPedido);
            $(".modal-content #cliente").html( nombre_cliente + "," + apellido_cliente);
        }


        function checkOutInTN(nroPedido){
            $.ajax({
                url: '/api/ordencheckoutInTN?nroPedido=' + nroPedido,
                'method': "GET",
                'contentType': 'application/json',
                success : function(json) {
                    ordenInsert = json
                    tableCheckInTN = $('#checkOutInTN').DataTable({
                                dom: 'Bfrtip',
                                "autoWidth": false,
                                "bDestroy": true,
                                "pageLength": 5,
                                buttons: [
                                    'excel'
                                ],
                                order: [0,'desc'],
                                "aaData": json,
                                "columns": [
                                    { "data": "nropedido" },
                                    { "data": "OrdenWeb" },
                                    { "data": "articulo" },
                                    { "data": "detalle" },
                                    { "data": "cantidad" },
                                    { "data": "precio" },
                                    { "data": "stock" },
                                ]
                            }
                    );
                },
            })
        }

        function checkOutInLocalSystem(nroPedido){
            $.ajax({
                url: '/api/ordencheckoutInLocalSystem?nroPedido=' + nroPedido,
                'method': "GET",
                'contentType': 'application/json',
                success : function(json) {
                    ordenInsert = json
                    tableCheckInLocalSystem = $('#checkOutInLocalSystem').DataTable({
                                dom: 'Bfrtip',
                                "autoWidth": false,
                                "bDestroy": true,
                                "pageLength": 5,
                                buttons: [
                                    'excel'
                                ],
                                order: [0,'desc'],
                                "aaData": json,
                                "columns": [
                                    { "data": "nropedido" },
                                    { "data": "OrdenWeb" },
                                    { "data": "Articulo" },
                                    { "data": "detalle" },
                                    { "data": "cantidad" },
                                    { "data": "PrecioVenta" },
                                ]
                            }
                    );
                },
            })
        }

        function checkOutDiff(nroPedido){
            $.ajax({
                url: '/api/ordencheckoutInDiff?nroPedido=' + nroPedido,
                'method': "GET",
                'contentType': 'application/json',
                success : function(json) {
                    ordenInsert = json
                    tableCheckInDiff = $('#checkOutInDiff').DataTable({
                                dom: 'Bfrtip',
                                "autoWidth": false,
                                "bDestroy": true,
                                "pageLength": 5,
                                buttons: [
                                    'excel'
                                ],
                                order: [0,'desc'],
                                "aaData": json,
                                "columns": [
                                    { "data": "nropedido" },
                                    { "data": "articulo" },
                                    { "data": "detalle" },
                                    { "data": "TNCantidad" },
                                    { "data": "TNPrecio" },
                                    { "data": "CantidadLocal" },
                                    { "data": "PrecioLocal" },
                                ]
                            }
                    );
                },
            })
        }

        function eliminarTablas(){
            if(typeof tableCheckInTN != "undefined"){
                // table.destroy()
                // Para evitar hacer un destroy que demora mas tiempo, se agrego el parametro "bDestroy en las propiedades de la tabla" y luego hago un clear.
                tableCheckInTN.clear().draw();
            }
            if(typeof tableCheckInLocalSystem != "undefined"){
                // table.destroy()
                // Para evitar hacer un destroy que demora mas tiempo, se agrego el parametro "bDestroy en las propiedades de la tabla" y luego hago un clear.
                tableCheckInLocalSystem.clear().draw();
            }
            if(typeof tableCheckInDiff != "undefined"){
                // table.destroy()
                // Para evitar hacer un destroy que demora mas tiempo, se agrego el parametro "bDestroy en las propiedades de la tabla" y luego hago un clear.
                tableCheckInDiff.clear().draw();
            }
            return
        }
        //Descontinuado
        var modalEncuesta = document.getElementById('myModalEncuesta');
        function encuesta(id_cliente, nombre, apellido){
            globalid_cliente = id_cliente;
            consultaEncuestaCliente(globalid_cliente);
            // Get the <span> element that closes the modal
            var span = document.getElementById("closeEncuesta");

            // When the user clicks the button, open the modal
            modalEncuesta.style.display = "block";

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modalEncuesta.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modalEncuesta) {
                    modalEncuesta.style.display = "none";
                }
            }
            $(".modal-content h4").html("Cliente: " + nombre + ", " + apellido);
        }

        //Descontinuado
        function guardarEncuesta(id_cliente){
            var select = document.getElementById('encuesta_id');
            var selectValue = select.options[select.selectedIndex].text;
            $.ajax({
                url: 'api/encuestaRedes?id_cliente=' + globalid_cliente + '&encuesta=' + selectValue ,
                dataType: "json",
            });
            modalEncuesta.style.display = "none";
        }
        function consultaEncuestaCliente(id_cliente){
            $.ajax({
                url: '/api/encuestaRedesConsulta?id_cliente=' + id_cliente,
                dataType: "json",
                success: function (json) {
                    $('#encuesta_id').val(json[0]['encuesta'])
                }
            });
        }

        function cambioPago(nroPedido,estado, posicionBoton){
            $.ajax({
                url:'api/estadopago?nroPedido=' + nroPedido + '&estado=' + estado,
                dataType: 'json',
                success: function(json){
                    if (json == 0) {
                        document.getElementById("botonNoPago" + posicionBoton).style.display = 'block'
                        document.getElementById("botonPago" + posicionBoton).style.display = 'none'
                    }else {
                        document.getElementById("botonPago" + posicionBoton).style.display = 'block'
                        document.getElementById("botonNoPago" + posicionBoton).style.display = 'none'
                    }
                }
            })
        }

        function cargoSelectTransportes() {
            const selectTransportes = document.getElementById('Transporte_Select');
            $.ajax({
                url: '/api/transportes',
                dataType: "json",
                success: function (data) {
                    // Borra las opciones existentes en el select
                    while (selectTransportes.firstChild) {
                        selectTransportes.removeChild(selectTransportes.firstChild);
                    }

                    // Recorre los datos JSON y agrega las opciones al select
                    data.forEach(function(opcion) {
                        const option = document.createElement('option');
                        option.text = opcion.nombre;
                        selectTransportes.appendChild(option);
                    });
                }
            });
        }
    </script>
    @include('chatia.consulta_ia_v2')

@stop
