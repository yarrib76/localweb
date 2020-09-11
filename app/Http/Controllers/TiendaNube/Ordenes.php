<?php

namespace Donatella\Http\Controllers\TiendaNube;

use Carbon\Carbon;
use Donatella\Ayuda\TnubeConnect;
use Donatella\Http\Controllers\ProveedorEcomerce\TiendaNube;
use Donatella\Models\Clientes;
use Donatella\Models\ControlPedidos;
use Donatella\Models\NroPedidos;
use Donatella\Models\Provincias;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use TiendaNube\API;
use TiendaNube\Auth;

class Ordenes extends Controller
{
    public function main()
    {
        /*
        $code = '7a1ae9c219ddbc8661353b6f6e39f1c33f874cd0';
        $auth = new Auth(1358, 'WcuW5hyGiiPPqpnC5OEVOmg0r7oDjUcvlIXLEphoAanRFVd5');
        $store_info = $auth->request_access_token($code);
        dd($store_info);
        */

        $cantidadPorPaginas = 200;
        $store_id = Input::get('store_id');
        $tnConnect = new TnubeConnect();
        $connect = $tnConnect->getConnectionTN($store_id);
        $api = new API($store_id, $connect[0]['access_token'], $connect[0]['appsName']);
        $cantidadConsultas = $this->obtengoCantConsultas($api,$cantidadPorPaginas);
        $result = $this->getOrdenes($api,$cantidadConsultas,$cantidadPorPaginas);
        return $result;
    }

    public function getOrdenes($api,$cantidadConsultas,$cantidadPorPaginas)
    {

        // $fechaActual = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        for ($i = 1; $i <= $cantidadConsultas; $i++) {
            $ordenesTiendaNube = $api->get("orders?page=$i&per_page=$cantidadPorPaginas");
            // dd($ordenesTiendaNube->body);
            foreach ($ordenesTiendaNube->body as $orden) {
                // dd($orden);
                $crearPedido = $this->verificarOrgen($orden->number);
                $fecha = date('Y-m-d',strtotime($orden->created_at));
                if ($crearPedido && ($fecha >= '2020-09-10')){
                    echo ('Se puede Crear la Orden' . $orden->number .  "," );
                    $cliente_id = $this->verificarCliente($orden);
                    $this->crearPedido($cliente_id,$orden);
                }
            }
            return Response::json("ok");
        }
    }
    /*Debido a que la API de tienda nube, no puede enviar mas de 200 productos por pagina, lo que hace esta funcion
    es tomar la cantidad de productos que hay en tienda nube y lo divide por la cantidad de productos por pagina. Con
    Esta informacion la urilizo en el FOR para solicitar todas las paginas que tienen los articulos*/
    public function obtengoCantConsultas($api,$cantidadPorPaginas)
    {
        $query = $api->get("orders?page=1&per_page=1");
        $cantidadConsultas = (ceil(($query->headers['x-total-count'] / $cantidadPorPaginas)));
        return $cantidadConsultas;
    }

    /*La funcioon verifica si existe un pedido con el # de orden que llega y devuelve si se puede crear o un nuevo pedido.
    Si devuelve true, se puede crear un pedido porque no existe ninguno con ese # de orden
    Si devuelve false, no se puede crear ya que hay un pedido con ese # de orden */
    private function verificarOrgen($nroOrden)
    {
        $pedido = ControlPedidos::where('ordenWeb',$nroOrden)->get();
        if ($pedido->isEmpty()) {
            return true;
        }else {
            return false;
        }
    }

    /*La funcioon verifica si existe el cliente, verificando el mail y devuelve si se puede crear o un nuevo cliente.
    Si devuelve true, se puede crear el cliente porque no existe ninguno con ese mail
    Si devuelve false, no se puede crear ya que hay un cliente con esa direccion de mail */
    private function verificarCliente($orden){
        $cliente = Clientes::where('mail',$orden->customer->email)->get();
        if ($cliente->isEmpty()){
            $cliente_id = $this->crearCliente($orden->customer);
            return $cliente_id;
        }else {
            return $cliente[0]->id_clientes;
        }
    }

    private function crearCliente($datos){
        $id_Provincia = $this->getProvincia_id($datos->billing_province);
        $nombre = (substr($datos->name, 0, strrpos($datos->name, ' ') + 0));
        $apellido = (substr($datos->name, strrpos($datos->name, ' ') + 1, strlen($datos->name) + 1));
        $direccion = ($datos->default_address->address . " " . $datos->default_address->number);
        $email = $datos->email;
        $telefono = ($datos->phone);
        $dni_cuit = ($datos->identification);
        $ciudad = ($datos->default_address->city);
        $cliente_id = Clientes::create([
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Apodo" => "",
            "Direccion" => $direccion,
            "Mail" => $email,
            "Telefono" => $telefono,
            "Cuit" => $dni_cuit,
            "Localidad" => $ciudad,
            "Provincia" => "",
            "Id_provincia" => $id_Provincia
        ]);
        return $cliente_id->id;
    }

    private function getProvincia_id ($provincia){
        $id = Provincias::where('nombre',$provincia)->get();
        if ($id){
            return $id[0]->id;
        }else {
            return 1;
        }
    }

    private function crearPedido($cliente_id,$orden){
        dd($cliente_id);
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        
    }
}
