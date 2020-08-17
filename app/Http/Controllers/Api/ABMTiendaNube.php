<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\ProvEcomerce;
use Donatella\Models\StatusEcomerceSinc;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Debug\Exception\FatalErrorException;
use TiendaNube\API;
use TiendaNube\API\Exception;
use TiendaNube\Auth;

class ABMTiendaNube extends Controller
{
    public function abmProductos()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        //Para instalar la aplicación en mi tienda, ingresar a la parte administrador de la tienda,
        // 1. Abrir una nueva pestania y poner le url "https://www.tiendanube.com/apps/(app_id)/authorize",
        //2. Reemplazar (app_id) por el id de la aplicacion que se quiere instalar.
        //3. Luego tomar el Code y pegarlo en el codigo de abajo:
        //4. En la creación del objeto ingresar el id de la aplicación y el Clien Secret (esta en https://partners.tiendanube.com/apps/?ref=menu)


        /*
        $code = 'c20dd9b6d9a87d1ec7dca0e5e3278625e4abfd9b';
        // En Auth(Cliente_id,Client Secret)
        $auth = new Auth(1358, 'WcuW5hyGiiPPqpnC5OEVOmg0r7oDjUcvlIXLEphoAanRFVd5');
        $store_info = $auth->request_access_token($code);
        dd($store_info);
        */

        /*
         * Datos de Acceso para Viamore
            "store_id" => 1043936
            "access_token" => "483b0e8c4eb5d65211002a5d1770281b7ea5e437"
            "scope" => "write_products"
         */

        /* Datos de Acceso para Demo Nacha
            "store_id" => 972788
            "access_token" => "a37bd246745b939c29e3fdd11b18cd356d1b87c4"
            "scope" => "write_products"
         */
        //La cantidad de produtos por página
        $cantidadPorPaginas = 200;

        $store_id = 0;
        /*Verifica con que tienda tiene que sincronizar:
        Demo Nacha = 972788
        Samira SRL = 938857
        Donatella = 963000
        Viamore = 1043936
        */
        if (Input::get('store_id') == '972788'){
            $access_token = 'a37bd246745b939c29e3fdd11b18cd356d1b87c4';
            $store_id = '972788';
            $appsName = 'SincroDemo (yarrib76@gmail.com)';
        }
        if (Input::get('store_id') == '938857'){
            $access_token = '101d4ea2e9fe7648ad05112274a5922acf115d37';
            $store_id = '938857';
            $appsName = 'SincroApps (yarrib76@gmail.com)';
        }
        if (Input::get('store_id') == '963000'){
            $access_token = '00b27bb0c34a6cab2c1d4edc0792051b50b91f9e';
            $store_id = '963000';
            $appsName = 'SincoAppsDonatella (yarrib76@gmail.com)';
        }
        if (Input::get('store_id') == '1043936'){
            $access_token = '483b0e8c4eb5d65211002a5d1770281b7ea5e437';
            $store_id = '1043936';
            $appsName = 'SincoAppsViamore (yarrib76@gmail.com)';
        }

        /*
        //Datos para la conexión Samira SRL
        $access_token = '101d4ea2e9fe7648ad05112274a5922acf115d37';
        $store_id = '938857'; */

        $api = new API($store_id, $access_token, $appsName);
        $cantidadConsultas = $this->obtengoCantConsultas($api,$cantidadPorPaginas);
        $id_provEcomerce = ProvEcomerce::Create([
            'proveedor' => 'TiendaNube',
            'id_users' => auth()->user()->id,
            'fecha' => $fecha
        ]);
        for ($i = 1; $i <= $cantidadConsultas; $i++){
            $articulosTiendaNube = $api->get("products?page=$i&per_page=$cantidadPorPaginas");
            $precioAydua = new Precio();
            foreach ($articulosTiendaNube->body as $articulo){
                foreach ($articulo->variants as $variant){
                    $articuloLocal = Articulos::where('Articulo',$variant->sku)->get();
                    if (!$articuloLocal->isEmpty()){
                        $articuloEnPedidos = DB::select('select pedtemp.Articulo as Articulo, sum(pedtemp.cantidad) as Cantidad
                                 from samira.pedidotemp as pedtemp
                                 INNER JOIN samira.controlpedidos as control ON pedtemp.NroPedido = control.nropedido
                                 where articulo = "'.$variant->sku.'" and control.estado = 1');
                        //Verifico que no sea null la cantidad
                        if ($articuloEnPedidos[0]->Cantidad){
                            $cantidad = ($articuloLocal[0]->Cantidad - $articuloEnPedidos[0]->Cantidad);
                        }else $cantidad = $articuloLocal[0]->Cantidad;
                        try {
                            $response = $api->put("products/$variant->product_id/variants/$variant->id", [
                                'price' => $precioAydua->query($articuloLocal[0])[0]['PrecioVenta'],
                                'stock' => $this->verificoStock($cantidad)
                            ]);
                            StatusEcomerceSinc::Create([
                                'id_provecomerce' => $id_provEcomerce->id,
                                'status' => 'OK',
                                'fecha' => Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString(),
                                'articulo' => $variant->sku
                            ]);
                        }catch (Exception $e){
                            StatusEcomerceSinc::Create([
                                'id_provecomerce' => $id_provEcomerce->id,
                                'status' => "ErrorAPI", //$e->response->body->code,
                                'fecha' => Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString(),
                                'articulo' => $variant->sku
                            ]);
                        }
                        catch (FatalErrorException $e) {
                            return Response::json("Fatal Error");
                        }
                        catch (\Exception $e) {
                            StatusEcomerceSinc::Create([
                                'id_provecomerce' => $id_provEcomerce->id,
                                'status' => "ErrorException", //$e->response->body->code,
                                'fecha' => Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString(),
                                'articulo' => $variant->sku
                            ]);
                        }
                    }
                }
            }
        }
        return Response::json("ok");
    }
    public function verificoStock($cantidad)
    {
        if ($cantidad >= 4) {
            return "";
        }
        return 0;
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
