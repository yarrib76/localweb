<?php

namespace Donatella\Http\Controllers\TiendaNube;

use Carbon\Carbon;
use Donatella\Ayuda\TnubeConnect;
use Donatella\Models\Carrito_abandonado;
use Donatella\Models\Notas_Carrito_abandonado;
use Donatella\Models\Vendedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use TiendaNube\API;

class CarritosAbandonados extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $carritosAbandonados = Carrito_abandonado::all();
        $user_id = Auth::user()->id;
        return view('tiendanube.carritosabandonados.reporte_v2', compact('user_id'));
        // return view('tiendanube.carritosabandonados.reporte', compact('carritosAbandonados','user_id'));
    }
    public function main($store_id)
    {
        $cantidadPorPaginas = 10;
        $tnConnect = new TnubeConnect();
        // $store_id = Input::get('store_id');
        // $store_id = 1043936;
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
            "fecha" => $fecha,
            "vendedora" => "PAGINA "
        ]);
    }

    public function query()
    {
        $carritos = DB::select('select id_carritos_abandonados as id_carritos, id_tienda_nube, nombre_contacto, cel_contacto,
                                    email_contacto,total, estado, fecha, vendedora, (select count(*) from samira.notas_carritos_abandonados
                                    where id_carritos_abandonados = id_carritos) as cant_notas
                                    from samira.carritos_abandonados as carritos
                                    where estado = 0
                                    ORDER by fecha DESC ');
        ob_start('ob_gzhandler');
        return Response::json($carritos);
    }

    public function vendedoras()
    {
        $arrVendedoras = [];
        $vendedoras = Vendedores::where('Tipo', '<>', 0)->get();
        for ($i = 0; $i < $vendedoras->count(); $i++ ){
            $arrVendedoras[$i] = [$vendedoras[$i]->Nombre => $vendedoras[$i]->Nombre];
        }
        ob_start('ob_gzhandler');
        return Response::json($arrVendedoras);
    }

    public function updateVendedora()
    {
        $datos = Input::all();
        $articulo = Carrito_abandonado::where('id_carritos_abandonados', $datos['id_carritos']);
        $articulo->update([
            'vendedora' => $datos['vendedora']
        ]);
        return;
    }

    public function notasCarritos()
    {
        $id_carrito = Input::get('id_carrito');
        DB::statement("SET lc_time_names = 'es_ES'");
        $notas_carrito = DB::select('SELECT DATE_FORMAT(fecha, "%d de %M %Y %k:%i") AS fechaFormateada, usuarios.name as nombre,
                                        notas_carritos.notas as comentario
                                        from samira.notas_carritos_abandonados as notas_carritos
                                        INNER JOIN samira.users as usuarios ON usuarios.id = notas_carritos.users_id
                                        WHERE notas_carritos.id_carritos_abandonados = "'. $id_carrito . '"
                                        ORDER BY fecha DESC');
        return Response::json($notas_carrito);
    }

    public function agrrgarNotaCarritoAbandonado()
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateTimeString();
        Notas_Carrito_abandonado::create([
            'id_carritos_abandonados' => Input::get('id_carrito'),
            'users_id' => Input::get('user_id'),
            'notas' => Input::get('textarea'),
            'fecha' => $fecha
        ]);
        return Response::json('ok');
    }

    public function finalizarCarrito()
    {
        $id_carrito = Input::get('id_carritos');
        $carrito = Carrito_abandonado::where('id_carritos_abandonados',$id_carrito);
        $carrito->update([
            'estado' => 1,
        ]);
        return;
    }
}
