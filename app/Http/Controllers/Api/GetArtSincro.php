<?php

namespace Donatella\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class GetArtSincro extends Controller
{
    public function listaArticulosRemotos()
    {
        $local = Input::get('Local');
        $url ="";
        switch ($local){
            case 'Viamore':
                $url = ("http://viamore.dyndns.org:8081/api/artisinc?Codigo=3869");
                break;
            case 'Samira':
                $url = ("http://samirasrl.dyndns.org:8081/api/artisinc?Codigo=3869");
                break;
            case 'Donatella':
                $url = ("http://donatella.dyndns.org:8081/api/artisinc?Codigo=3869");
                break;
        }
        ini_set('default_socket_timeout', 900);
        try {
            $articulos = json_decode(file_get_contents(($url), true),true);
            $articulosNuesvos =  $this->verificaNewArticulos($articulos);
            return $articulosNuesvos;
        }catch (Exception $e) {
            echo $e;
            echo "error";
        }

    }
    public function verificaNewArticulos($articulos)
    {
        $countNohay = 0;
        $count = 0;
        $countTodo = 0;
        $esIgual = null;
        $artiuculosNuevos = [];
        $localArticulos = DB::select('select Articulo,Detalle,Proveedor,PrecioOrigen,PrecioConvertido,Moneda,ProveedorSKU from samira.articulos');
        foreach ($localArticulos as $localArticulo){
            foreach ($articulos as $key=>$articulo){
                if ($articulo['Articulo'] == $localArticulo->Articulo) {
                    $count++;
                    //  printf($localArticulo->Articulo . "\n");
                    $esIgual = true;
                    unset ($articulos[$key]);
                    break;
                }else{
                    $esIgual = false;
                }
                $countTodo++;
            }
            if ($esIgual == false){
                $countNohay++;
                $artiuculosNuevos[] = ['Articulo' => $localArticulo->Articulo
                    ,'Detalle' => $localArticulo->Detalle
                    ,'ProveedorSKU' => $localArticulo->ProveedorSKU
                    ,'Proveedor' => $localArticulo->Proveedor
                    , 'PrecioOrigen' =>$localArticulo->PrecioOrigen
                    , 'PrecioConvertido' => $localArticulo->PrecioConvertido
                    , 'Moneda' =>$localArticulo->Moneda];
            }
        }
        return $artiuculosNuevos;
    }
}
