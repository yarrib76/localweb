<?php

namespace Donatella\Http\Controllers\Api\Automation;

use Carbon\Carbon;
use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\StatusEcomerceSinc;
use Donatella\Models\StatusEcommerceaAtoSinc;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Psy\Exception\FatalErrorException;
use TiendaNube\API;
use TiendaNube\API\Exception;

class ReplicaTN extends Controller
{
    public function view()
    {
        $statusEcommerceAutoSincs = DB::select('select articulos.Articulo, Detalle, Fecha, Stock from statusecommerceautosinc
                                                INNER JOIN articulos ON articulos.articulo = statusecommerceautosinc.articulo');
        return view('tiendanube.autosinc.reporte' ,compact('statusEcommerceAutoSincs')) ;
    }
    public function sincroArticulos($store_id,$artiCant){
        //$store_id = Input::get('store_id');
        //$artiCant = Input::get('artiCant');
        $countOk = 0;
        $countError = 0;
        $countCheck = 0;
        $respuesta=[];
        $connection = $this->getConnectionTN($store_id);
        $fecha = date("Y-m-d");
        $statusEcomerce = DB::select('SELECT id_provecomerce, statusecomerce.articulo,
                                        statusecomerce.status,
                                        statusecomerce.fecha, product_id, articulo_id, images
                                        from samira.statusecomercesincro as statusecomerce
                                        inner join samira.provecomerce as provecomerce ON provecomerce.id = statusecomerce.id_provecomerce
                                        inner join samira.pedidotemp  as pedtemp ON pedtemp.Articulo = statusecomerce.articulo
                                        where id_provecomerce = (SELECT id FROM samira.provecomerce
                                        where id_cliente = "'. $store_id .'"
                                        order by id desc limit 1)
                                        and pedtemp.Fecha = "'.$fecha.'"');
        foreach ($statusEcomerce as $articulo){
            $resultado = $this->abmProductos($articulo->articulo,$articulo->product_id,$articulo->articulo_id,$store_id, $connection,$artiCant);
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
    public function abmProductos($sku,$product_id_TN,$articulo_id_TN,$store_id,$connection, $artiCant){

        /*   $sku = Input::get('sku');
           $product_id_TN = Input::get('product_id_tn');
           $articulo_id_TN = Input::get('articulo_id_tn');
           $ecommerce_id = Input::get('ecommerce_id');
       */

        $api = new API($store_id, $connection[0]['access_token'], $connection[0]['appsName']);
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
                $response = $api->put("products/$product_id_TN/variants/$articulo_id_TN", [
                    'price' => $precioAydua->query($articuloLocal[0])[0]['PrecioVenta'],
                    'stock' => $this->verificoStock($cantidad,$artiCant)
                ]);
                /*
                $response = $api->put("products/$product_id_TN/variants/$articulo_id_TN", [
                    'price' => "",
                    'stock' => $this->verificoStock($cantidad)
                ]);*/
                $statusEcommerceAutoSinc = StatusEcommerceaAtoSinc::where('articulo',$articuloLocal[0]->Articulo)->get();
                if ($cantidad < $artiCant){
                    $cant = 0;
                }else $cant = 1;
                if (!$statusEcommerceAutoSinc->isEmpty())
                {
                    DB::select('UPDATE samira.statusecommerceautosinc
                                SET
                                  fecha = "'. Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString().'",
                                  stock = "'.$cant.'"
                                  WHERE articulo = "'.$articuloLocal[0]->Articulo.'";');
                }else {
                    DB::select('INSERT INTO `samira`.`statusecommerceautosinc`
                                (articulo,
                                 fecha,
                                 stock)
                                VALUES
                                ("'.$articuloLocal[0]->Articulo.'",
                                 "'. Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString().'",
                                 "'.$cant.'");');
                }
            }catch (Exception $e){
                dd('1');
                return "ErrorAPI";
            }
            catch (FatalErrorException $e) {
                dd('2');
                return "Fatal Error";
            }
            catch (\Exception $e) {
                dd($e);
                return "ErrorException";
            }
        }

        return "ok";
    }

    public function verificoStock($cantidad,$artiCant)
    {
        if ($cantidad >= $artiCant) {
            return "";
        }
        return 0;
    }

    public function getConnectionTN($store_id)
    {
        //$store_id = 0;
        /*Verifica con que tienda tiene que sincronizar:
        Demo Nacha = 972788
        Samira SRL = 938857
        Donatella = 963000
        Viamore = 1043936
        */
        $connetion=[];
        if ($store_id == '972788'){
            $access_token = '3f2d77c28ce3bfc9df48fc7f34e43549220d7379';
            //   $store_id = '972788';
            $appsName = 'SincroDemo (yarrib76@gmail.com)';
        }
        if ($store_id == '938857'){
            $access_token = '101d4ea2e9fe7648ad05112274a5922acf115d37';
            //    $store_id = '938857';
            $appsName = 'SincroApps (yarrib76@gmail.com)';
        }
        if ($store_id == '963000'){
            $access_token = '00b27bb0c34a6cab2c1d4edc0792051b50b91f9e';
            //    $store_id = '963000';
            $appsName = 'SincoAppsDonatella (yarrib76@gmail.com)';
        }
        if ($store_id == '1043936'){
            $access_token = '483b0e8c4eb5d65211002a5d1770281b7ea5e437';
            //    $store_id = '1043936';
            $appsName = 'SincoAppsViamore (yarrib76@gmail.com)';
        }
        $connetion[0]= ['access_token' => $access_token,'appsName' => $appsName];
        return $connetion;
    }

    public function delete()
    {
        DB::select('TRUNCATE TABLE statusecommerceautosinc');
        return Response::json("OK");
    }
}
