<?php
/**
 * Created by PhpStorm.
 * User: yarrib76
 * Date: 5/9/18
 * Time: 13:59
 */

namespace Donatella\Http\Controllers\Articulo;


use Donatella\Http\Controllers\Controller;
use Donatella\Models\Articulos;
use Donatella\Models\Compras;
use Illuminate\Support\Facades\DB;

class Resolissue extends Controller
{
    public function run()
    {
        //Esta funcion se utilizo para corregir los numero de Articulos cortos
        dd('Funcion Incativa, consultar con Yamil');
        $articulos = Articulos::all();
        //Quitar 4 primero y 1 final substr($p1, 4,8) 00000010;
        foreach ($articulos as $articulo){
            $articuloTemp = substr($articulo['Articulo'], 4,8);
            DB::select('UPDATE samira.compras SET Articulo = "'.$articulo['Articulo'].'", TipoOrden = 2
                        WHERE Articulo = "'.$articuloTemp.'"');
        }
        dd('Proceso Finalizado');
    }
}