<?php

namespace Donatella\Http\Controllers\Reporte;


use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\Dolar;
use Donatella\Models\Proveedores;
use Donatella\Models\ReporteArtiulos;
use Donatella\Models\StatusReportes;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ArticuloProveedores extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function query()
    {
        /*    $articulos = articulos::orderBy("Proveedor")->get();
            $precioAydua = new Precio();
            $articulosPreoveedores[] = [];
            DB::select('truncate table samira.reportearticulo');
            foreach ($articulos as $articulo) {
                if (!is_null($articulo->Proveedor)) {
                    $precio = $precioAydua->query($articulo);
                    $proveedor = Proveedores::where('Nombre', $articulo->Proveedor)->get();
                    ReporteArtiulos::create([
                        'Proveedor' => $proveedor[0]->Nombre,
                        'Pais' => $proveedor[0]->Pais,
                        'Articulo' => $articulo->Articulo,
                        'Detalle' => $articulo->Detalle,
                        'Costo' => $precio[0]['Gastos'],
                        'Ganancia' => $precio[0]['Ganancia'],
                        'Cantidad' => $articulo->Cantidad,
                        'PrecioOrigen' => $articulo->PrecioOrigen,
                        'Moneda' => $articulo->Moneda,
                        'PrecioConvertido' => $articulo->PrecioConvertido,
                        'PrecioManual' => $articulo->PrecioManual,
                        'PrecioArgDolar' => ($articulo->PrecioConvertido * $precio[0]['Gastos']),
                        'PrecioArgenPesos' => ($articulo->PrecioManual * $precio[0]['Gastos']),
                        'PrecioVenta' => $precio[0]['PrecioVenta'],
                        'CotizacionDolar' => Dolar::all()[0]->PrecioDolar
                    ]);
                }
            } */
        // $articulosProveedor = ReporteArtiulos::all();
        $fecha = StatusReportes::all()[0]->Fecha;
        return view('reporte.reportearticuloproveedor_v2', compact('fecha'));
    }
}
