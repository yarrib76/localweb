<?php

namespace Donatella\Http\Controllers\Promociones;

use Carbon\Carbon;
use Donatella\Ayuda\CodAutorizacion;
use Donatella\Http\Requests\PromocionRequestForm;
use Donatella\Models\Clientes;
use Donatella\Models\Promociones;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class PromocionController extends Controller
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

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Clientes::all();
        $codAuto = new CodAutorizacion();
        $codAuto = $codAuto->get_rand_alphanumeric(10);
        $codAuto = (strtoupper($codAuto));
        return view ('promociones.nuevo', compact('clientes','codAuto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromocionRequestForm $request)
    {
        $datos = Input::all();
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        Promociones::create([
            'id_cliente' => $datos['Cliente_id'],
            'fecha_creacion' => $fecha,
            'fecha_vencimiento' => $datos['FechaVencimiento'],
            'estado' => 1,
            'detalle' => $datos['promocion'],
            'codautorizacion' => $datos['CodAuto']
        ]);
        return redirect()->route('panelpromocion.index');
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

    public function activar()
    {
        $nroPromocion = Input::get('nropromocion');
        $promocion = Promociones::where('id', $nroPromocion);
        $promocion->update([
            'estado' => 2,
        ]);
        return Response::json('Ok');
    }

    public function finalizar()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        $datos = Input::all();
        $promocion = Promociones::where('id',$datos['nropromocion']);
        $promocion->update([
            'nrofactura' => $datos['nrofactura'],
            'fecha_cierre' => $fecha,
            'estado' => 3
        ]);
        return Response::json('Ok');
    }

    public function eliminar()
    {
        $nroPromocion = Input::get('nropromocion');
        $promocion = Promociones::where('id', $nroPromocion);
        $promocion->delete();
        return Response::json('Ok');
    }
}
