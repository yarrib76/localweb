<?php

namespace Donatella\Http\Controllers\Reporte;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class Vendedoras extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function pedidos()
    {
        $consultas = DB::select ('SELECT ctrl.vendedora,
                                SUM(CASE WHEN ctrl.estado = 1 THEN 1 ELSE 0 END) as "Asignados",
                                SUM(CASE WHEN ctrl.estado = 0 and  ctrl.empaquetado = 1 THEN 1 ELSE 0 END) as Empaquetado,
                                SUM(CASE WHEN ctrl.total < 1 and ctrl.estado = 1  THEN 1 ELSE 0 END) as "EnProceso",
                                SUM(CASE WHEN ctrl.total > 1 and ctrl.estado = 1  THEN 1 ELSE 0 END) as "ParaFacturar"
                                FROM samira.controlpedidos as ctrl
                                inner join samira.vendedores as vendedores ON vendedores.nombre = ctrl.vendedora
                                where ctrl.fecha > "2020-05-01" and
                                ctrl.vendedora not in ("Veronica"," ")
                                and vendedores.tipo <> 0
                                group by vendedora;');
        return view('reporte.reportevendedoras', compact('consultas'));
    }

    public function asignados()
    {
        $user_id = Auth::user()->id;
        $vendedora = Input::get('Vendedora');
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb,
                    pedidos.instancia, clientes.id_clientes, clientes.encuesta, pedidos.pagado
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1 and vendedora = "'. $vendedora .'"
                    group by nropedido');

        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }

    public function enProceso()
    {
        $user_id = Auth::user()->id;
        $vendedora = Input::get('Vendedora');
        DB::statement("SET lc_time_names = 'es_ES'");
        //Lo reemplaze por un StorePrecedure para saber la cantidad de articulos repetidos con la utilidad de pedido efecientes
        /* $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb,
                    pedidos.instancia, clientes.id_clientes, clientes.encuesta
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1 and pedidos.total < 1 and vendedora = "'. $vendedora .'"
                    group by nropedido');
        */
        //Utilizo esta función para llamar a un store procedure
        $pedidos = $this->consultaVendedorasEnProceso($vendedora);
        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }

    public function paraFacturar()
    {
        $user_id = Auth::user()->id;
        $vendedora = Input::get('Vendedora');
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb,
                    pedidos.instancia, clientes.id_clientes, clientes.encuesta, pedidos.pagado
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    where pedidos.estado = 1 and pedidos.total > 1 and vendedora = "'. $vendedora .'"
                    group by nropedido');

        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }
    public function empaquetados()
    {
        $user_id = Auth::user()->id;
        $vendedora = Input::get('Vendedora');
        DB::statement("SET lc_time_names = 'es_ES'");
        $fecha_actual = date("Y-m-d");
        $fecha_limite = (date("Y-m-d",strtotime($fecha_actual."- 3 days")));

        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden,
                    DATE_FORMAT(facturah.fecha, "%d de %M %Y") FechaFactura, facturah.fecha as fechaParaOrdenFact, nroPedido as nropedido, clientes.nombre as nombre,
                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb,
                    pedidos.instancia, clientes.id_clientes, clientes.encuesta,
                    CASE
                        WHEN "'.$fecha_limite.'" <= facturah.fecha then 1
                        ELSE 2
                    END as vencimiento
                    from samira.controlPedidos as pedidos
                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                    INNER JOIN samira.facturah as facturah ON facturah.NroFactura = pedidos.nrofactura
                    where pedidos.estado = 0 and pedidos.empaquetado = 1 and vendedora = "'. $vendedora .'"
                    group by nropedido');
        $estado = 'Procesados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }

    private function consultaVendedorasEnProceso($vendedora)
    {
        //Utilizo esta conexción para llamar a un Store Procedure
        $con = '';
        $res = array();
        try {
            $con = new \mysqli(env("DB_HOST", "localhost"), env("DB_USERNAME", "root"), env("DB_PASSWORD", 'NetAcc10'), env("DB_DATABASE", "samira"))
            or die('Could not connect to the database server' . mysqli_connect_error());
            if (empty($con))
                throw new \Exception("Connection is only allowed for authorized personnels.", 5001);
        } catch (\Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        $r = $con->query('CALL vendedoras_en_proceso("'.$vendedora.'")');
        while ($row = mysqli_fetch_object($r)) {

            $res[] = $row;
        }
        return $object = (object) $res;
    }
}
