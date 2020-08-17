<?php

namespace Donatella\Http\Controllers\Articulo;

use Donatella\Models\Articulos;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class Barcode extends Controller
{
    public function crearCodigo()
    {
        $sku = Input::get('articulo');
        $articulo = Articulos::where('Articulo',$sku)->get();
        $detalle = $this->completoTexto($articulo[0]->Detalle);
        $codigos = ['codigo' => $articulo[0]->Articulo,'texto' => $detalle];
        return view('barcode.muestrabarcode', compact('codigos'));
    }

    public function completoTexto($texto)
    {
        $textoAgregado = "";
        $tamañoText = strlen($texto);

        if ($tamañoText < 29 ){
            $agregarText = 29 - $tamañoText ;
            for ($i = 1; $i <= $agregarText;$i++){
                $textoAgregado = $textoAgregado . '_';
            }
            return ($texto . $textoAgregado);
        }
        return $texto;
    }
}
