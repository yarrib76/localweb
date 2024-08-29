<div id="myModalNotificaciones" class="modal">
        <!-- Modal Ingreso -->
        <div id="modal-content-notificacion" class="modal-content">
                <span id="close-notificacion" class="close">&times;</span>
                <div class="row">
                        <div class="col-sm-25">
                                <div class="panel panel-primary">
                                        <div class="panel-heading"><i class="fa fa-book"> Notificaciones</i></div>
                                        <div id="table-notificaciones"></div>
                                </div>
                        </div>
                </div>
        </div>
</div>

<style>
        .modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                padding-top: 50px; /* Location of the box */
                left: 20%;
                top: 5%;
                width: 70%; /* Full width */
                height: 40%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        /* Modal Content */
        .modal-content {
                background-color: rgba(243, 255, 242, 0.91);
                margin: auto;
                padding: 15px;
                border: 1px solid #888;
                width: 100%;
                height: 100%;
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

        #table-notificaciones{
                background-color: rgba(255, 248, 243, 0.09); /* Color de fondo personalizado */
        }


</style>

<script src="../../js/jquery/jquery.min.js"></script>
<link rel="stylesheet" href="../../js/tabulador/tabulator5-5-2min.css" rel="stylesheet">
<script type="text/javascript" src="../../js/tabulador/tabulator5-5-2.min.js"></script>
<script type="text/javascript">
        // Obtener el ID del usuario autenticado desde una variable PHP
        var userId = "<?php echo Auth::user()->id; ?>";
        $(document).ready( function () {
                getCantiNoti()
        })
        //custom max min header filter
        var minMaxFilterEditor = function (cell, onRendered, success, cancel, editorParams) {
                var end;
                var container = document.createElement("span");
                //create and style inputs
                var start = document.createElement("input");
                start.setAttribute("type", "number");
                start.setAttribute("placeholder", "Min");
                start.setAttribute("min", 0);
                start.setAttribute("max", 100);
                start.style.padding = "4px";
                start.style.width = "50%";
                start.style.boxSizing = "border-box";

                start.value = cell.getValue();

                function buildValues() {
                        success({
                                start: start.value,
                                end: end.value,
                        });
                }

                function keypress(e) {
                        if (e.keyCode == 13) {
                                buildValues();
                        }

                        if (e.keyCode == 27) {
                                cancel();
                        }
                }

                end = start.cloneNode();

                start.addEventListener("change", buildValues);
                start.addEventListener("blur", buildValues);
                start.addEventListener("keydown", keypress);

                end.addEventListener("change", buildValues);
                end.addEventListener("blur", buildValues);
                end.addEventListener("keydown", keypress);


                container.appendChild(start);
                container.appendChild(end);

                return container;
        }
        //custom max min filter function
        function minMaxFilterFunction(headerValue, rowValue, rowData, filterParams) {
                //headerValue - the value of the header filter element
                //rowValue - the value of the column in this row
                //rowData - the data for the row being filtered
                //filterParams - params object passed to the headerFilterFuncParams property

                if (rowValue) {
                        if (headerValue.start != "") {
                                if (headerValue.end != "") {
                                        return rowValue >= headerValue.start && rowValue <= headerValue.end;
                                } else {
                                        return rowValue >= headerValue.start;
                                }
                        } else {
                                if (headerValue.end != "") {
                                        return rowValue <= headerValue.end;
                                }
                        }
                }

                return true; //must return a boolean, true if it passes the filter.
        }
        var tableNotificaciones = new Tabulator("#table-notificaciones", {
                height: "550px",
                columns: [
                        {title: "Fecha", field: "fecha", width: 150},
                        {title: "Tipo", field: "tipo", width: 350},
                        {title: "Accion",width:100, cellClick:function(e, cell){
                                eliminarNoti(cell.getRow().getData()['id_notificaciones'])
                        },
                                formatter: function (cell) {
                                        return "<button class='btn btn-success'><i class='fa fa-check'></i></button>"; // Botón verde con ícono de check
                                }
                        }
                ],
        });


        function notificaciones(){
                cargaTabulador();
                var modalNotificacion = document.getElementById('myModalNotificaciones');
                // Get the <span> element that closes the modal
                var spanNotificacion = document.getElementById("close-notificacion");
                // When the user clicks the button, open the modal
                modalNotificacion.style.display = "block";
                // When the user clicks on <span> (x), close the modal
                spanNotificacion.onclick = function() {
                        modalNotificacion.style.display = "none";
                }
        }
        function cargaTabulador(){
                tableNotificaciones.setData('/api/getNotificaciones?id_users=' + userId)
        }

        function getCantiNoti(){
                $.ajax({
                        type: 'get',
                        url: '/api/getCantNoti?id_users=' + userId,
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function (datos) {
                                document.getElementById('alertCount').innerText = datos
                        },
                        error: function (datos) {
                                console.log("Este callback maneja los errores " + datos);
                        }

                }); // aja
        }

        function eliminarNoti(id_notificacion){
                $.ajax({
                        type: 'get',
                        url: '/api/marcarLeido?id_noti=' + id_notificacion,
                        contentType: "application/json; charset=utf-8",
                        dataType: "json",
                        success: function (datos) {
                                var cantidad = document.getElementById('alertCount').innerText
                                document.getElementById('alertCount').innerText = cantidad -1
                                cargaTabulador()
                        },
                        error: function (datos) {
                                console.log("Este callback maneja los errores " + datos);
                        }
                })
        }
        // Ejecutar la función cada 5 segundos (5000 milisegundos), para 2 minutos (120,000 milisegundos)
        setInterval(getCantiNoti, 10000);
</script>