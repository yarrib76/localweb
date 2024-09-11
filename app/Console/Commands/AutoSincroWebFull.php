<?php

namespace Donatella\Console\Commands;

use Carbon\Carbon;
use Donatella\Ayuda\TnubeConnect;
use Donatella\Http\Controllers\Api\ABMTiendaNubeNew;
use Donatella\Models\ProvEcomerce;
use Donatella\Models\StatusEcomerceSinc;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Response;
use TiendaNube\API;
use TiendaNube\API\Exception;

class AutoSincroWebFull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sincroweb:tiendanube {options*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corre la bajada full de articulos de la tienda. Lo hago para que el útimo sea el completo y puedan ver
    las fotos de los artículos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $option = $this->argument('options');

        switch ($option[0]) {
            case 'Samira' : $store_id = 938857;
                break;
            case 'Donatella' : $store_id = 963000;
                break;
            case 'Viamore' : $store_id = 1043936;
                break;
            case 'Dona' : $store_id = 972788;
                break;
            case 'MegaNay' : $store_id = 4999055;
                break;
        }
        $tipo_bajada = 'todo';
        $this->getProductos($store_id,$tipo_bajada);
    }

    public function getProductos($store_id,$tipo_bajada)
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

        /*Verifica con que tienda tiene que sincronizar:
        Demo Nacha = 972788
        Samira SRL = 938857
        Donatella = 963000
        Viamore = 1043936
        */
        $tnConnect = new TnubeConnect();
        $connect = $tnConnect->getConnectionTN($store_id);

        $api = new API($store_id, $connect[0]['access_token'], $connect[0]['appsName']);
        $cantidadConsultas = $this->obtengoCantConsultas($api,$cantidadPorPaginas,$tipo_bajada);
        $id_provEcomerce = ProvEcomerce::Create([
            'proveedor' => 'TiendaNube',
            'id_users' => 1,
            'fecha' => $fecha,
            'id_cliente' => $store_id,
            'tienda' => $connect[0]['tienda']
        ]);

        for ($i = 1; $i <= $cantidadConsultas; $i++){
            try {
                if($tipo_bajada == 'todo'){
                    $articulosTiendaNube = $api->get("products?page=$i&per_page=$cantidadPorPaginas");
                }else if ($tipo_bajada == 'visible') {
                    $articulosTiendaNube = $api->get("products?page=$i&per_page=$cantidadPorPaginas&published=true");
                }
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
            }catch (Exception $e){
                // echo " error en " . $i;
                $i = $i-1;
                //Envio error
                $this->logFile($e);
            };
            $this->logFile($i);
        }
        $this->logFile("Finalizo correctamente");
        return Response::json("ok");
    }
    /*Debido a que la API de tienda nube, no puede enviar mas de 200 productos por pagina, lo que hace esta funcion
    es tomar la cantidad de productos que hay en tienda nube y lo divide por la cantidad de productos por pagina. Con
    Esta información la urilizo en el FOR para solicitar todas las pagínas que tienen los artículos*/
    private function obtengoCantConsultas($api,$cantidadPorPaginas,$tipo_bajada)
    {
        if ($tipo_bajada == 'todo'){
            $query = $api->get("products?page=1&per_page=1");
        }else $query = $api->get("products?page=1&per_page=1&published=true");
        $cantidadConsultas = (ceil(($query->headers['x-total-count'] / $cantidadPorPaginas)));
        return $cantidadConsultas;
    }

    function logFile($error){
        // Definir la ruta del archivo .log
        $logFile = storage_path('logs/mi_archivo.log');

        // Definir el mensaje a registrar
        $mensaje = "[" . date('Y-m-d H:i:s') . "]" . $error . "\n";
        // Guardar el mensaje en el archivo
        file_put_contents($logFile, $mensaje, FILE_APPEND);
        // FILE_APPEND asegura que el contenido se agregue al archivo, en lugar de sobrescribirlo.
    }
}
