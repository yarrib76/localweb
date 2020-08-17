<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Donatella\Models\FacturacionHist;
use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;


class FacturacionH extends Controller
{
    public function listar(){
        $tipo = Input::get('tipo');
        $fechaInicio = Input::get('FechaInicio');
        $fechaFin = Input::get('FechaFin');
        $facturasHistoricas = $this->facturas($fechaInicio,$fechaFin,$tipo);
        return $facturasHistoricas;
    }


    public function cajaMin()
    {
        $datos = $this->buscoDiasParaAtas(6);
        $datos = $this->eliminoNulls($datos);
        return Response::json($datos);
    }

    public function facturas($fechaInicio,$fechaFin,$tipo)
    {
        return FacturacionHist::where('estado',$tipo)
            ->where('Fecha', '>=', $fechaInicio)
            ->where('Fecha', '<=', $fechaFin )->get();
    }


    /*
     * Le paso a la funciòn los dias que quiero ir para atràs ($dias)
     * luego realizo con un for y voy para atras los dias que pase mientras hago el query por esos dias.
     * Ejemplo: si paso 6 y hoy es Sabado, hago el query para lo que se facturo el Sabado, viernes, Jueves,
     * Miercoles, Martes y Lunes. En el query si hay descuento sumo el descuento mas el total de los que no
     * tienen descuento.
     */
    private function buscoDiasParaAtas($dias)
    {
        $datos[] = "";
        for ($i = 0; $i < $dias; $i++)
        {
            $date = Carbon::now();
            $date->subDay($i);
            $date = (Carbon::parse($date)->format('Y-m-d'));
            $query = DB::select('SELECT ROUND(SUM(CASE WHEN Descuento <> "null" OR Descuento = 0 THEN Descuento ELSE total END),2) as total from samira.facturah
                 where estado <> 2 and fecha = "' . $date . ' " ');
            $query = $this->conviertoQueryEnArray($query);
            $datos[$i] = ['Fecha' =>$this->traductorDias(Carbon::parse($date)->format('l')),'Total' =>$query];
        }
        return $datos;
    }
    public function traductorDias($diaSemana)
    {
        switch($diaSemana)
        {
            Case 'Monday':
                return 'Lunes';
                break;
            Case 'Tuesday':
                return 'Martes';
                break;
            Case 'Wednesday':
                return 'Miercoles';
                break;
            Case 'Thursday':
                return 'Jueves';
                break;
            Case 'Friday':
                return 'Viernes';
                break;
            Case 'Saturday':
                return 'Sabado';
                break;
            Case 'Sunday':
                return 'Domingo';
                break;
        }
    }

    private function eliminoNulls($datos)
    {
        for ($i = 0; $i < 6; $i++)
        {

            if ($datos[$i]['Total'][0]['total'] == null)
            {
                $datos[$i]['Total'][0]['total'] = 0;
            }
        }
        return $datos;
    }

    private function conviertoQueryEnArray($query)
    {
        $arr = [];
        foreach($query as $row)
        {
            $arr[] = (array) $row;
        }
        return $arr;
    }
}
