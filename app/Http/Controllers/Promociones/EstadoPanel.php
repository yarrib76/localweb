<?php

namespace Donatella\Http\Controllers\Promociones;

use Carbon\Carbon;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Donatella\Models\Clientes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class EstadoPanel extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function index()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        $estado = Input::get();
        switch ($estado['tipo'])
        {
            case "Espera" : $promociones =  $this->espera($estado['id_cliente']);
                            $cliente = Clientes::where('id_clientes', '=',$estado['id_cliente'])->get();
                            $cliente = ($cliente[0]['nombre'] . ',' . $cliente[0]['apellido'] );
                            $tipo = "en espera";
                            return view('promociones.tipos', compact('promociones','cliente','tipo'));
                break;
            case "Finalizado" : $promociones = $this->finalizado($estado['id_cliente']);
                                $cliente = Clientes::where('id_clientes', '=',$estado['id_cliente'])->get();
                                $cliente = ($cliente[0]['nombre'] . ',' . $cliente[0]['apellido'] );
                                $tipo = "finalizadas";
                                return view('promociones.tipos', compact('promociones','cliente','tipo'));
                break;
            case "Activo" : $promociones = $this->activo($estado['id_cliente'],$fecha);
                            $cliente = Clientes::where('id_clientes', '=',$estado['id_cliente'])->get();
                            $cliente = ($cliente[0]['nombre'] . ',' . $cliente[0]['apellido'] );
                            $tipo = "activas";
                            return view('promociones.tipos', compact('promociones','cliente','tipo'));
                break;
            case "Vencido" :$promociones =$this->vencido($estado['id_cliente'],$fecha);
                            $cliente = Clientes::where('id_clientes', '=',$estado['id_cliente'])->get();
                            $cliente = ($cliente[0]['nombre'] . ',' . $cliente[0]['apellido'] );
                            $tipo = "vencidas";
                            return view('promociones.tipos', compact('promociones','cliente','tipo'));
                break;
            case "Total" : $promociones = $this->total($estado['id_cliente']);
                            $cliente = Clientes::where('id_clientes', '=',$estado['id_cliente'])->get();
                            $cliente = ($cliente[0]['nombre'] . ',' . $cliente[0]['apellido'] );
                            $tipo = "totales";
                            return view('promociones.tipos', compact('promociones','cliente','tipo'));
                break;
        }
    }
    public function espera($id_cliente)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $promociones = DB::select('SELECT CONCAT (cliente.nombre, "," , cliente.apellido) as Nombre,promocion.id as Promocion_Id,
        DATE_FORMAT(promocion.fecha_creacion, "%d de %M %Y") as FechaCreacion, DATE_FORMAT(promocion.fecha_vencimiento, "%d de %M %Y") as FechaVencimiento,
        promocion.detalle  as Detalle, promocion.codautorizacion as CodAutorizacion
        FROM samira.promocion as promocion
        INNER JOIN samira.clientes as cliente ON promocion.id_cliente = cliente.id_clientes
        WHERE cliente.id_clientes = "'.$id_cliente .'" and promocion.estado = 1');

        return $promociones;
    }

    public function finalizado($id_cliente)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $promociones = DB::select('SELECT CONCAT (cliente.nombre, "," , cliente.apellido) as Nombre,promocion.id as Promocion_Id, promocion.NroFactura as NroFactura,
        DATE_FORMAT(promocion.fecha_creacion, "%d de %M %Y") as FechaCreacion, DATE_FORMAT(promocion.fecha_vencimiento, "%d de %M %Y") as FechaVencimiento,
        promocion.detalle  as Detalle, promocion.codautorizacion as CodAutorizacion
        FROM samira.promocion as promocion
        INNER JOIN samira.clientes as cliente ON promocion.id_cliente = cliente.id_clientes
        WHERE cliente.id_clientes = "'.$id_cliente .'" and promocion.estado = 3');

        return $promociones;
    }

    public function activo($id_cliente,$fecha)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $promociones = DB::select('SELECT CONCAT (cliente.nombre, "," , cliente.apellido) as Nombre,promocion.id as Promocion_Id,
        DATE_FORMAT(promocion.fecha_creacion, "%d de %M %Y") as FechaCreacion, DATE_FORMAT(promocion.fecha_vencimiento, "%d de %M %Y") as FechaVencimiento,
        promocion.detalle  as Detalle, promocion.codautorizacion as CodAutorizacion
        FROM samira.promocion as promocion
        INNER JOIN samira.clientes as cliente ON promocion.id_cliente = cliente.id_clientes
        WHERE cliente.id_clientes = "'.$id_cliente .'" and promocion.estado = 2 and promocion.fecha_vencimiento >= "'.$fecha.'"');

        return $promociones;
    }

    public function vencido($id_cliente,$fecha)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $promociones = DB::select('SELECT CONCAT (cliente.nombre, "," , cliente.apellido) as Nombre,promocion.id as Promocion_Id,
        DATE_FORMAT(promocion.fecha_creacion, "%d de %M %Y") as FechaCreacion, DATE_FORMAT(promocion.fecha_vencimiento, "%d de %M %Y") as FechaVencimiento,
        promocion.detalle  as Detalle, promocion.codautorizacion as CodAutorizacion
        FROM samira.promocion as promocion
        INNER JOIN samira.clientes as cliente ON promocion.id_cliente = cliente.id_clientes
        WHERE cliente.id_clientes = "'.$id_cliente .'" and promocion.estado = 2 and promocion.fecha_vencimiento < "'.$fecha.'"');

        return $promociones;
    }

    public function total($id_cliente)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $promociones = DB::select('SELECT CONCAT (cliente.nombre, "," , cliente.apellido) as Nombre,promocion.id as Promocion_Id, promocion.estado as Estado,
        DATE_FORMAT(promocion.fecha_creacion, "%d de %M %Y") as FechaCreacion, DATE_FORMAT(promocion.fecha_vencimiento, "%d de %M %Y") as FechaVencimiento,
        promocion.detalle  as Detalle, promocion.codautorizacion as CodAutorizacion
        FROM samira.promocion as promocion
        INNER JOIN samira.clientes as cliente ON promocion.id_cliente = cliente.id_clientes
        WHERE cliente.id_clientes = "'.$id_cliente .'"');

        return $promociones;
    }
}


