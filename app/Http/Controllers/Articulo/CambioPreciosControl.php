<?php

namespace Donatella\Http\Controllers\Articulo;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class CambioPreciosControl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }

    public function index()
    {
        return view('articulos.cambioprecios');
    }


    public function proceso()
    {
        $proveedores = Input::get('proveedores');
        $calculo = Input::get('calculo');
        $tipo = Input::get('tipo');
        $proveedoresFormateados = $this->formateoProveedores($proveedores);
        if ($tipo == 'verificacion'){
            $resultado = $this->verificacion($proveedoresFormateados,$calculo);
            return $resultado;
        }
        if ($tipo == 'produccion') {
            $resultado = $this->produccion($proveedoresFormateados,$calculo);
            return $resultado;
        }
    }
    public function verificacion($proveedores,$calculo)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        //Solo si el cambio de precio es por porcentaje, ejemplo China Ana
        if ($calculo['tipo'] == 'porcentaje'){
            $valor = $calculo['valor'];
            $resultadoPreview = DB::select("SELECT :fecha as Fecha, Articulo, PrecioConvertido as PrecioConvertidoViejo,
                                IF(PrecioManual IS NULL OR PrecioManual = 0, ROUND(precioConvertido * $valor, 2), precioConvertido) AS nuevoPrecioConvertido,
                                PrecioManual as PrecioManualViejo, IF(PrecioManual IS NOT NULL AND PrecioManual <> 0, ROUND(precioManual * $valor, 2), precioManual) AS nuevoPrecioManual,
                                PrecioOrigen as PrecioOrigenViejo, ROUND(precioOrigen * $valor, 2) AS nuevoPrecioOrigen, proveedor as Proveedor
                                FROM articulos
                                WHERE Proveedor IN ($proveedores)", ['fecha' => $fecha]);

        }
        //Solo si el cambio de precios es en dolares, ejemplo marcelo.
        if ($calculo['tipo'] == 'dolar' and $calculo['porcentajeDescuento'] == ""){
            $valor = $calculo['valor'];
            $resultadoPreview = DB::select("SELECT :fecha as Fecha, Articulo, PrecioConvertido as PrecioConvertidoViejo,
                                IF(PrecioManual IS NULL OR PrecioManual = 0, ROUND(precioOrigen * $valor, 2), precioConvertido) AS nuevoPrecioConvertido,
                                PrecioManual as PrecioManualViejo, IF(PrecioManual IS NOT NULL AND PrecioManual <> 0, ROUND(precioOrigen * $valor, 2), precioManual) AS nuevoPrecioManual,
                                PrecioOrigen as PrecioOrigenViejo, PrecioOrigen AS nuevoPrecioOrigen, proveedor as Proveedor
                                FROM articulos
                                WHERE Proveedor IN ($proveedores)", ['fecha' => $fecha]);
        }
        //En este caso aplica si al cambio de precio hay que hacerle el descuento ejemplo Linda Moda 2
        elseif ($calculo['tipo'] == 'dolar' and $calculo['porcentajeDescuento'] != "") {
            $valor = $calculo['valor'];
            $porcentajeDescuento = $calculo['porcentajeDescuento'];
            $resultadoPreview = DB::select("SELECT :fecha as Fecha, Articulo, PrecioConvertido as PrecioConvertidoViejo,
                                IF(PrecioManual IS NULL OR PrecioManual = 0, ROUND((precioOrigen * $valor)* $porcentajeDescuento, 2), precioConvertido) AS nuevoPrecioConvertido,
                                PrecioManual as PrecioManualViejo, IF(PrecioManual IS NOT NULL AND PrecioManual <> 0, ROUND((precioOrigen * $valor)* $porcentajeDescuento,2), precioManual) AS nuevoPrecioManual,
                                PrecioOrigen as PrecioOrigenViejo, PrecioOrigen AS nuevoPrecioOrigen, proveedor as Proveedor
                                FROM articulos
                                WHERE Proveedor IN ($proveedores)", ['fecha' => $fecha]);
        }
        //Solo si el cambio de precio debe quitar el ultimo porcentaje de aumento y sumar el nuevo ejemplo Zacky
        if ($calculo['tipo'] == 'agregoQuito' ){
            $valorAgrego = $calculo['valorAgrego'];
            $valorQuito = $calculo['valorQuito'];
            $resultadoPreview = DB::select("SELECT  :fecha as Fecha, Articulo, PrecioConvertido as PrecioConvertidoViejo,
                                IF(PrecioManual IS NULL OR PrecioManual = 0, ROUND((precioConvertido / $valorQuito) * $valorAgrego,2), precioConvertido) AS nuevoPrecioConvertido,
                                PrecioManual as PrecioManualViejo, IF(PrecioManual IS NOT NULL AND PrecioManual <> 0, ROUND((precioManual / $valorQuito) * $valorAgrego,2), precioManual) AS nuevoPrecioManual,
                                PrecioOrigen as PrecioOrigenViejo, ROUND((precioOrigen / $valorQuito) * $valorAgrego,2) AS nuevoPrecioOrigen, proveedor as Proveedor
                                FROM articulos
                                WHERE Proveedor IN ($proveedores)",  ['fecha' => $fecha]);

        }

        //Solo si el cambio de precio debe quitar el ultimo dolar de aumento y sumar el nuevo ejemplo Mercaderia comprada en China
        if ($calculo['tipo'] == 'agregoQuitoUSD' ){
            $valorAgregoUSD = $calculo['valorAgregoUSD'];
            $valorQuitoUSD = $calculo['valorQuitoUSD'];
            $resultadoPreview = DB::select("SELECT  :fecha as Fecha, Articulo, PrecioConvertido as PrecioConvertidoViejo,
                                IF(PrecioManual IS NULL OR PrecioManual = 0, ROUND((precioConvertido / $valorQuitoUSD) * $valorAgregoUSD,0), precioConvertido) AS nuevoPrecioConvertido,
                                PrecioManual as PrecioManualViejo, IF(PrecioManual IS NOT NULL AND PrecioManual <> 0, ROUND((precioManual / $valorQuitoUSD) * $valorAgregoUSD,0), precioManual) AS nuevoPrecioManual,
                                PrecioOrigen as PrecioOrigenViejo, precioOrigen AS nuevoPrecioOrigen, proveedor as Proveedor
                                FROM articulos
                                WHERE Proveedor IN ($proveedores)",  ['fecha' => $fecha]);
        }
        return $resultadoPreview;
    }

    public function produccion($proveedores,$calculo)
    {
        //Solo si el cambio de precio es por porcentaje, ejemplo China Ana
        if ($calculo['tipo'] == 'porcentaje' ){
            DB::transaction(function () use ($proveedores,$calculo){
                $valor = $calculo['valor'];
                // Actualiza precioConvertido cuando precioManual es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioConvertido = ROUND(precioConvertido * $valor,2) WHERE (PrecioManual IS NULL OR PrecioManual = 0) AND Proveedor IN ($proveedores)");

                // Actualiza precioManual x 1.5 cuando precioManual no es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioManual = ROUND(precioManual * $valor,2) WHERE PrecioManual IS NOT NULL AND PrecioManual <> 0 AND Proveedor IN ($proveedores)");

                // Actualiza precioOrigen x 1.5 independientemente de las condiciones anteriores y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioOrigen = ROUND(precioOrigen * $valor,2) WHERE Proveedor IN ($proveedores)");
            });
        }
        //Solo si el cambio de precios es en dolares, ejemplo marcelo.
        if ($calculo['tipo'] == 'dolar' and $calculo['porcentajeDescuento'] == ""){
            DB::transaction(function () use ($proveedores,$calculo){
                $valor = $calculo['valor'];
                // Actualiza precioConvertido cuancalculo['porcentajeDescuento']o precioManual es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioConvertido = ROUND(precioOrigen * $valor,2) WHERE (PrecioManual IS NULL OR PrecioManual = 0) AND Proveedor IN ($proveedores)");

                // Actualiza precioManual x 1.5 cuando precioManual no es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioManual = ROUND(precioOrigen * $valor,2) WHERE PrecioManual IS NOT NULL AND PrecioManual <> 0 AND Proveedor IN ($proveedores)");
            });
        }
            //En este caso aplica si al cambio de precio hay que hacerle el descuento ejemplo Linda Moda 2
            elseif ($calculo['tipo'] == 'dolar' and $calculo['porcentajeDescuento'] != "") {
                DB::transaction(function () use ($proveedores,$calculo){
                    $valor = $calculo['valor'];
                    $porcentajeDescuento = $calculo['porcentajeDescuento'];
                    // Actualiza precioConvertido cuando precioManual es nulo y el proveedor está en la lista
                    DB::unprepared("UPDATE articulos SET precioConvertido = ROUND((precioOrigen * $valor)* $porcentajeDescuento,2) WHERE (PrecioManual IS NULL OR PrecioManual = 0) AND Proveedor IN ($proveedores)");

                    // Actualiza precioManual x 1.5 cuando precioManual no es nulo y el proveedor está en la lista
                    DB::unprepared("UPDATE articulos SET precioManual = ROUND((precioOrigen * $valor)* $porcentajeDescuento,2) WHERE PrecioManual IS NOT NULL AND PrecioManual <> 0 AND Proveedor IN ($proveedores)");
                });
            }
        //Solo si el cambio de precio debe quitar el ultimo porcentaje de aumento y sumar el nuevo ejemplo Zacky
        if ($calculo['tipo'] == 'agregoQuito' ){
            DB::transaction(function () use ($proveedores,$calculo){
                $valorAgrego = $calculo['valorAgrego'];
                $valorQuito = $calculo['valorQuito'];
                // Actualiza precioConvertido cuando precioManual es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioConvertido = ROUND((precioConvertido / $valorQuito) * $valorAgrego,2) WHERE (PrecioManual IS NULL OR PrecioManual = 0) AND Proveedor IN ($proveedores)");

                // Actualiza precioManual cuando precioManual no es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioManual = ROUND((precioManual / $valorQuito) * $valorAgrego,2) WHERE PrecioManual IS NOT NULL AND PrecioManual <> 0 AND Proveedor IN ($proveedores)");

                // Actualiza precioOrigen independientemente de las condiciones anteriores y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioOrigen = ROUND((precioOrigen / $valorQuito) * $valorAgrego,2) WHERE Proveedor IN ($proveedores)");
            });
        }
        //Solo si el cambio de precio debe quitar el ultimo dolar de aumento y sumar el nuevo ejemplo Mercaderia comprada en China
        if ($calculo['tipo'] == 'agregoQuitoUSD' ){
            DB::transaction(function () use ($proveedores,$calculo){
                $valorAgregoUSD = $calculo['valorAgregoUSD'];
                $valorQuitoUSD = $calculo['valorQuitoUSD'];
                // Actualiza precioConvertido cuando precioManual es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioConvertido = ROUND((precioConvertido / $valorQuitoUSD) * $valorAgregoUSD,0) WHERE (PrecioManual IS NULL OR PrecioManual = 0) AND Proveedor IN ($proveedores)");

                // Actualiza precioManual cuando precioManual no es nulo y el proveedor está en la lista
                DB::unprepared("UPDATE articulos SET precioManual = ROUND((precioManual / $valorQuitoUSD) * $valorAgregoUSD,0) WHERE PrecioManual IS NOT NULL AND PrecioManual <> 0 AND Proveedor IN ($proveedores)");

            });
        }
        $resultado = $this->guardoHistorialCambioPrecio($proveedores,$calculo);
        return $resultado;
    }

    public function formateoProveedores($proveedores)
    {
        //Esta función se utiliza para poder hacer la consulta con la DB::
        function flatten_array($array) {
            $flatten = array();
            array_walk_recursive($array, function($a) use (&$flatten) { $flatten[] = $a; });
            return $flatten;
        }
        $flatten = flatten_array($proveedores);
        $quoted_elements = array_map(function($element){ return '"'.addslashes($element).'"'; }, $flatten);
        $string_coma = implode(",", $quoted_elements);

        return $string_coma;
    }

    public function guardoHistorialCambioPrecio($proveedores,$calculo)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        // la función explode, que divide una cadena en elementos basados en un delimitador, que en este caso es la coma y lo convierte en array
        $proveedores = explode(',',$proveedores);
        foreach ($proveedores as $proveedor){
            //la función trim, en este caso quita las commilas del texto.
            $proveedor = trim($proveedor, '"');
            if ($calculo['tipo'] == 'agregoQuito'){
                DB::select('INSERT INTO samira.historico_cambio_precios
                    (fecha,
                    proveedor,
                    tipo,
                    porcentaje_descuento,
                    valor_agrego,
                    valor_quito)
                    VALUES
                    ("'.$fecha.'","'.$proveedor.'","'.$calculo['tipo'].'","'.$calculo['porcentajeDescuento'].'","'.$calculo['valorAgrego'].'","'.$calculo['valorQuito'].'");
                    ');
            }elseif ($calculo['tipo'] == 'agregoQuitoUSD') {
                DB::select('INSERT INTO samira.historico_cambio_precios
                    (fecha,
                    proveedor,
                    tipo,
                    porcentaje_descuento,
                    valor_agrego,
                    valor_quito)
                    VALUES
                    ("' . $fecha . '","' . $proveedor . '","' . $calculo['tipo'] . '","' . $calculo['porcentajeDescuento'] . '","' . $calculo['valorAgregoUSD'] . '","' . $calculo['valorQuitoUSD'] . '");
                    ');
            }else {
                DB::select('INSERT INTO samira.historico_cambio_precios
                    (fecha,
                    proveedor,
                    tipo,
                    porcentaje_descuento,
                    valor)
                    VALUES
                    ("'.$fecha.'","'.$proveedor.'","'.$calculo['tipo'].'","'.$calculo['porcentajeDescuento'].'","'.$calculo['valor'].'");
                    ');
            }

        }
        return "Terminado";
    }

    public function reporteCambioPrecioHistorico()
    {
        $datos = DB::select('SELECT fecha,proveedor,tipo,porcentaje_descuento,valor,valor_agrego,valor_quito
                              FROM samira.historico_cambio_precios;');
        return Response::json($datos);
    }
}
