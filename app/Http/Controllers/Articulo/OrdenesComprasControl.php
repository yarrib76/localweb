<?php

namespace Donatella\Http\Controllers\Articulo;

use Carbon\Carbon;
use Donatella\Models\Notas_Control_Ordenes;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class OrdenesComprasControl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function index()
    {
        $user_id = Auth::user()->id;
        return view('articulos.ordenescontrol', compact('user_id'));
    }

    public function consulta()
    {
        $nroOrden = Input::get('nroOrden');
        $resultado = DB::Select('SELECT id_compra,OrdenCompra AS OrdenCompra, ordenCompra1.Articulo, ordenCompra1.Detalle, ordenCompra1.Cantidad as Cantidad, DATE_FORMAT(FechaCompra, "%Y-%m-%d") as Fecha,
                        repoArt.PrecioVenta as PVenta, Observaciones,
                        (select count(*) from samira.notas_control_orden
                                      where id_compras = id_compra) as cant_notas,
                        ordenControlada
                        FROM samira.compras as ordenCompra1
                        inner join samira.reportearticulo as repoArt
                        ON ordenCompra1.Articulo = repoArt.Articulo
                        where TipoOrden IS NOT NULL
                        and ordenCompra = "'.$nroOrden.'"
                        and TipoOrden = 2 and ordenCompra1.Cantidad <> 0
                        ORDER BY OrdenCompra DESC, FechaCompra DESC;');
        return Response::json($resultado);
    }

    public function consultaTodas()
    {
        $resultados = DB::Select('SELECT id_compra,OrdenCompra AS OrdenCompra, ordenCompra1.Articulo, ordenCompra1.Detalle, ordenCompra1.Cantidad as Cantidad, DATE_FORMAT(FechaCompra, "%Y-%m-%d") as Fecha,
                        repoArt.PrecioVenta as PVenta, Observaciones,
                        (select count(*) from samira.notas_control_orden
                                      where id_compras = id_compra) as cant_notas,
                        ordenControlada
                        FROM samira.compras as ordenCompra1
                        inner join samira.reportearticulo as repoArt
                        ON ordenCompra1.Articulo = repoArt.Articulo
                        where TipoOrden IS NOT NULL
                        and TipoOrden = 2 and ordenCompra1.Cantidad <> 0
                        ORDER BY OrdenCompra DESC, FechaCompra DESC;');
        return Response::json($resultados);
    }

    public function notas()
    {
        $id_compra = Input::get('id_compra');
        $notas = DB::Select('SELECT name as nombre, notas as comentario, DATE_FORMAT(fecha_creacion, "%d de %M %Y %k:%i") AS fecha FROM samira.compras
                                inner join samira.notas_control_orden as nota_compra ON nota_compra.id_compras = id_compra
                                inner join samira.users ON id = nota_compra.users_id
                                where id_compra = "'.$id_compra.'"
                                ORDER BY fecha_creacion DESC;');

        return Response::json($notas);
    }

    public function agregarNotas()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        Notas_Control_Ordenes::create([
            'id_compras' => Input::get('id_compra'),
            'users_id' => Input::get('user_id'),
            'notas' => Input::get('textarea'),
            'fecha_creacion' => $fecha
        ]);
        return Response::json('ok');
    }

    public function finalizarControl()
    {
        $estado = Input::get('estado');
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        $id_compra = Input::get('id_compra');
        $ordenControlada = DB::select('select ordenControlada from samira.compras WHERE id_compra = "'.$id_compra.'" ');

        // DB::Select('update samira.compras SET fechaControl = "'.$fecha.'", ordenControlada = "'.$estado.'" where id_compra = "'.$id_compra.'"');

        if ($ordenControlada[0]->ordenControlada == 1 || $ordenControlada[0]->ordenControlada == 2){
            DB::Select('update samira.compras SET fechaControl = "'.$fecha.'", ordenControlada = 0 where id_compra = "'.$id_compra.'"');
        }else DB::Select('update samira.compras SET fechaControl = "'.$fecha.'", ordenControlada = "'.$estado.'" where id_compra = "'.$id_compra.'"');

        return "OK";
    }

}
