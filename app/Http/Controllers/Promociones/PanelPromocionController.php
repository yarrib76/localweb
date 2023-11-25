<?php

namespace Donatella\Http\Controllers\Promociones;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PanelPromocionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Ventas,Caja');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        DB::statement("SET lc_time_names = 'es_ES'");
        $promociones = DB::select('SELECT CONCAT (cliente.nombre, "," , cliente.apellido) as Nombre,cliente.id_clientes as Id,
                                    SUM(CASE WHEN promocion.fecha_vencimiento < "'.$fecha.'" AND promocion.estado = 2 THEN 1 ELSE 0 END) as Vencido,
                                    SUM(CASE WHEN promocion.fecha_vencimiento >= "'.$fecha.'" AND promocion.estado = 2 THEN 1 ELSE 0 END) as Activo,
                                    SUM(CASE WHEN promocion.estado = 3 THEN 1 ELSE 0 END) as Finalizado,
                                    SUM(CASE WHEN promocion.estado = 1 THEN 1 ELSE 0 END) as Espera
                                    FROM samira.promocion as promocion
                                    INNER JOIN samira.clientes as cliente ON promocion.id_cliente = cliente.id_clientes
                                    WHERE cliente.id_clientes <> 1
                                    GROUP BY cliente.id_clientes;');
        return view('promociones.panel', compact('promociones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
