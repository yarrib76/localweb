<?php

namespace Donatella\Http\Controllers\Reporte;

use Donatella\Models\Transferencias;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TransferenciasArticulos extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja');
    }
    public function query()
    {
        $transferenciasArticulos = DB::select('SELECT trans.Articulo, arti.Detalle, trans.Cantidad,
                                    if (trans.UbicacionActual = "Local", "Samira", "Donatella")as UbicacionActual,
                                    if (trans.UbicacionNueva = "Local", "Samira", "Donatella") as UbicacionNueva,
                                    trans.Usuario, DATE_FORMAT(trans.Fecha,"%Y/%m/%d") as Fecha
                                    FROM samira.transferencias as trans
                                    inner join samira.articulos as arti On arti.Articulo =  trans.Articulo;');
        return view('reporte.reportetransferenciasarticulos', compact('transferenciasArticulos'));
    }
}
