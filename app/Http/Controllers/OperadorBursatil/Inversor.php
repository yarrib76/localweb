<?php

namespace Donatella\Http\Controllers\OperadorBursatil;

use Carbon\Carbon;
use Donatella\Http\Controllers\Ia\Bursatil;
use Donatella\Models\Inversiones;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class Inversor extends Controller
{
    public function index()
    {
        return view('operadorbursatil.inversor');
    }

    public function buscarAcciones()
    {
        $apikey = Input::get('apikey');
        $cantidadAcciones = Input::get('cantidad');
        //https://www.alphavantage.co/query?function=TOP_GAINERS_LOSERS&apikey=demo
        $url = "https://www.alphavantage.co/query?function=TOP_GAINERS_LOSERS&apikey=$apikey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // No verificar el certificado SSL (útil para pruebas)
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
        $respuesta = curl_exec($ch);

        $respuestaOrdenada = $this->ordenaRespuesta($respuesta);
        $top5Gainers = array_slice($respuestaOrdenada['top_gainers'], 0, $cantidadAcciones);
        return $top5Gainers;
    }

    public function ordenaRespuesta($respuesta)
    {
        $respuestaArray = json_decode($respuesta, true);

        // Usar una función anónima para comparar precios
        if (isset($respuestaArray['top_gainers'])) {
            usort($respuestaArray['top_gainers'], function($a, $b) {
                $priceA = floatval($a['price']);
                $priceB = floatval($b['price']);

                if ($priceA == $priceB) {
                    return 0;
                }

                return ($priceA < $priceB) ? 1 : -1;
            });
        }

        if (isset($respuestaArray['top_losers'])) {
            usort($respuestaArray['top_losers'], function($a, $b) {
                $priceA = floatval($a['price']);
                $priceB = floatval($b['price']);

                if ($priceA == $priceB) {
                    return 0;
                }

                return ($priceA < $priceB) ? 1 : -1;
            });
        }

        if (isset($respuestaArray['most_actively_traded'])) {
            usort($respuestaArray['most_actively_traded'], function($a, $b) {
                $priceA = floatval($a['price']);
                $priceB = floatval($b['price']);

                if ($priceA == $priceB) {
                    return 0;
                }

                return ($priceA < $priceB) ? 1 : -1;
            });
        }
        return$respuestaArray;
    }

    public function invertir()
    {
        $apikey = Input::get('apikey');
        $empresas = Input::get('empresas');
        $operador = new Bursatil();
        foreach ($empresas as $empresa){
            $inversiones = $operador->inicio($apikey,$empresa);
            // Eliminar los caracteres de formato JSON (```json\n y \n```), si es necesario
            $jsonResponse = preg_replace('/```json\n|\n```/', '', $inversiones);
            // Decodificar el JSON a un array asociativo de PHP
            $data = json_decode($jsonResponse, true);
            $this->insertarDatos($data);
        }

        //dd($data[0]['precioAccion']);
    }

    private function insertarDatos($data)
    {
        $fechaActual = Carbon::now()->format('Y-m-d H:i:s');
        $fechaFinalizacion = Carbon::now();
        $fechaFinalizacion = $fechaFinalizacion->addDays($data[0]['Dias']);
        Inversiones::create([
            'nbr_accion' => $data[0]['Symbol'],
            'recomendacion' => $data[0]['Probabilidad de Ganar'],
            'informeia' => $data[0]['Detalle'],
            'dias_retencion' => $data[0]['Dias'],
            'precio' => $data[0]['precioAccion'],
            'fecha_compra' => $fechaActual,
            'fecha_finalizacion' => $fechaFinalizacion,
        ]);
    }

    public function cargaDatosTabuladorInversiones()
    {
        $listaInversiones = DB::select('select * from samira.inversiones');
        return Response::json($listaInversiones);
    }

    public function actualizar()
    {
        $datos = Input::all();
        $inversion = Inversiones::where('id_inversiones',$datos['id_inversiones']);
        $inversion->update([
            'estado' => $datos['estado'],
            'porcentaje_ganancia' => $datos['porcentaje_ganancia'],
            'precio_venta' => $datos['precio_venta'],
        ]);
    }

    public function listaInversines()
    {
        $tipo = Input::get();
        $datos = DB::select('select * from samira.inversiones where estado = "'.$tipo['tipo'].'"');
        return Response::json($datos);
    }
}
