<?php

namespace Donatella\Http\Controllers\Api;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class GetArtiMasVendidos extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia');
    }
    public function listaArticulos()
    {
        $local = Input::get('local');

        $anioDesde = Input::get('anioDesde');
        $anioHasta = Input::get('anioHasta');
        $proveedor = Input::get('proveedor');
        $esWeb = Input::get('esWeb');
        $url ="";
        switch ($local){
            case 'Samira':
                $url = ("http://samirasrl.dyndns.org:8081/api/reportesArticulos?anioDesde=" . $anioDesde .
                    "&anioHasta=" . $anioHasta . "&proveedor=" . $proveedor . "'&esWeb=" . $esWeb);
                return $this->viamore($url);
                break;
            case 'Donatella':
                $url = ("http://donatella.dyndns.org:8081/api/reportesArticulos?anioDesde=" . $anioDesde .
                    "&anioHasta=" . $anioHasta . "&proveedor=" . $proveedor. "'&esWeb=" . $esWeb);
                return $this->viamore($url);
                break;
            case 'Viamore':
                $url = ("http://viamore.dyndns.org:8081/api/reportesArticulos?anioDesde=" . $anioDesde .
                    "&anioHasta=" . $anioHasta . "&proveedor=" . $proveedor. "'&esWeb=" . $esWeb);
                return $this->viamore($url);
                break;
            case 'Local':
                $reporteArticulos = new ReporteArticulos();
                $articulos = $reporteArticulos->masVendidos();
                return $articulos;
                break;
        }

    }

    public function viamore($url)
    {
        $url = $this->removeSpace_url($url);
        ini_set('default_socket_timeout', 900);
        try {
            $articulos = json_decode(file_get_contents(($url), true),true);
            return Response::json($articulos);
        }catch (Exception $e) {
            echo $e;
            echo "error";
        }
    }

        function removeSpace_url($url){
            $urlSinSpacecio = str_replace(' ', '%20', $url);
            return $urlSinSpacecio;
        }
}
