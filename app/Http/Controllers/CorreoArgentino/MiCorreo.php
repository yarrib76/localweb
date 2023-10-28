<?php

namespace Donatella\Http\Controllers\CorreoArgentino;

use Donatella\Models\Pub_sucursales;
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
        $this->middleware('role:Gerencia,Caja');
    }

    public function index()
    {
        return view('correoargentino.reportemicorreo');
    }

    public function listarEvios()
    {
        $empaquetados = $this->obtengoEmpaquetados();
        $this->crearEnvios($empaquetados);
        $pedidosEmpaquetados = DB::select('select * from samira.mi_correo');
        ob_start('ob_gzhandler');
        return Response::json($pedidosEmpaquetados);
    }
    public function obtengoEmpaquetados()
    {
        $empaquetados = DB::select('select nropedido,concat(clientes.nombre," ", apellido) as nombre, vendedora, direccion,localidad, replace(telefono,"+54","") as cel
                                    ,mail, provincias.nombre as provincia, codigopostal, total, transporte, ordenweb, pub_sucursales.codigo_provincia
                                    from samira.controlpedidos as pedidos
                                    INNER JOIN samira.clientes as clientes ON clientes.id_clientes = pedidos.id_cliente
                                    INNER JOIN samira.provincias ON provincias.id = clientes.id_provincia
                                    INNER JOIN samira.pub_sucursales ON pub_sucursales.id_provincias = provincias.id
                                    where pedidos.estado = 0 and pedidos.empaquetado = 1
                                    and (pedidos.transporte = "Domicilio" or  pedidos.transporte = "Sucursal")
                                    group by (nropedido)');
        return $empaquetados;
    }

    public function crearEnvios($empaquetados)
    {
        foreach ($empaquetados as $pedido){
            $total = ($pedido->total * 4);
            $nombre = $this->quitar_tildes($pedido->nombre);
            //Verifico si ya fue creado el pedido, si no existe lo creo
            $verificoExistencia = DB::select('select * from samira.mi_correo where nropedido = "'.$pedido->nropedido.'"');
            if (!$verificoExistencia) {
                DB::select('INSERT INTO samira.mi_correo
                            (tipo_producto,largo,ancho,altura,peso,valor_del_contenido,provincia_destino,localidad_destino,calle_destino,
                            codpostal_destino,destino_nombre,destino_email,cod_area_cel,
                            cel,nropedido,vendedora,tipo_envio,ordenweb,transporte,provincia,sucursal_destino,cod_area_tel,tel,
                            altura_destino,dpto,piso)
                            VALUES
                            ("CP","10","10","10","1","' . $total . '","' . $pedido->codigo_provincia . '","' . $this->quitar_tildes($pedido->localidad) . '",
                            "' . $this->quitar_tildes($pedido->direccion) . '","' . $pedido->codigopostal . '","' . $nombre . '",
                            "' . $pedido->mail . '","'.substr($pedido->cel,0,3).'","' . substr($pedido->cel,-8) . '","' . $pedido->nropedido . '","' . $pedido->vendedora . '",
                            "' . $pedido->transporte . '","' . $pedido->ordenweb . '","' . $pedido->transporte . '","' . $pedido->provincia . '","","","",
                            "","","");');
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
    }

    public function sucursalesDestinos(){
        $codigo_provincia = Input::get('codigo_provincia');
        $datos = Pub_sucursales::where('codigo_provincia','=', $codigo_provincia)->orderBy('nombre_sucursal', 'asc')->get();
        for ($i = 0; $i < $datos->count(); $i++ ){
            $arrEstadosFinanciera[$i] = [$datos[$i]->codigo_sucursal => $datos[$i]->nombre_sucursal];
        }
        return Response::json($arrEstadosFinanciera);
    }

    public function eliminarEnvio()
    {
        $id_mi_correo = Input::get("id_mi_correo");
        DB::select('delete from samira.mi_correo where id_mi_correo = "'.$id_mi_correo.'"');
        return "OK";
    }
}
