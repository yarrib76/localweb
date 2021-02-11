<?php

namespace Donatella\Http\Controllers\API\BI;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class Vendedoras extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function index()
    {
        return view('bi.vendedoras');

    }
    public function productividad()
    {
        $horaDesde = ' 00:00:00';
        $horaHasta = ' 23:59:59';
        $ultActuDesde = Input::get('FechaDesde');
        $ultActuDesde = $ultActuDesde . $horaDesde;
        $ultActuHasta = Input::get('FechaHasta');
        $ultActuHasta = $ultActuHasta . $horaHasta;

        $query = DB::select('SELECT vendedora as ResulVendedora,
                                (select count(*) as cantPedidos
                                FROM samira.controlpedidos
                                where ultactualizacion > "' . $ultActuDesde .'" and ultactualizacion < "'. $ultActuHasta .'"
                                and total > 1
                                and estado <> 2
                                and ordenWeb > 0
                                and vendedora = ResulVendedora
                                ) as cantPedidos,
                                (SELECT count(*) as Cant FROM
                                    (SELECT * FROM samira.controlpedidos
                                    where ultactualizacion > "' . $ultActuDesde .'" and ultactualizacion < "'. $ultActuHasta .'"
                                    and total > 1
                                    and estado <> 2
                                    and ordenWeb > 0
                                    group by Day(ultactualizacion)) as cant) as Dias,
                                (select round(sum( cantPedidos / Dias),1)) as Promedio,
                                (select round(sum(total),2)
                                    FROM samira.controlpedidos
                                    where ultactualizacion > "'. $ultActuDesde .'" and ultactualizacion < "'. $ultActuHasta .'"
                                    and total > 1
                                    and estado <> 2
                                    and ordenWeb > 0
                                    and vendedora = ResulVendedora) as TotalFacturado,
                                (select round(sum( TotalFacturado / cantPedidos),1)) as PromedioFacturado,
                                (SELECT count(pedidotmp.Cantidad)
                                    FROM samira.controlpedidos
                                    inner join samira.pedidotemp as pedidotmp ON pedidotmp.nropedido = controlpedidos.nropedido
                                    where ultactualizacion >  "'. $ultActuDesde .'" and ultactualizacion < "'. $ultActuHasta .'"
                                    and total > 1
                                    and ordenWeb > 0
                                    and controlpedidos.estado <> 2
                                    and controlpedidos.vendedora = ResulVendedora) as cantArticulos,
                                (select round(sum( cantArticulos / cantPedidos),1)) as PromedioCantArticulos
                        FROM samira.controlpedidos
                        where ultactualizacion >  "'. $ultActuDesde .'" and ultactualizacion < "'. $ultActuHasta .'"
                        and total > 1
                        and estado <> 2
                        and ordenWeb > 0
                        group by vendedora;');
        return $query;
    }

    public function pedidosPendientes()
    {
        $pedidosPendientes = DB::select('SELECT count(*) as pedidosPendientes FROM samira.controlpedidos
                                        where total < 1
                                        and estado = 1
                                        and fecha  > "2020-05-01"');
        return $pedidosPendientes;
    }
}
