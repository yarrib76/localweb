<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Models\Articulos;
use Donatella\Models\Deposito;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class InArtSincro extends Controller
{
    public function nuevo()
    {
        //$insert(Input::get('misDatos'));
        $articulo = Input::get('Articulo');
        $proveedor = Input::get('Proveedor');
        $detalle = Input::get('Detalle');
        $precioOrigen = Input::get('PrecioOrigen');
        $precioConvertido = Input::get('PrecioConvertido');
        $proveedorSku = Input::get('ProveedorSKU');
        $moneda = Input::get('Moneda');
        $verificador = Articulos::where('Articulo', '=', $articulo)->first();
        if ($verificador['Articulo'] === null){
            $resultado = $this->crearArticulo($articulo,$detalle,$proveedor,$precioOrigen,$moneda,$precioConvertido,$proveedorSku);
            return $resultado;
        }
        return Response::json('El articulo ya existe');
    }
    public function crearArticulo($articulo,$detalle,$proveedor,$precioOrigen,$moneda,$precioConvertido,$proveedorSku)
    {
        Articulos::create([
            'Articulo' => $articulo,
            'Detalle' => $detalle,
            'ProveedorSKU' => $proveedorSku,
            'Cantidad' => 0,
            'PrecioOrigen' => $precioOrigen,
            'PrecioCOnvertido' => $precioConvertido,
            'Moneda' => $moneda,
            'PrecioManual' => 0,
            'Gastos' => 0,
            'Ganancia' => 0,
            'Proveedor' => $proveedor
        ]);

        Deposito::create([
            'Articulo' => $articulo,
            'Detalle' => $detalle,
            'ProveedorSKU' => $proveedorSku,
            'Cantidad' => 0,
            'PrecioOrigen' => $precioOrigen,
            'PrecioCOnvertido' => $precioConvertido,
            'Moneda' => $moneda,
            'PrecioManual' => 0,
            'Gastos' => 0,
            'Ganancia' => 0,
            'Proveedor' => $proveedor
        ]);
        return Response::json('Finalizado');
    }
}
