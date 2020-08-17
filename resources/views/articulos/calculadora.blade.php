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
		width: 80%;
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

<!-- Trigger/Open The Modal -->
<!-- <button id="myBtn">Open Modal</button> -->
<label id="myBtn" class="btn btn-primary" name="calculadora"><i ></i> Calculadora</label>

<!-- The Modal -->
<div id="myModal" class="modal">

	<!-- Modal content -->
	<div class="modal-content">
		<span class="close">&times;</span>
		<label>Calculadora:Precio en Argentina</label>
		<h4></h4>
		<label>Pesos</label>
		<input type="number" step="any" class="form-control" name="CalculadoraPrecioPesos" id="CalculadoraPrecioPesos" readonly>
		<label>Dolares</label>
		<input type="number" step="any" class="form-control" name="CalculadoraPrecioDolares" id="CalculadoraPrecioDolares" readonly>
		<label>Precio de Venta</label>
		<input type="number" step="any" class="form-control" name="CalculadoraPrecioVenta" id="CalculadoraPrecioVenta" readonly>
		<label>Gastos</label>
		<input type="number" step="any" class="form-control" name="CalculadoraGastos" id="CalculadoraGastos" readonly>
		<label>Ganancia</label>
		<input type="number" step="any" class="form-control" name="CalculadoraGanancia" id="CalculadoraGanancia" readonly>
	</div>

</div>
<script>
	// Get the modal
	var modal = document.getElementById('myModal');

	// Get the button that opens the modal
	var btn = document.getElementById("myBtn");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	// When the user clicks the button, open the modal
	btn.onclick = function() {
		modal.style.display = "block";
		if (document.getElementById('Pesos').checked){
			$precioConvertido = document.getElementById('PrecioConvertido').value
			$gastos = document.getElementById('GastosProveedor').value
			$ganancia = document.getElementById('GananciaProveedor').value
			$precioEnArgentinaPesos = ($precioConvertido * $gastos)
			$precioEnArgentinaPesos = ayudaPrecio($precioEnArgentinaPesos)
			$precioVenta = ($precioConvertido * $ganancia * $gastos)
			$precioVenta = ayudaPrecio($precioVenta)
			document.getElementById('CalculadoraPrecioPesos').setAttribute('value',$precioEnArgentinaPesos)
			document.getElementById('CalculadoraPrecioVenta').setAttribute('value',$precioVenta)
			document.getElementById('CalculadoraGastos').setAttribute('value',$gastos)
			document.getElementById('CalculadoraGanancia').setAttribute('value',$ganancia)
			document.getElementById('CalculadoraPrecioDolares').setAttribute('value',0)
		}
		if (document.getElementById('Dolares').checked){
			$precioConvertido = document.getElementById('PrecioConvertido').value
			$gastos = document.getElementById('GastosProveedor').value
			$ganancia = document.getElementById('GananciaProveedor').value
			$dolar = {{ $dolar  }}
			$precioConvertidoDolar = ($precioConvertido * $dolar)
			$precioEnArgentinaPesos = ($precioConvertidoDolar * $gastos)
			$precioEnArgentinaPesos = ayudaPrecio($precioEnArgentinaPesos)
			$precioVenta = ($precioConvertidoDolar * $ganancia * $gastos)
			$precioVenta = ayudaPrecio($precioVenta)
			$precioEnArgentinaDolar = ($precioConvertido * $gastos)
			$precioEnArgentinaDolar = ayudaPrecio($precioEnArgentinaDolar)
			document.getElementById('CalculadoraPrecioPesos').setAttribute('value',$precioEnArgentinaPesos)
			document.getElementById('CalculadoraPrecioDolares').setAttribute('value',$precioEnArgentinaDolar)
			document.getElementById('CalculadoraPrecioVenta').setAttribute('value',$precioVenta)
			document.getElementById('CalculadoraGastos').setAttribute('value',$gastos)
			document.getElementById('CalculadoraGanancia').setAttribute('value',$ganancia)
		}
		if (document.getElementById('Manual').checked){
			$precioManual = document.getElementById('InputManual').value
			$gastos = document.getElementById('Gastos').value
			$ganancia = document.getElementById('Ganancia').value
			$precioEnArgentinaPesos = ($precioManual * $gastos)
			$precioEnArgentinaPesos = ayudaPrecio($precioEnArgentinaPesos)
			$precioVenta = ($precioManual * $ganancia * $gastos)
			$precioVenta = ayudaPrecio($precioVenta)
			document.getElementById('CalculadoraPrecioPesos').setAttribute('value',$precioEnArgentinaPesos)
			document.getElementById('CalculadoraPrecioVenta').setAttribute('value',$precioVenta)
			document.getElementById('CalculadoraGastos').setAttribute('value',$gastos)
			document.getElementById('CalculadoraGanancia').setAttribute('value',$ganancia)
			document.getElementById('CalculadoraPrecioDolares').setAttribute('value',0)
		}
	}

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
	function ayudaPrecio ($precioVenta){
		$precioVenta = Math.round($precioVenta * 100) /100;
		$resultdo = (Math.round(($precioVenta / 0.05) * 100) /100 - parseInt(Math.round($precioVenta / 0.05 * 100) / 100));
		while ($resultdo != 0) {
			$precioVenta = $precioVenta - 0.01;
			$resultdo = (Math.round(($precioVenta / 0.05) * 100) /100 - parseInt(Math.round($precioVenta / 0.05 * 100) / 100));
		}
		$precioVenta = Math.round($precioVenta * 100) /100;
		return $precioVenta;
	}
</script>