<!-- The Modal Cancelados-->
<div id="myModalCancelados" class="modal">
<!-- Modal content -->
    <div id="modal-content-Cancelados" class="modal-content">
        <span id="closeCancelados" class="close">&times;</span>
        <h3>Nº Pedido: </h3>
        <h4 id="propuesta"></h4>
        <div id="general">
            <div id="botonCancelarPedidos">
                <button id="rechazar" class="btn btn-danger" onclick="cancelacionDifinitiva();">Rechaza Propuesta</button>
            </div>
        </div>
    </div>
</div>

<style>
    body {font-family: Arial, Helvetica, sans-serif;}
    /* The Modal (background) */
    #myModalCancelados {
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
    #modal-content-Cancelados {
        background-color: #00ffff;
        margin: auto;
        padding: 20px;
        border: 3px solid #888;
        width: 50%;
    }
    #botonCancelarPedidos{
        margin: auto;
        width: 150px;
        height: 50px;
    }
</style>
