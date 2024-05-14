<?php

namespace Donatella\Http\Controllers\CorreoArgentino;

use Donatella\Models\Pub_sucursales;
use Donatella\Models\Tipo_Transportes;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class MiCorreo extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }

    public function index()
    {
        // $this->eliminarTodoslosEnvios();
        return view('correoargentino.reportemicorreo');
    }

    public function listarEvios()
    {
        $tipo = Input::get('tipo');
        if ($tipo == "Pagados") {
            $envio = $this->obtengoPedidosPagos();
            $tipo = 1;
        } else {
            $envio = $this->obtengoEmpaquetados();
            $tipo = 0;
        }
        $this->crearEnvios($envio,$tipo);
        $pedidosEmpaquetados = DB::select('select * from samira.mi_correo where tipo = "'.$tipo.'"');
        ob_start('ob_gzhandler');
        return Response::json($pedidosEmpaquetados);
    }
    public function obtengoEmpaquetados()
    {
        $envios = DB::select('select nropedido,concat(clientes.nombre," ", apellido) as nombre, vendedora, clientes.direccion, clientes.localidad, replace(telefono,"+54","") as cel
                                    ,mail, provincias.nombre as provincia, codigopostal, total, transporte, ordenweb, pub_sucursales.codigo_provincia
                                    from samira.controlpedidos as pedidos
                                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                                    INNER JOIN samira.provincias ON provincias.id = clientes.id_provincia
                                    INNER JOIN samira.pub_sucursales ON pub_sucursales.id_provincias = provincias.id
                                    where pedidos.estado = 0 and pedidos.empaquetado = 1
                                    and (pedidos.transporte = "Domicilio" or  pedidos.transporte = "Sucursal")
                                    group by (nropedido)');
        return $envios;
    }

    public function obtengoPedidosPagos()
    {
        $envios = DB::select('select nropedido,concat(clientes.nombre," ", apellido) as nombre, vendedora, clientes.direccion, clientes.localidad, replace(telefono,"+54","") as cel
                                    ,mail, provincias.nombre as provincia, codigopostal, total, transporte, ordenweb, pub_sucursales.codigo_provincia
                                    from samira.controlpedidos as pedidos
                                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                                    INNER JOIN samira.provincias ON provincias.id = clientes.id_provincia
                                    INNER JOIN samira.pub_sucursales ON pub_sucursales.id_provincias = provincias.id
                                    where pedidos.estado = 1 and pedidos.pagado = 1
                                    and (pedidos.transporte = "Domicilio" or  pedidos.transporte = "Sucursal")
                                    group by (nropedido)');
        return $envios;
    }
    public function crearEnvios($envios,$tipo)
    {
        foreach ($envios as $pedido){
            $total = ($pedido->total * 2);
            $nombre = $this->quitar_tildes($pedido->nombre);
            //defino el tipo de envío en CP para envio clasico
            $tipo_producto = "CP";
            //Verifico si ya fue creado el pedido, si no existe lo creo
            $verificoExistencia = DB::select('select * from samira.mi_correo where nropedido = "'.$pedido->nropedido.'"');
            if (!$verificoExistencia) {
                DB::select('INSERT INTO samira.mi_correo
                            (tipo_producto,largo,ancho,altura,peso,valor_del_contenido,provincia_destino,localidad_destino,calle_destino,
                            codpostal_destino,destino_nombre,destino_email,cod_area_cel,
                            cel,nropedido,vendedora,tipo_envio,ordenweb,transporte,provincia,sucursal_destino,cod_area_tel,tel,
                            altura_destino,dpto,piso,tipo)
                            VALUES
                            ("'.$tipo_producto.'","20","10","20","1","' . $total . '","' . $pedido->codigo_provincia . '","' . $this->quitar_tildes($pedido->localidad) . '",
                            "' . $this->quitar_tildes($pedido->direccion) . '","' . $pedido->codigopostal . '","' . $nombre . '",
                            "' . $pedido->mail . '","'.substr($pedido->cel,0,-8).'","' . substr($pedido->cel,-8) . '","' . $pedido->nropedido . '","' . $pedido->vendedora . '",
                            "' . $pedido->transporte . '","' . $pedido->ordenweb . '","' . $pedido->transporte . '","' . $pedido->provincia . '","","","",
                            "","","","'.$tipo.'");');
            }
        }
    }
    function quitar_tildes($cadena) {
        $cadena = utf8_decode($cadena);
        $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã?","Ã„","Ã‹","(",")");
        $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","","");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }

    public function update()
    {
        $datos = Input::all();
        DB::select ('UPDATE samira.mi_correo SET
                    largo = "'.$datos['largo'].'",
                    altura = "'.$datos['altura'].'",
                    ancho = "'.$datos['ancho'].'",
                    peso = "'.$datos['peso'].'",
                    calle_destino = "'.$datos['calle_destino'].'",
                    altura_destino = "'.$datos['altura_destino'].'",
                    piso = "'.$datos['piso'].'",
                    dpto = "'.$datos['dpto'].'",
                    codpostal_destino = "'.$datos['codpostal_destino'].'",
                    destino_nombre = "'.$datos['destino_nombre'].'",
                    cod_area_cel = "'.$datos['cod_area_cel'].'",
                    cel = "'.$datos['cel'].'",
                    sucursal_destino = "'.$datos['sucursal_destino'].'",
                    provincia_destino = "'.$datos['provincia_destino'].'",
                    provincia = "'.$datos['provincia'].'",
                    localidad_destino = "'.$datos['localidad_destino'].'"
                    where id_mi_correo = "'.$datos['id_mi_correo'].'"');

        //Actualizao el codigo postal de un cliente, que tiene asignado un determinado pedido.
        DB::select('UPDATE samira.clientes AS c
                    JOIN samira.controlpedidos AS cp ON c.id_clientes = cp.id_cliente
                    SET c.codigopostal = "'.$datos['codpostal_destino'].'"
                    WHERE cp.nropedido = "'.$datos['nropedido'].'";');
    }

    public function sucursalesDestinos(){
        $codigo_provincia = Input::get('codigo_provincia');
        $datos = Pub_sucursales::where('codigo_provincia','=', $codigo_provincia)->orderBy('localidad', 'asc')->get();
        for ($i = 0; $i < $datos->count(); $i++ ){
            $arrEstadosFinanciera[$i] = [$datos[$i]->codigo_sucursal => $datos[$i]->localidad . " - Direccion: (" . $datos[$i]->direccion . " Nro " . $datos[$i]->nro_calle . " )"];
        }
        return Response::json($arrEstadosFinanciera);
    }

    public function tipo_transportes()
    {
        $transportes = Tipo_Transportes::where('nombre', '=', 'Domicilio')
                                        ->orWhere('nombre','=', 'Sucursal')
                                        ->get();
        for ($i = 0; $i < $transportes->count(); $i++){
            $arrTipoTransporte[$i] = [$transportes[$i]->nombre => $transportes[$i]->nombre];
        }
        return Response::json($arrTipoTransporte);
    }
    public function eliminarEnvio()
    {
        $id_mi_correo = Input::get("id_mi_correo");
        DB::select('delete from samira.mi_correo where id_mi_correo = "'.$id_mi_correo.'"');
        return "OK";
    }

    //Elimino el pedido de miCorreo cuando se pone el pedido como entregado desde pedidos.reportes_v2.blade.php
    public function eliminarEnvioDesdeEntregado()
    {
        $nroPedido = Input::get("nroPedido");
        DB::select('delete from samira.mi_correo where nropedido = "'.$nroPedido.'"');
        return "OK";
    }

    private function eliminarTodoslosEnvios()
    {
        DB::select('delete from samira.mi_correo');
    }
}
