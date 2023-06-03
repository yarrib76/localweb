<?php

namespace Donatella\Http\Controllers\Personal;

use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;


class Estadisticas extends Controller
{
    protected $anio;
    public function __construct()
    {
        $this->anio = date('Y');
    }
    public function index($id)
    {
        // $pathFoto = DB::select('select foto from samira.users where id= "'.$id.'"');.
        $anioActual = $this->anio;
        return view('personal.estadistica', compact('id','anioActual'));
    }

    public function pedidos()
    {
        $usuario_id = Input::get('usuario_id');
        $pedidos = $this->obtengoPedidos($usuario_id);
        return $pedidos;
    }

    public function ventasSalon()
    {
        $usuario_id = Input::get('usuario_id');
        $ventasSalon = $this->obtengoVentasSalon($usuario_id);
        return $ventasSalon;
    }
    public function obtengoFoto()
    {
        $usuario_id = Input::get('usuario_id');
        $fotoName=DB::select('select foto from samira.users where id="'.$usuario_id.'"');
        return $fotoName;
    }
    public function obtengoPedidos($usuario_id)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT upper(date_format(fecha, "%m")) as mes, count(*) as cantidad FROM samira.users
                                inner join samira.vendedores on vendedores.id = users.id_vendedoras
                                inner join samira.controlpedidos on controlpedidos.vendedora = vendedores.nombre
                                where id_vendedoras <> 31
                                and users.id = "'.$usuario_id.'"
                                and fecha >= "'. $this->anio.'""-01-01" and fecha <= "'. $this->anio.'""-12-31"
                                and nrofactura is not null
                                and ordenWeb is not null
								and ordenWeb <> 0
                                group by (month(fecha));');

