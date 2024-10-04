<?php

namespace Donatella\Http\Controllers\Test;

use Carbon\Carbon;
use DateTime;
use Donatella\Ayuda\GetPuntos;
use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\Dolar;
use Donatella\Models\ProvEcomerce;
use Donatella\Models\Proveedores;
use Donatella\Models\ReporteArtiulos;
use Donatella\Models\StatusEcomerceSinc;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use mysqli;
//
class TestTiendaNube extends Controller
{
    public function Test()
    {

        /*
        $product_id = DB::select('select product_id from samira.statusecomercesincro
                                  where id_provecomerce = 2486
                                  ');
        $this->delCurl($product_id);
        dd('listo');
        */

        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        $cantidadConsultas =  $this->obtengoPaginas();
        //$cantidadConsultas = 10;
        $store_id = 963000;
        $cantidadPorPaginas = 200;

        $id_provEcomerce = ProvEcomerce::Create([
                'proveedor' => 'TiendaNube',
                'id_users' => 1,
                'fecha' => $fecha,
                'id_cliente' => $store_id,
                'tienda' => 'Donatella'
            ]);
            for ($i = 1; $i <= $cantidadConsultas; $i++){
                try {
                    $data = $this->consultaCurl($i,$cantidadPorPaginas);
                    foreach ($data as $articulo){
                        // dump($i, $articulo);
                        $image = 0;
                        if (!empty($articulo['images'])){
                            $image = 1;
                        }
                        foreach ($articulo['variants'] as $variant){
                            //Verifico que no sea null la cantidad
                            if (!empty($articulo['images'][0]['src'])){
                                $imagesSrc = $articulo['images'][0]['src'];
                            }else $imagesSrc = "";
                            StatusEcomerceSinc::Create([
                                'id_provecomerce' => $id_provEcomerce->id,
                                'status' => 'Pending',
                                'fecha' => Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString(),
                                'articulo' => $variant['sku'],
                                'product_id' => $variant['product_id'],
                                'articulo_id' => $variant['id'],
                                'visible' => $articulo['published'],
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
                // $this->logFile($i);
            }
            // Mostrar el array para visualizar la respuesta
        return('Listo');
    }

    function obtengoPaginas(){
        // Inicializar cURL
        $curl = curl_init();

        // Definir los encabezados
        $headers = [
            'Authentication: bearer 9d4e7d6c96a5256904d289d6425b969c043bd1cf',
            'User-Agent: SincroApps (yarrib76@gmail.com)'
        ];

        // URL con paginación
        $url = 'https://api.tiendanube.com/v1/938857/products?page=2&per_page=100';

        // Configurar cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,  // Incluir los encabezados en la salida
            CURLOPT_HTTPHEADER => $headers,
        ]);

// Ejecutar la solicitud
        $response = curl_exec($curl);

// Verificar si hubo errores
        if (curl_errno($curl)) {
            echo 'Error en la solicitud cURL: ' . curl_error($curl);
        } else {
            // Separar los encabezados del cuerpo de la respuesta
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $headers = substr($response, 0, $header_size);

            // Buscar el encabezado X-Total-Count
            $x_total_count = null;
            foreach (explode("\r\n", $headers) as $header) {
                if (stripos($header, 'X-Total-Count:') !== false) {
                    $x_total_count = trim(explode(':', $header)[1]);
                    break;
                }
            }

            if ($x_total_count !== null) {
                return (ceil($x_total_count/200));
            } else {
                echo "Encabezado X-Total-Count no encontrado.\n";
            }
        }
        // Cerrar cURL
        curl_close($curl);
    }

    public function consultaCurl($pagina, $cantidadPorPaginas)
    {
        // Inicializar cURL
        $curl = curl_init();

        // Definir los encabezados
        $headers = [
            'Authentication: bearer 9d4e7d6c96a5256904d289d6425b969c043bd1cf',
            'User-Agent: SincroApps (yarrib76@gmail.com)',
            'Content-Type: application/json'
        ];


        // URL para obtener los productos (con paginación si es necesario)
        $url = "https://api.tiendanube.com/v1/938857/products?page=$pagina&per_page=$cantidadPorPaginas";
        //$url = "https://api.tiendanube.com/v1/938857/products?page=1&per_page=1";

        // Configurar cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        // Ejecutar la solicitud y obtener la respuesta
        $response = curl_exec($curl);

        // Verificar si hubo errores
        if (curl_errno($curl)) {
            echo 'Error en la solicitud cURL: ' . curl_error($curl);
        } else {
            // Decodificar el cuerpo de la respuesta JSON a un array asociativo
            $data = json_decode($response, true);
            return $data;
        }
        // Cerrar cURL
        curl_close($curl);
    }

    public function delCurl($products_id)
    {
        // Inicializar cURL
        $curl = curl_init();

        // Definir los encabezados
        $headers = [
            'Authentication: bearer fa4dae089b6475c03622ecff3fc4680d1c3d5c97',
            'User-Agent: SincroApps (yarrib76@gmail.com)',
            'Content-Type: application/json'
        ];


        // URL para obtener los productos (con paginación si es necesario)
        // $url = "https://api.tiendanube.com/v1/5209165/products?page=$pagina&per_page=$cantidadPorPaginas";
        //$url = "https://api.tiendanube.com/v1/938857/products?page=1&per_page=1";

        foreach ($products_id as $product_id) {
            $url = "https://api.tiendanube.com/v1/5209165/products/$product_id->product_id";
            // Para DELETE
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_CUSTOMREQUEST => "DELETE", // Establecer método DELETE
            ]);

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($curl);
            // Verificar si hubo errores
            if (curl_errno($curl)) {
                echo 'Error en la solicitud cURL: ' . curl_error($curl);
            } else {
                // Decodificar el cuerpo de la respuesta JSON a un array asociativo
                $data = json_decode($response, true);
                print_r($data);
            }
        }
        // Cerrar cURL
        curl_close($curl);
    }
}
