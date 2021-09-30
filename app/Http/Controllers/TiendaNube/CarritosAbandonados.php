<?php

namespace Donatella\Http\Controllers\TiendaNube;

use Donatella\Ayuda\TnubeConnect;
use Donatella\Models\Carrito_abandonado;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use TiendaNube\API;

class CarritosAbandonados extends Controller
{
    public function main()
    {
        $cantidadPorPaginas = 10;
        $tnConnect = new TnubeConnect();
        $store_id = Input::get('store_id');
        $store_id = 1043936;
        $connect = $tnConnect->getConnectionTN($store_id);
        $api = new API($store_id, $connect[0]['access_token'], $connect[0]['appsName']);
        $cantidadConsultas = $this->obtengoCantConsultas($api,$cantidadPorPaginas);
        $result = $this->getCarritosAbondonados($api,$cantidadConsultas,$cantidadPorPaginas);
        return $result;
    }


    public function getCarritosAbondonados($api,$cantidadConsultas,$cantidadPorPaginas)
    {
        for ($i = 1; $i <= $cantidadConsultas; $i++) {
            $carritosAbandonadosTiendaNube = $api->get("checkouts?page=$i&per_page=$cantidadPorPaginas");
            foreach ($carritosAbandonadosTiendaNube->body as $carrito) {
                $crearCarrito = $this->verificarCarrito($carrito->id);
                if ($crearCarrito){
                    $this->crearCarrito($carrito);
                }
                //dd($orden->products);
            }
        }
        return "OK";
    }

    /*La funcioon verifica si existe un carrito abandonado con el # de id que llega y devuelve si se puede crear o un nuevo pedido.
    Si devuelve true, se puede crear un carrito nuevo porque no existe ninguno con ese # de carrito
    Si devuelve false, no se puede crear ya que hay un carrito abandonado con ese # de carrito y en ese local*/
    private function verificarCarrito($idCarrito)
    {
        $carrito = Carrito_abandonado::where('id_tienda_nube',$idCarrito)->get();
        if ($carrito->isEmpty()) {
            return true;
        }else {
            return false;
        }
    }

    /*Debido a que la API de tienda nube, no puede enviar mas de 200 productos por pagina, lo que hace esta funcion
    es tomar la cantidad de productos que hay en tienda nube y lo divide por la cantidad de productos por pagina. Con
    Esta informacion la urilizo en el FOR para solicitar todas las paginas que tienen los articulos*/
    public function obtengoCantConsultas($api,$cantidadPorPaginas)
    {
        try {
            $query = $api->get("checkouts?page=1&per_page=1");
            // dd(ceil($query->headers['x-total-count']));
            $cantidadConsultas = (ceil(($query->headers['x-total-count'] / $cantidadPorPaginas)));
        }catch (API\Exception $e){
            //Si no hay resultado, para que no de error la consulta se pasa $cantidadConsultas = 0
            $cantidadConsultas = 0;
        }
        return $cantidadConsultas;
    }

    private function crearCarrito($carrito)
    {
        $fecha = date('Y-m-d h:m:s',strtotime($carrito->created_at));
        Carrito_abandonado::create([
            "id_tienda_nube" => $carrito->id,
            "nombre_contacto" => $carrito->contact_name,
            "cel_contacto" => $carrito->contact_phone,
            "email_contacto" => $carrito->contact_email,
            "total" => $carrito->total,
            "fecha" => $fecha
        ]);
    }
}
