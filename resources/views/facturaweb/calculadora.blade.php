<style>
	body {font-family: Arial, Helvetica, sans-serif;}

    .myModalCalculadora{
        display: none;               /* oculto */
        position: fixed;
        z-index: 1050;
        inset: 0;                    /* top:0; right:0; bottom:0; left:0 */
        background: rgba(0,0,0,.4);

        /* Centrado */
        align-items: center;
        justify-content: center;

        /* si querés que el overlay no scrollee */
        overflow: hidden;
    }

    /* Caja del modal */
    .modal-content-Calculadora{
        background:#fff;
        width: 20%;
        max-width: 600px;
        border-radius: 8px;
        padding: 20px;
    }

	/* The Close Button */
	.closeCalculadora {
		color: #aaaaaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
	}

	.closeCalculadora:hover,
	.closeCalculadora:focus {
		color: #000;
		text-decoration: none;
		cursor: pointer;
	}
</style>

<!-- The Modal -->
<div id="myModalCalculadora" class="myModalCalculadora">

	<!-- Modal content -->
	<div class="modal-content-Calculadora">
		<span class="closeCalculadora">&times;</span>
        <i class="fas fa-calculator fa-2x text-primary"></i>
		<h4></h4>
		<label>Total</label>
		<input type="number" step="any" class="form-control" name="CalculadoraTotal" id="CalculadoraTotal" readonly>
		<label>PagoCliente</label>
		<input type="number" step="any" class="form-control" name="CalculadoraPagoCliente" id="CalculadoraPagoCliente">
		<label>Vuelto</label>
		<input type="number" step="any" class="form-control" name="CalculadoraVuelto" id="CalculadoraVuelto" readonly>
	</div>

</div>
<script>
    // Elementos
    var modal = document.getElementById("myModalCalculadora");
    var btn = document.getElementById("myBtnCalculadora");
    var span = document.getElementsByClassName("closeCalculadora")[0];
    const totalCalculadora = document.getElementById("CalculadoraTotal")
    const vueltoCalculadora = document.getElementById("CalculadoraVuelto")
    const pagoCliente = document.getElementById("CalculadoraPagoCliente")

    function calcularVuelto() {
        const total = Number(totalCalculadora.value);
        const pago  = Number(pagoCliente.value);

        // Si todavía no hay pago, no tocar el vuelto
        if (!pagoCliente.value) return;

        // Si hay números válidos, calcular
        if (!Number.isNaN(total) && !Number.isNaN(pago)) {
            vueltoCalculadora.value = (pago - total).toFixed(2);
        }
    }
    // Un solo listener (se recalcula escribiendo y también al salir del input)
    pagoCliente.addEventListener("input", calcularVuelto);
    pagoCliente.addEventListener("blur",  calcularVuelto);


    // Abrir modal
    btn.onclick = function () {

        let txtTotalFactura = document.getElementById('totalApagar').value
        modal.style.display = "flex";
        if (textEfectivo.value != 0) {
            totalCalculadora.value = textEfectivo.value
        } else {
                if(textDescuento.value != 0) {
                    totalCalculadora.value = textDescuento.value
                } else {
                    totalCalculadora.value = txtTotalFactura }
        }
        pagoCliente.addEventListener('change',function(event){
            console.log("Hola mundo")
            vueltoCalculadora.value = parseFloat(pagoCliente - totalCalculadora)
        } )

        // reset campos al abrir
        pagoCliente.value = "";
        vueltoCalculadora.value = "";

        // foco para tipear directo
        pagoCliente.focus();
    };

    // Cerrar modal con la X
    span.onclick = function () {
        modal.style.display = "none";
    };

    // Cerrar con ESC (opcional pero útil)
    document.addEventListener("keydown", function(e){
        if (e.key === "Escape") modal.style.display = "none";
    });
</script>