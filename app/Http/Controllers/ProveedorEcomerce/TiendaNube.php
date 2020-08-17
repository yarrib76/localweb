<?php

namespace Donatella\Http\Controllers\ProveedorEcomerce;

use Donatella\Models\ProvEcomerce;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class TiendaNube extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja');
    }
    public function statusGeneral()
    {
        $provEcomerces = DB::select ('SELECT ecomerce.id as corrida, ecomerce.proveedor, usuario.name as nombre, ecomerce.fecha,
                                    count(status) as total,
                                    SUM(CASE WHEN status.status = "OK" THEN 1 ELSE 0 END) as ok,
                                    SUM(CASE WHEN status.status <> "OK" and status.status <> "Pending" THEN 1 ELSE 0 END) as error,
                                    SUM(CASE WHEN status.status = "Pending" THEN 1 ELSE 0 END) as pending
                                    FROM samira.provecomerce ecomerce
                                    inner join samira.users as usuario ON usuario.id = ecomerce.id_users
                                    inner join samira.statusecomercesincro status ON status.id_provecomerce = ecomerce.id
                                    group by ecomerce.id;');
        return view('tiendanube.reporte', compact('provEcomerces'));
    }

    public function statusPorCorrida()
    {
        $id_corrida = Input::get('id_corrida');
        $proveedor = Input::get('proveedor');
        $nombre_ejecutor = Input::get('nombre');
        $statusEcomerce = DB::select('SELECT statusecomerce.id as e_id, provecomerce.proveedor, usuario.name as nombre, statusecomerce.articulo,
                                     statusecomerce.status,
                                     statusecomerce.fecha, product_id, articulo_id, visible
                                     from samira.statusecomercesincro as statusecomerce
                                     inner join samira.provecomerce as provecomerce ON provecomerce.id = statusecomerce.id_provecomerce
                                     inner join samira.users as usuario ON usuario.id = provecomerce.id_users
                                     where id_provecomerce = "'.$id_corrida.'"');
        return view('tiendanube.reportedetalladonew', compact('statusEcomerce','id_corrida','proveedor','nombre_ejecutor'));

    }
}
