<div id="myModalHistorico" class="modalHistorico">
    <div id="modal-content" class="modal-content-Historico">
        <span id="close" class="close">&times;</span>
        <div id="example-table"></div>
    </div>
</div>

<style>
        .modalHistorico {
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
        .modal-content-Historico {
            background-color: rgba(243, 255, 242, 0.91);
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            height: 80%;
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
    </style>
<script type="text/javascript">
    function reporteHistorico(){
        llenarTabla()
        var modal = document.getElementById('myModalHistorico');

        // Get the <span> element that closes the modal
        var span = document.getElementById("close");

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
    }

    var minMaxFilterEditor = function(cell, onRendered, success, cancel, editorParams){

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

        function buildValues(){
            success({
                start:start.value,
                end:end.value,
            });
        }

        function keypress(e){
            if(e.keyCode == 13){
                buildValues();
            }

            if(e.keyCode == 27){
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
    function minMaxFilterFunction(headerValue, rowValue, rowData, filterParams){
        //headerValue - the value of the header filter element
        //rowValue - the value of the column in this row
        //rowData - the data for the row being filtered
        //filterParams - params object passed to the headerFilterFuncParams property

        if(rowValue){
            if(headerValue.start != ""){
                if(headerValue.end != ""){
                    return rowValue >= headerValue.start && rowValue <= headerValue.end;
                }else{
                    return rowValue >= headerValue.start;
                }
            }else{
                if(headerValue.end != ""){
                    return rowValue <= headerValue.end;
                }
            }
        }

        return true; //must return a boolean, true if it passes the filter.
    }

    $("#example-table").tabulator({
        height: "350px",
        columns: [
            {title: "Fecha", field: "fecha", sortable: true, width: 140},
            {title: "Proveedor", field: "proveedor", sortable: true, width: 140},
            {title: "Tipo", field: "tipo", sortable: true, width: 140},
            {title: "PorcentajeDescuento", field: "porcentaje_descuento", sortable: true, width: 190},
            {title: "Valor", field: "valor", sortable: true, width: 140},
            {title: "ValorAgregado", field: "valor_agrego", sortable: true, width: 140},
            {title: "ValorQuitado", field: "valor_quito", sortable: true, width: 140},
        ],

    });

    function llenarTabla() {
        $("#example-table").tabulator("setData", '/reportecambiopreciohistorico');
    }
</script>
