<?php
namespace Donatella\Ayuda;

use Donatella\Http\Controllers\Api\Proveedor;
use Donatella\Models\Dolar;
use Donatella\Models\Proveedores;

class Precio
{
    public function query($articulo)
    {
        if (!is_null($articulo->PrecioManual) OR (!is_null($articulo->PrecioConvertido))) {
            if ($articulo->PrecioManual <> 0) {
                $precio = $this->precioManual($articulo);
            } else {
                $precio = $this->precioConvertido($articulo);
            }
            return $precio;
        }
    }

    public function precioManual($articulo)
    {
        $precioVenta = ($articulo->PrecioManual * $articulo->Gastos * $articulo->Ganancia);
        $precioVenta = $this->redondeoDecimal($precioVenta);
        $precio [] = ['PrecioVenta' => $precioVenta, 'Gastos' => $articulo->Gastos, 'Ganancia' => $articulo->Ganancia];
        return $precio;
    }

    public function precioConvertido($articulo)
    {
        $proveedor = Proveedores::where('Nombre', $articulo->Proveedor)->get();
        if ($articulo->Moneda == "ARG") {
            $precioVenta = $articulo->PrecioConvertido * $proveedor[0]->Gastos * $proveedor[0]->Ganancia;
            $precioVenta = $this->redondeoDecimal($precioVenta);
            $precio [] = ['PrecioVenta' => $precioVenta, 'Gastos' => $proveedor[0]->Gastos, 'Ganancia' => $proveedor[0]->Ganancia];
            return $precio;
        }else{
            $cotizacion = Dolar::get();
            $precioEnPesos = $articulo->PrecioConvertido * $cotizacion[0]->PrecioDolar;
            $precioVenta = $precioEnPesos * $proveedor[0]->Gastos * $proveedor[0]->Ganancia;
            $precioVenta = $this->redondeoDecimal($precioVenta);
            $precio [] = ['PrecioVenta' => $precioVenta, 'Gastos' => $proveedor[0]->Gastos, 'Ganancia' => $proveedor[0]->Ganancia];
            return $precio;
        }
    }

    public function redondeoDecimal($precioVenta)
    {
        /* Quedo descontinuado
        $x = $precioVenta / 0.05;
        $f = (int)($x);
        $resultdo = $x - $f; */
        //Esta a Prueba estas 2 lineas reemplazan a las 3 comentadas de arriba
        $precioVenta = round($precioVenta, 2);
        $resultdo = Round(($precioVenta / 0.05), 2) - (int)(Round($precioVenta / 0.05, 2));
        while ($resultdo != 0) {
            $precioVenta = $precioVenta - 0.01;
            $precioVenta  = sprintf ("%.2f", $precioVenta);
            $x = $precioVenta / 0.05;
            $x = sprintf ("%.2f", $x);
            $f = (int)($x);
            $resultdo = $x - $f;
            $resultdo = sprintf ("%.2f", $resultdo);
        }
        return $precioVenta;
    }
}