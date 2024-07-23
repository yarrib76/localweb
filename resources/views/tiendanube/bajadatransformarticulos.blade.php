@extends('layouts.master')
@section('contenido')
    <div>
        <body>
        <br/>
        <br/>
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title" style="padding:12px 0px;font-size:25px;"><strong>Bajar Lista y Transformacion de Articulos de Tienda Nube</strong></h3>
                </div>
                <select id="select">
                    <!--Debe ir el store_id de Samrira ya que se utilizara como master de exportacion de Articulos-->
                    {{$store_id = '938857'}}
                    <option>Selecciona un Local</option>
                    @if (substr(Request::url('http://donatella.dyndns.org'),0,27) == 'http://donatella.dyndns.org')
                        <option>Donatella</option>
                        <option>Viamore</option>
                    @elseif (substr(Request::url('http://samirasrl.dyndns.org'),0,27) == 'http://samirasrl.dyndns.org')
                        <option>Donatella</option>
                        <option>Viamore</option>
                    @elseif (substr(Request::url('http://viamore.dyndns.org'),0,25) == 'http://viamore.dyndns.org')
                        <option>Samira</option>
                        <option>Donatella</option>
                    @elseif (substr(Request::url('http://donalab2.dyndns.org'),0,26) == 'http://donalab2.dyndns.org')
                        <option>Viamore</option>
                        <option>Samira</option>
                        <option>Donatella</option>
                    @endif
                </select>
                <div class="panel-body" id="sincro">
                    <input type="button" value="Bajar" class="btn btn-success" onclick="sincro({{$store_id}})">
                </div>
            </div>
        </div>

        </body>
    </div>

    <style>
        #myModal {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 11%;
            height: 20%;
            overflow-y: auto;
        }
        #myModalFinish {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 30%;
            height: 50%;
            overflow-y: auto;
        }
        #myModalError {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 30%;
            height: 50%;
            overflow-y: auto;
        }
    </style>
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <img src="refresh/load.gif" height="100" width="100">
        </div>
    </div>

    <div id="myModalFinish" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="col-xs-12 col-xs-offset-0 well">
            <table id="pedidos" class="table table table-scroll table-striped">
                <thead>
                <tr>
                    <td><img src="refresh/checkmark.png" height="100" width="100"></td>
                    <td><h1>Finalizado</h1></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="button" value="Cerrar" class="btn btn-success" onclick="cerrarFinish()"></td>
                </tr>
                </thead>
            </table>
        </div>

        </div>
    </div>
    <div id="myModalError" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="col-xs-12 col-xs-offset-0 well">
                <table id="pedidos" class="table table table-scroll table-striped">
                    <thead>
                    <tr>
                        <td><img src="refresh/error.png" height="100" width="100"></td>
                        <td><h1>Error en Proceso</h1></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="button" value="Cerrar" class="btn btn-success" onclick="cerrarError()"></td>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>

    <script>
        // Get the modal

        var modalFinish = document.getElementById('myModalFinish');
        var modalError = document.getElementById('myModalError');
        function sincro(store_id){
            var selectLocal = document.getElementById("select");
            selectLocal = selectLocal.options[selectLocal.selectedIndex].text
            if (selectLocal == "Selecciona un Local") {
                window.alert("Debe Seleccionar un Local")
            } else {
                // Get the modal
                var modal = document.getElementById('myModal');
                // When the user clicks the button, open the modal
                modal.style.display = "block";
                $.ajax({
                    url: '/api/tiendanubeGetArticulos?store_id=' + store_id + "&" + "local=" + selectLocal,
                    dataType : "json",
                    success : function(json) {
                        console.log(json)
                        //close the modal
                        modal.style.display = "none";
                        // When the finish process, open the modalFinish
                        modalFinish.style.display = "block";
                        window.location.replace("/vistaExportaExcel");
                    },
                    error: function () {
                        //close the modal
                        console.log(json)
                        modal.style.display = "none";
                        // When the finish process, open the modalError
                        modalError.style.display = "block";
                    }
                });
            }
        }
        function cerrarFinish(){
            //close the modal
            modalFinish.style.display = "none";
        }
        function cerrarError(){
            //close the modal
            modalError.style.display = "none";
        }
    </script>
@stop