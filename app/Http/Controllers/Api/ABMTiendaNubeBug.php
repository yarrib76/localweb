<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Donatella\Ayuda\Precio;
use Donatella\Ayuda\TnubeConnect;
use Donatella\Models\Articulos;
use Donatella\Models\ProvEcomerce;
use Donatella\Models\StatusEcomerceSinc;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use TiendaNube\API;
use TiendaNube\API\Exception;
use TiendaNube\Auth;
use Symfony\Component\Debug\Exception\FatalErrorException;


class ABMTiendaNubeBug extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function getProductos()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();

        //La cantidad de produtos por página
        $cantidadPorPaginas = 200;

        $store_id = 0;
        /*Verifica con que tienda tiene que sincronizar:
        Demo Nacha = 972788
        Samira SRL = 938857
        Donatella = 963000
        Viamore = 1043936
        */
        $store_id = Input::get('store_id');
        $tnConnect = new TnubeConnect();
        $connect = $tnConnect->getConnectionTN($store_id);

        $api = new API($store_id, $connect[0]['access_token'], $connect[0]['appsName']);
        $cantidadConsultas = $this->obtengoCantConsultas($api,$cantidadPorPaginas);
        $id_provEcomerce = ProvEcomerce::Create([
            'proveedor' => 'TiendaNube',
            'id_users' => auth()->user()->id,
            'fecha' => $fecha,
            'id_cliente' => $store_id,
            'tienda' => $connect[0]['tienda']
        ]);

        for ($i = 1; $i <= $cantidadConsultas; $i++) {
            try {
                $articulosTiendaNube = $api->get("products?page=$i&per_page=$cantidadPorPaginas");
                foreach ($articulosTiendaNube->body as $articulo) {
                    $image = 0;
                    if (!empty($articulo->images)) {
                        $image = 1;
                    }
                    foreach ($articulo->variants as $variant) {
                        //dd($variant);
                        //Verifico que no sea null la cantidad
                        if (!empty($articulo->images[0]->src)) {
                            $imagesSrc = $articulo->images[0]->src;
                        } else $imagesSrc = "";
                        StatusEcomerceSinc::Create([
                            'id_provecomerce' => $id_provEcomerce->id,
                            'status' => 'Pending',
                            'fecha' => Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString(),
                            'articulo' => $variant->sku,
                            'product_id' => $variant->product_id,
                            'articulo_id' => $variant->id,
                            'visible' => $articulo->published,
                            'images' => $image,
                            'imagessrc' => $imagesSrc
                        ]);
                    }
                }
            }catch (Exception $e){
                echo " error en " . $i;
                $i = $i-1;
            };
        }
        return Response::json("ok");
    }

    /*Debido a que la API de tienda nube, no puede enviar mas de 200 productos por pagina, lo que hace esta funcion
    es tomar la cantidad de productos que hay en tienda nube y lo divide por la cantidad de productos por pagina. Con
    Esta información la urilizo en el FOR para solicitar todas las pagínas que tienen los artículos*/
    private function obtengoCantConsultas($api,$cantidadPorPaginas)
    {
        $query = $api->get("products?page=1&per_page=1");
        $cantidadConsultas = (ceil(($query->headers['x-total-count'] / $cantidadPorPaginas)));
        return $cantidadConsultas;
    }

}
