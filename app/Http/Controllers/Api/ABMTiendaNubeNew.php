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


class ABMTiendaNubeNew extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function getProductos()
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

        for ($i = 1; $i <= $cantidadConsultas; $i++){
            $articulosTiendaNube = $api->get("products?page=$i&per_page=$cantidadPorPaginas");
            foreach ($articulosTiendaNube->body as $articulo){
                $image = 0;
                if (!empty($articulo->images)){
                    $image = 1;
                }
                foreach ($articulo->variants as $variant){
                    //dd($variant);
                    //Verifico que no sea null la cantidad
                    if (!empty($articulo->images[0]->src)){
                        $imagesSrc = $articulo->images[0]->src;
                    }else $imagesSrc = "";
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
        }
        return Response::json("ok");
    }

    public function sincroArticulos(){
        $id_corrida = Input::get('id_corrida');
        $store_id = Input::get('store_id');
        $conOrden = Input::get('conOrden');
        $ordenCant = Input::get('ordenCant');
        $artiCant = Input::get('artiCant');
        $status = "OK";
        $countOk = 0;
        $countError = 0;
        $countCheck = 0;
        $respuesta=[];
        if ($conOrden == 1){
            $statusEcomerce = DB::select('SELECT OrdenCompras.OrdenCompra,StatusEComerce.id as e_id, StatusEComerce.id_provecomerce, OrdenCompras.articulo, StatusEComerce.product_id, StatusEComerce.articulo_id,
                                          visible, images FROM samira.compras as OrdenCompras
                                          inner join samira.statusecomercesincro as StatusEComerce ON ordencompras.Articulo = StatusEComerce.Articulo
                                            where OrdenCompra IN
                                            (
                                                select * from (
                                                                select OrdenCompra from samira.compras group by OrdenCompra DESC LIMIT ' . $ordenCant . '
                                                               ) as subquery
                                            )
                                            and Cantidad <> 0
                                            and id_provecomerce = "' . $id_corrida . '" and statusecomerce.status <> "' . $status . '"');
        } else {
            $statusEcomerce = DB::select('SELECT statusecomerce.id as e_id, provecomerce.proveedor, usuario.name as nombre, statusecomerce.articulo,
                                     statusecomerce.status,
                                     statusecomerce.fecha, product_id, articulo_id, images
                                     from samira.statusecomercesincro as statusecomerce
                                     inner join samira.provecomerce as provecomerce ON provecomerce.id = statusecomerce.id_provecomerce
                                     inner join samira.users as usuario ON usuario.id = provecomerce.id_users
                                     where id_provecomerce = "' . $id_corrida . '" and statusecomerce.status <> "' . $status . '"');
        }
        // dd($statusEcomerce);
        foreach ($statusEcomerce as $articulo){
            $resultado = $this->abmProductos($articulo->articulo,$articulo->product_id,$articulo->articulo_id,$articulo->e_id,$store_id,$conOrden, $articulo->images,$artiCant);
            if ($resultado == "ok"){
                $countOk++;
            }elseif ($resultado == "ErrorAPI"){
                $countError++;
            }elseif ($resultado == 'No Requiere'){
                $countCheck++;
            }
        }
        $respuesta[0] = ['OK' => $countOk,'Error' => $countError, 'No Requiere' => $countCheck];
        //$respuesta = (json_encode($respuesta));
        //dd("OK: " , $countOk , " Error: " , $countError , " No Requiere " , $countCheck);
        return Response::json($respuesta);
    }
    public function abmProductos($sku,$product_id_TN,$articulo_id_TN,$ecommerce_id,$store_id,$conOrden, $images, $artiCant){
        //$store_id = 0;
        /*Verifica con que tienda tiene que sincronizar:
        Demo Nacha = 972788
        Samira SRL = 938857
        Donatella = 963000
        Viamore = 1043936
        */
        $tnConnect = new TnubeConnect();
        $connect = $tnConnect->getConnectionTN($store_id);

        /*   $sku = Input::get('sku');
           $product_id_TN = Input::get('product_id_tn');
           $articulo_id_TN = Input::get('articulo_id_tn');
           $ecommerce_id = Input::get('ecommerce_id');
       */

        $statusEcommerceSinc = StatusEcomerceSinc::where('id',$ecommerce_id);
        if ($statusEcommerceSinc->get()[0]->status <> 'OK'){
            $api = new API($store_id, $connect[0]['access_token'], $connect[0]['appsName']);
            $articuloLocal = Articulos::where('Articulo',$sku)->get();
            $precioAydua = new Precio();
            if (!$articuloLocal->isEmpty()){
                $articuloEnPedidos = DB::select('select pedtemp.Articulo as Articulo, sum(pedtemp.cantidad) as Cantidad
                                 from samira.pedidotemp as pedtemp
                                 INNER JOIN samira.controlpedidos as control ON pedtemp.NroPedido = control.nropedido
                                 where articulo = "'.$sku.'" and control.estado = 1');
                //Verifico que no sea null la cantidad
                if ($articuloEnPedidos[0]->Cantidad){
                    $cantidad = ($articuloLocal[0]->Cantidad - $articuloEnPedidos[0]->Cantidad);
                }else $cantidad = $articuloLocal[0]->Cantidad;
                try {
                    //Verifica si la replica corresponde a articulos que esten en una orden de compras, si tiene imagen
                    if ($conOrden == 1 and $images == 1 and $cantidad >= $artiCant) {
                        // dd($articulo_id_TN);
                        $response = $api->put("products/$product_id_TN", [
                            'published' => true
                        ]);
                    }

                    //Si el Stock es menor a la cantidad especificada en la corria, oculta el articulo para que no salga con Sin-Stock
                   /* if ($cantidad < $artiCant) {
                        // dd($articulo_id_TN);
                        $response = $api->put("products/$product_id_TN", [
                            'published' => false
                        ]);
                    } */
                    $response = $api->put("products/$product_id_TN/variants/$articulo_id_TN", [
                        'price' => $precioAydua->query($articuloLocal[0])[0]['PrecioVenta'],
                        'stock' => $this->verificoStock($cantidad,$artiCant)
                    ]);
                    /*
                    $response = $api->put("products/$product_id_TN/variants/$articulo_id_TN", [
                        'price' => "",
                        'stock' => $this->verificoStock($cantidad)
                    ]);*/

                    $statusEcommerceSinc->update([
                        'status' => 'OK',
                        'fecha' => Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString(),
                    ]);
                }catch (Exception $e){
                    $statusEcommerceSinc->update([
                        'status' => "ErrorAPI", //$e->response->body->code,
                        'fecha' => Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString(),
                    ]);
                    return "ErrorAPI";
                }
                catch (FatalErrorException $e) {
                    return "Fatal Error";
                }
                catch (\Exception $e) {
                    $statusEcommerceSinc->update([
                        'status' => "ErrorException", //$e->response->body->code,
                        'fecha' => Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString(),
                    ]);
                    return "ErrorException";
                }
            }
            if ($statusEcommerceSinc->get()[0]->status == 'Pending'){
                return "No Requiere";
            }
            return "ok";
        }
        return "No Requiere";
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
    public function verificoStock($cantidad,$artiCant)
    {
        if ($cantidad >= $artiCant) {
            return "";
        }
        return 0;
    }
}
