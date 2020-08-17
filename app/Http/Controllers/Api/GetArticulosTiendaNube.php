<?php

namespace Donatella\Http\Controllers\Api;

use Carbon\Carbon;
use Donatella\Models\NewArtiTN;
use Donatella\Models\ProvEcomerce;
use Donatella\Models\StatusEcomerceSinc;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use TiendaNube\API;
use TiendaNube\API\Exception;
use TiendaNube\Auth;

class GetArticulosTiendaNube extends Controller
{
    public function inbox (){
        return view('tiendanube.bajadatransformarticulos');
    }
    public function vistaExportaExcel (){
         return view('tiendanube.exportexcel');
    }
    public function getArticulos()
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



        //Trunco la tabla
        DB::select('truncate table samira.newartitn');
        $api = new API($store_id, $access_token, $appsName);
        // $query = $api->get("products/39750826");
        // dd($query);
        $cantidadConsultas = $this->obtengoCantConsultas($api,$cantidadPorPaginas);

        for ($i = 1; $i <= $cantidadConsultas; $i++){
            $articulosTiendaNube = $api->get("products?page=$i&per_page=$cantidadPorPaginas");
            foreach ($articulosTiendaNube->body as $articulo){

                $categorias = $this->obtengoCategorias($articulo->categories);

                $newDescriptions = "";
                if (Input::get('local') == 'Viamore'){
                    $newTituloSeo = substr($articulo->name->es, 0, strrpos($articulo->name->es, ' ') + 1) . " " . "POR MAYOR EN FLORES";
                    $newMarca = "VIAMORE";
                    //Cambio la palabra SAMIRA Bijou en sus diferentes escrituras por Viamore en la descripciòn
                    if(strpos($articulo->description->es,'SAMIRA Bijou') == true){
                        $newDescriptions = (str_replace("SAMIRA Bijou"," Viamore",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,'SAMIRA BIJOU') == true){
                        $newDescriptions = (str_replace("SAMIRA BIJOU"," Viamore",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,'Samira Bijou') == true){
                        $newDescriptions = (str_replace("Samira Bijou"," Viamore",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,' Samira&nbsp;Bijou') == true){
                        $newDescriptions = (str_replace(" Samira&nbsp;Bijou"," Viamore",$articulo->description->es));
                    }
                }

                if (Input::get('local') == 'Donatella'){
                    $newTituloSeo = substr($articulo->name->es, 0, strrpos($articulo->name->es, ' ') + 1) . " " . "POR MAYOR EN ONCE";
                    $newMarca = "DONATELLA";
                    //Cambio la palabra SAMIRA Bijou en sus diferentes escrituras por Viamore en la descripciòn
                    if(strpos($articulo->description->es,'SAMIRA Bijou') == true){
                        $newDescriptions = (str_replace("SAMIRA Bijou"," Donatella Bijou",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,'SAMIRA BIJOU') == true){
                        $newDescriptions = (str_replace("SAMIRA BIJOU"," Donatella Bijou",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,'Samira Bijou') == true){
                        $newDescriptions = (str_replace("Samira Bijou"," Donatella Bijou",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,' Samira&nbsp;Bijou') == true){
                        $newDescriptions = (str_replace(" Samira&nbsp;Bijou"," Donatella Bijou",$articulo->description->es));
                    }
                }

 /*
                if (Input::get('local') == 'Samira'){
                    $newTituloSeo = $articulo->name->es . " " . "POR MAYOR EN ONCE";
                    $newMarca = "SAMIRA";
                    //Cambio la palabra SAMIRA Bijou en sus diferentes escrituras por Viamore en la descripciòn
                    if(strpos($articulo->description->es,'SAMIRA Bijou') == true){
                        $newDescriptions = (str_replace("SAMIRA Bijou"," SAMIRA Bijou",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,'SAMIRA BIJOU') == true){
                        $newDescriptions = (str_replace("SAMIRA BIJOU"," SAMIRA Bijou",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,'Samira Bijou') == true){
                        $newDescriptions = (str_replace("Samira Bijou"," SAMIRA Bijou",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,' Samira&nbsp;Bijou') == true){
                        $newDescriptions = (str_replace(" Samira&nbsp;Bijou"," SAMIRA Bijou",$articulo->description->es));
                    }
                }
/*
/*
                if (Input::get('local') == 'Viamore'){
                    $newTituloSeo = $articulo->name->es . " " . "POR MAYOR EN FLORES";
                    $newMarca = "VIAMORE";
                    //Cambio la palabra SAMIRA Bijou en sus diferentes escrituras por Viamore en la descripciòn
                    if(strpos($articulo->description->es,'Viamore') == true){
                        $newDescriptions = (str_replace("Viamore","Viamore",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,'VIAMORE') == true){
                        $newDescriptions = (str_replace("VIAMORE","Viamore",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,'Samira Bijou') == true){
                        $newDescriptions = (str_replace("Samira Bijou","Viamore",$articulo->description->es));
                    }
                    if(strpos($articulo->description->es,' Samira&nbsp;Bijou') == true){
                        $newDescriptions = (str_replace(" Samira&nbsp;Bijou","Viamore",$articulo->description->es));
                    }
                }
*/
                if (empty($articulo->attributes)){
                    NewArtiTN::Create([
                        'Identificador de URL' => $articulo->handle->es,
                        'Nombre' => $articulo->name->es,
                        'Categorías' => $categorias,
                        'SKU' => $articulo->variants[0]->sku,
                        'Mostrar en tienda' =>"NO",
                        'Precio' => "0",
                        'Precio Promocional' => "0",
                        'Descripción' => $newDescriptions,
                        'Título para SEO' => $newTituloSeo,
                        'Marca' => $newMarca
                    ]);
                } elseif (!empty($articulo->variants[0]->values[0]->es)) {
                    NewArtiTN::Create([
                        'Identificador de URL' => $articulo->handle->es,
                        'Nombre' => $articulo->name->es,
                        'Categorías' => $categorias,
                        'Nombre de propiedad 1' => $articulo->attributes[0]->es,
                        'Valor de propiedad 1' => $articulo->variants[0]->values[0]->es,
                        'SKU' => $articulo->variants[0]->sku,
                        'Precio' => "0",
                        'Precio Promocional' => "0",
                        'Mostrar en tienda' =>"NO",
                        'Descripción' => $newDescriptions,
                        'Título para SEO' => $newTituloSeo,
                        'Marca' => $newMarca
                    ]);
                    $this->creoVariantes($articulo);
                }
            }
        }
            return Response::json("ok");
        // return view('tiendanube.exportexcel');
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

    private function obtengoCategorias($catogories)
    {
        $categorias = "";
        foreach ($catogories as $categoria){
            $categorias .=  $categoria->name->es . ",";
    }
        return (substr($categorias, 0, -1));
    }

    private function creoVariantes($articulo)
    {
        $valueAnterior = $articulo->variants[0]->values[0]->es;
        foreach ($articulo->variants as $variante){
            if ($variante->values[0]->es != $valueAnterior)
            {
                NewArtiTN::Create([
                    'Identificador de URL' => $articulo->handle->es,
                    'Nombre de propiedad 1' => $articulo->attributes[0]->es,
                    'Valor de propiedad 1' => $variante->values[0]->es,
                    'SKU' => $variante->sku,
                    'Precio' => 0,
                    'Precio Promocional' => 0,
                    'Peso' => 0,
                    'Mostrar en tienda' =>"NO",
                    'Stock' => 0
                ]);
            }
        }
        return;
    }
    public function downloadExcel($type)
    {
        $data = NewArtiTN::get()->toArray();
        return Excel::create('ExportCSV', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
}