        $result = $this->formatoParaGrafico($pedidos);
        return $result;
    }

    public function obtengoVentasSalon($usuario_id)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        // $anio = '2022';
        $ventasSalon = DB::select('SELECT upper(date_format(factura.fecha, "%m")) as mes, count(distinct(factura.nrofactura)) as cantidad FROM samira.factura
                                    left join samira.controlpedidos as ctrl ON ctrl.nrofactura = factura.nrofactura
                                    inner join samira.vendedores On vendedores.nombre = factura.vendedora
                                    inner join samira.users on users.id_vendedoras = vendedores.id
                                    where factura.fecha >= "' .  $this->anio . '""-01-01" and factura.fecha <= "' .  $this->anio . '" "-12-31"
                                    and (ctrl.nrofactura is null
                                    or ctrl.ordenWeb is null
                                    or ctrl.ordenWeb = 0)
                                    and users.id = "' . $usuario_id . '"
                                    group by month(factura.fecha)');

        $result = $this->formatoParaGrafico($ventasSalon);
        return $result;
    }
    public function obtengoDatosPersonales(){
        $usuario_id = Input::get('usuario_id');
        $datos = DB::select('select nombre,apellido from samira.users
                            inner join samira.vendedores on vendedores.id = users.id_vendedoras
                            where users.id = "'.$usuario_id.'"');
        return Response::json($datos);
    }

    public function obtengoCantPedidos()
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $cantidadPedidos = DB::select('select left(upper(date_format(fecha, "%M")),3) mes, count(*) as cantidad from samira.controlpedidos
                                       where fecha >= "'.$this->anio.'""-01-01" and fecha <= "'.$this->anio.'""-12-31"
                                       and nrofactura is not null
                                       and ordenWeb is not null
									   and ordenWeb <> 0
                                       group by (month(fecha));');
        return Response::json($cantidadPedidos);
    }

    public function obtengoVentasSalonTotales(){

        DB::statement("SET lc_time_names = 'es_ES'");
        $cantidadTotalVentasSalon = DB::select('SELECT left(upper(date_format(factura.fecha, "%M")),3) as mes, count(distinct(factura.nrofactura)) as cantidad FROM samira.factura
                                                left join samira.controlpedidos as ctrl ON ctrl.nrofactura = factura.nrofactura
                                                inner join samira.vendedores On vendedores.nombre = factura.vendedora
                                                inner join samira.users on users.id_vendedoras = vendedores.id
                                                where factura.fecha >= "'.$this->anio.'""-01-01" and factura.fecha <= "'.$this->anio.'""-12-31"
                                                and (ctrl.nrofactura is null
                                                or ctrl.ordenWeb is null
                                                or ctrl.ordenWeb = 0)
                                                -- and users.id = 16
                                                group by month(factura.fecha) ');
        return Response::json($cantidadTotalVentasSalon);
    }

    public function obtengoPedidosCancelados(){
        $usuario_id = Input::get('usuario_id');
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidosCancelados = DB::select('select left(upper(date_format(fecha, "%m")),3) mes, count(*) as cantidad from samira.controlpedidos
                            inner join samira.vendedores on vendedores.nombre = controlpedidos.vendedora
                            inner join samira.users on users.id_vendedoras = vendedores.id
                            where fecha >= "'.$this->anio.'""-01-01" and fecha <= "'.$this->anio.'""-12-31"
                                    and estado = 2
                                    and users.id = "'.$usuario_id.'"
                            group by month(controlpedidos.fecha)');

        $result = $this->formatoParaGrafico($pedidosCancelados);
        return $result;
    }

    public function reportePedidosCancelados(){
        $user_id = Input::get('usuario_id');
        DB::statement("SET lc_time_names = 'es_ES'");
        $mes = Input::get('mes');
        $pedidos = DB::select('SELECT DATE_FORMAT(pedidos.fecha, "%d de %M %Y") AS fecha, pedidos.fecha as fechaParaOrden, nroPedido as nropedido, clientes.nombre as nombre,
                                    clientes.apellido as apellido, pedidos.nrofactura, pedidos.vendedora, pedidos.estado, pedidos.id as id, pedidos.total as total,
                                    pedidos.ordenweb as ordenweb, comentarios.comentario as comentarios, pedidos.empaquetado as empaquetado, pedidos.transporte as transporte, pedidos.totalweb,
                                    pedidos.instancia, clientes.id_clientes, clientes.encuesta
                                    from samira.controlPedidos as pedidos
                                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                                    left join samira.comentariospedidos as comentarios ON comentarios.controlpedidos_id = pedidos.id
                                    inner join samira.vendedores on vendedores.nombre = pedidos.vendedora
									inner join samira.users on users.id_vendedoras = vendedores.id
                                    where pedidos.estado = 2
                                    and YEAR(pedidos.fecha) = "'.$this->anio.'"
                                    and MONTHNAME(pedidos.fecha) = "'.$mes.'"
                                    and users.id = "'.$user_id.'"
                                    group by nropedido');

        $estado = 'Cancelados';
        return view('pedidos.reporte_v2', compact('pedidos','user_id','estado'));
    }

    public function obtengoCantidadNoEncuesta()
    {
        $usuario_id = Input::get('usuario_id');
        DB::statement("SET lc_time_names = 'es_ES'");
        $noEncuestados = DB::select('select left(upper(date_format(facth.fecha, "%m")),3) mes, count(*) as cantidad from samira.facturah as Facth
                                        left join samira.controlpedidos as Control ON Control.nrofactura = Facth.NroFactura
                                        inner join samira.clientes as clientes on clientes.id_clientes = facth.id_clientes
                                        inner join samira.vendedores On vendedores.nombre = Control.vendedora
                                        inner join samira.users on users.id_vendedoras = vendedores.id
                                        where control.Fecha >= "'.$this->anio.'""-01-01" and control.Fecha <= "'.$this->anio.'""-12-31"
                                            and Control.ordenWeb is Not null
                                            and Control.ordenWeb <> 0
                                            and clientes.encuesta = "Ninguna"
                                            and users.id = "'.$usuario_id.'"
                                            group by month(control.fecha)');
        $result = $this->formatoParaGrafico($noEncuestados);
        return $result;
    }

    public function obtengoCantidadTotalesParaNoEncuesta()
    {
        $usuario_id = Input::get('usuario_id');
        DB::statement("SET lc_time_names = 'es_ES'");
        $cantidadTotalesNoEncuesta = DB::select('select left(upper(date_format(facth.fecha, "%M")),3) mes, count(*) as cantidad from samira.facturah as Facth
                                        left join samira.controlpedidos as Control ON Control.nrofactura = Facth.NroFactura
                                        inner join samira.clientes as clientes on clientes.id_clientes = facth.id_clientes
                                        inner join samira.vendedores On vendedores.nombre = Control.vendedora
                                        inner join samira.users on users.id_vendedoras = vendedores.id
                                        where control.Fecha >= "'.$this->anio.'""-01-01" and control.Fecha <= "'.$this->anio.'""-12-31"
                                            and Control.ordenWeb is Not null
                                            and Control.ordenWeb <> 0
                                            and users.id = "'.$usuario_id.'"
                                            group by month(control.fecha)');
        return Response::json($cantidadTotalesNoEncuesta);
    }

    public function obtengoFidelClientes()
    {
        $usuario_id = Input::get('usuario_id');
        $consulta_fidel = DB::select('SELECT vendedora, nombre_etapa, fecha_creacion, count(*) as cantidad FROM samira.clientes_fidelizacion as cliFidel
                                        inner join samira.clientes_fidel_etapas as etapas ON etapas.id_clientes_fidel_etapas = cliFidel.id_clientes_fidel_etapas
                                        inner join samira.vendedores ON vendedores.nombre = cliFidel.vendedora
                                        inner join samira.users ON users.id_vendedoras = vendedores.id
                                        where estado = 1
                                        and users.id = "'.$usuario_id.'"
                                        and year(fecha_creacion) = "'.$this->anio.'"
                                        group by nombre_etapa;');
        $result[] = ['Etapa','Cantidad'];
        foreach ($consulta_fidel as $key => $value) {
            $result[++$key] = [$value->nombre_etapa, (int)$value->cantidad];
        }
        return json_encode($result);
    }
    public function formatoParaGrafico($datos)
    {
        $result[] = ['Mes', 'Cantidad'];
        $result = $this->llenoArray($result);
        foreach ($datos as $key => $value) {
            switch ((int)$value->mes) {
                Case 1:
                    $result[1] = ['Enero', (int)$value->cantidad];
                    break;
                Case 2:
                    $result[2] = ['Febrero', (int)$value->cantidad];
                    break;
                Case 3:
                    $result[3] = ['Marzo', (int)$value->cantidad];
                    break;
                Case 4:
                    $result[4] = ['Abril', (int)$value->cantidad];
                    break;
                Case 5:
                    $result[5] = ['Mayo', (int)$value->cantidad];
                    break;
                Case 6:
                    $result[6] = ['Junio', (int)$value->cantidad];
                    break;
                Case 7:
                    $result[7] = ['Julio', (int)$value->cantidad];
                    break;
                Case 8:
                    $result[8] = ['Agosto', (int)$value->cantidad];
                    break;
                Case 9:
                    $result[9] = ['Septiembre', (int)$value->cantidad];
                    break;
                Case 10:
                    $result[10] = ['Octubre', (int)$value->cantidad];
                    break;
                Case 11:
                    $result[11] = ['Noviembre', (int)$value->cantidad];
                    break;
                Case 12:
                    $result[12] = ['Diciembre', (int)$value->cantidad];
                    break;
            }
        }
        return json_encode($result);
    }
    public function llenoArray($result)
    {
        $result[1] = ['Enero',0];
        $result[2] = ['Febrero',0];
        $result[3] = ['Marzo',0];
        $result[4] = ['Abril',0];
        $result[5] = ['Mayo',0];
        $result[6] = ['Junio',0];
        $result[7] = ['Julio',0];
        $result[8] = ['Agosto',0];
        $result[9] = ['Septiembre',0];
        $result[10] = ['Octubre',0];
        $result[11] = ['Noviembre',0];
        $result[12] = ['Diciembre',0];
        return $result;
    }
}
