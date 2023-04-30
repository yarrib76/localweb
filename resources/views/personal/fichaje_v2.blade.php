<html>

    <body bgcolor="#8a2be2">
        <h1 id="titulo">Sistema de Fichaje</h1>
        <table>
            <tr>
                <td>
                    <div id="botonInicio" align="center">
                        <a onclick="callModal('ingreso')" class="round-button" style="margin: 200px">
                            <font color="#29966c" id="btn_ingreso">Ingreso</font>
                        </a>
                    </div>
                </td>
                <td>
                    <div id="botonSalida" align="center">
                        <a onclick="callModal('egreso')" class="round-button" style="margin: 200px">
                            <font color="#29966c" id="btn_egreso">Egreso</font>
                        </a>
                    </div>
                </td>
            </tr>
        </table>

    </body>

    <div id="myModal" class="modal">
        <!-- Modal Ingreso -->
        <div id="modal-content" class="modal-content">
            <span id="close" class="close">&times;</span>
            <h2 align="center">LECTOR DE TARJETAS</h2>
            <table>
                <tr>
                    <td>
                        <img src="refresh/codigo-de-barras.gif" width="90" height="90">
                    </td>
                    <td>
                        <input type="text" class="form-control" id="codigo" required="required" style= "font-size:15px" autocomplete="off">
                        <input type="text" class="form-control" id="vendedora" disabled style= "font-size:15px">
                    </td>
                    <td>
                        <button id="botonFichada"  disabled></button>
                    </td>
                </tr>
            </table>
            <div id="clockdate">
                <div class="clockdate-wrapper">
                    <div id="clock"></div>
                    <div id="date"></div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <style>
    .round-button {
        display:block;
        width:200px;
        height:200px;
        line-height:200px;
        border:2px solid #29966c;
        border-radius: 50%;
        color:#FFF;
        text-align:center;
        text-decoration:none;
        background: #FFFFFF;
        box-shadow: 0 0 3px gray;
        font-size:50px;
        font-weight:bold;
    }
    #botonInicio {
        width: 33.33%;
    }

    #botonSalida {
        width: 33.33%;
    }
    #botonFichada{
        width: 70px;
        height:70px;
        display:block;
        line-height:10px;
        border:2px solid #29966c;
        border-radius: 50%;
        color: rgba(64, 9, 21, 0.6);
        text-align:center;
        text-decoration:none;
        background: #f3fff2;
        box-shadow: 0 0 3px gray;
        font-size:15px;
        font-weight:bold;

    }
    #titulo{
        text-align:center;
        color: #00ed09;
        font-size: 50px;
    }
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
        background-color: #caffca;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 30%;
        overflow-y: auto;
        border-radius: 10%;
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
    .clockdate-wrapper {
        background-color: #333;
        padding:25px;
        max-width:350px;
        width:100%;
        text-align:center;
        border-radius:5px;
        margin:0 auto;
        margin-top:15%;
    }
    #clock{
        background-color:#333;
        font-family: sans-serif;
        font-size:60px;
        text-shadow:0px 0px 1px #fff;
        color:#fff;
    }
    #clock span {
        color:#888;
        text-shadow:0px 0px 1px #333;
        font-size:30px;
        position:relative;
        top:-27px;
        left:-10px;
    }
    #date {
        letter-spacing:10px;
        font-size:14px;
        font-family:arial,sans-serif;
        color:#fff;
    }
</style>

</html>
<script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>

<script type="text/javascript">
    $(document).ready ( function(){
        actualizoReloj()
    });

    function callModal(tipo){
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the <span> element that closes the modal
        var span = document.getElementById("close");

        // When the user clicks the button, open the modal
        modal.style.display = "block";

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
            location.reload()
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                location.reload()
            }
        }
        var btnFichar = document.getElementById('botonFichada')
        if (tipo == "ingreso"){
            btnFichar.onclick = function() { cargarFichajeIngreso('ingreso'); };
            btnFichar.innerHTML = "Ingreso";
        }
        if (tipo == "egreso"){
            btnFichar.onclick = function() { cargarFichajeIngreso('egreso'); };
            btnFichar.innerHTML = "Egreso";
        }
        var input = document.getElementById('codigo')
        input.focus()
        input.value = ""
        input.addEventListener('input', function (evt) {
            consultaIngreso(this.value);
        });
    }
    function consultaIngreso(codigo){
        var vendedora = document.getElementById('vendedora')
        var btnIngreso = document.getElementById('botonFichada')
        vendedora.value = ""
        $.ajax({
            url: '/fichajeCodigo?codigo=' + codigo,
            dataType: "json",
            success: function (json) {
                console.log(json.length)
                if (json.length ==! 0){
                    vendedora.value = json[0]['name'];
                    btnIngreso.disabled = false;
                }else {
                    alert('La vendedoras No Existe')
                    location.reload()
                }
            }
        });
    }
    function cargarFichajeIngreso(tipo){
        var codigoVendedora = document.getElementById('codigo')
        if (tipo == "ingreso"){
            $.ajax({
                url:'/ingreso?codigo=' + codigoVendedora.value ,
                dataTupe: "json",
                success: function (json){
                    alert(json)
                    location.reload()
                }
            });
        }
        if (tipo == "egreso"){
            $.ajax({
                url:'/egreso?codigo=' + codigoVendedora.value ,
                dataTupe: "json",
                success: function (json){
                    alert(json)
                    location.reload()
                }
            });
        }
    }
    function actualizoReloj(){
        var today = new Date();
        var hr = today.getHours();
        var min = today.getMinutes();
        var sec = today.getSeconds();
        //Add a zero in front of numbers<10
        min = checkTime(min);
        sec = checkTime(sec);
        document.getElementById("clock").innerHTML = hr + " : " + min + " : " + sec;
        var time = setTimeout(function(){ actualizoReloj() }, 500);
    }
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
</script>