<?php

namespace Donatella\Http\Controllers\TiendaNube;

use Carbon\Carbon;
use Donatella\Ayuda\TnubeConnect;
use Donatella\Http\Controllers\ProveedorEcomerce\TiendaNube;
use Donatella\Models\ControlPedidos;
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
                $crearPedido = $this->verificarOrgen($orden->number);
                // dd(date('Y-m-d',strtotime($orden->created_at)));
                $fecha = date('Y-m-d',strtotime($orden->created_at));
                if ($crearPedido && ($fecha >= '2020-09-10')){
                    echo ('Se puede Crear la Orden' . $orden->number .  "," );
                }
            }
            return Response::json("ok");
        }
    }
    /*Debido a que la API de tienda nube, no puede enviar mas de 200 productos por pagina, lo que hace esta funcion
    es tomar la cantidad de productos que hay en tienda nube y lo divide por la cantidad de productos por pagina. Con
    Esta informaci�n la urilizo en el FOR para solicitar todas las pag�nas que tienen los art�culos*/
    public function obtengoCantConsultas($api,$cantidadPorPaginas)
    {
        $query = $api->get("orders?page=1&per_page=1");
        $cantidadConsultas = (ceil(($query->headers['x-total-count'] / $cantidadPorPaginas)));
        return $cantidadConsultas;
    }

    /*La funcio�n verifica si existe un pedido con el # de orden que llega y devuelve si se puede crear o un nuevo pedido.
    Si devuelve true, se puede crear un pedido porque no existe ning�no con ese # de orden
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
}
