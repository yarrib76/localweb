<?php

namespace Donatella\Http\Controllers\TiendaNube;

use Carbon\Carbon;
use Donatella\Ayuda\TnubeConnect;
use Donatella\Http\Controllers\Api\GeneraNroPedidos;
use Donatella\Http\Controllers\ProveedorEcomerce\TiendaNube;
use Donatella\Models\Clientes;
use Donatella\Models\ControlPedidos;
use Donatella\Models\NroPedidos;
use Donatella\Models\Provincias;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use TiendaNube\API;
use TiendaNube\Auth;

class Ordenes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function index()
    {
        return view('tiendanube.orden.reporte');
    }
    public function main()
    {

        /*
        $code = '41adb708b6ec1c0a0bffca99bb0f0d4cb45603fe';
        $auth = new Auth(1056, 'gZck3RgyMZTA5YWGOXwrxqDG4pK10nKNJ1Ha2VaI62PwBFFC');
        $store_info = $auth->request_access_token($code);
        dd($store_info);
        */

        $cantidadPorPaginas = 10;
        $fecha_min = Input::get('fecha_min');
        $fecha_max = Input::get('fecha_max');
        $store_id = Input::get('store_id');
        $tnConnect = new TnubeConnect();
        $connect = $tnConnect->getConnectionTN($store_id);
        $api = new API($store_id, $connect[0]['access_token'], $connect[0]['appsName']);
        $cantidadConsultas = $this->obtengoCantConsultas($api,$cantidadPorPaginas,$fecha_min,$fecha_max);
        $result = $this->getOrdenes($api,$cantidadConsultas,$cantidadPorPaginas,$connect[0]['tienda'],$fecha_min,$fecha_max);
        return $result;
    }

    public function getOrdenes($api,$cantidadConsultas,$cantidadPorPaginas,$tienda,$fecha_min,$fecha_max)
    {
        $count = 0;
        $listaOrdenes = [];
        $fechaInicio = '2020-09-17';
        // $fechaActual = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        for ($i = 1; $i <= $cantidadConsultas; $i++) {
            $ordenesTiendaNube = $api->get("orders?page=$i&per_page=$cantidadPorPaginas&status=open&created_at_min=$fecha_min&created_at_max=$fecha_max");
            // dd($ordenesTiendaNube->body);
            foreach ($ordenesTiendaNube->body as $orden) {
                // dd($orden);
                $crearPedido = $this->verificarOrgen($orden->number,$tienda);
                $fecha = date('Y-m-d',strtotime($orden->created_at));
                if ($crearPedido && ($fecha >= $fechaInicio)){
                    /*

                    */
                    if ($orden->customer->default_address->locality){
                        $localidad = $orden->customer->default_address->locality;
                    }else {
                        $localidad = $orden->customer->default_address->city;
                    }
                    $listaOrdenes[$count] = ['Nombre' => (substr($orden->customer->name, 0, strrpos($orden->customer->name, ' ') + 0))
                                            ,'Apellido' => substr($orden->customer->name, strrpos($orden->customer->name, ' ') + 1, strlen($orden->customer->name) + 1)
                                            ,'Mail' => $orden->customer->email
                                            ,'Direccion' => ($orden->customer->default_address->address . " " . $orden->customer->default_address->number)
                                            ,'Telefono' => $orden->customer->phone
                                            ,'Cuit' => $orden->customer->identification
                                            ,'Provincia' => $orden->customer->billing_province
                                            ,'Localidad' => $localidad
                                            ,'OrdenWeb' => $orden->number
                                            ,'TotalWeb' => $orden->total
                                            ,'Tienda' =>$tienda];
                    $count++;
                }
            }
        }
        // return json_encode($listaOrdenes, JSON_UNESCAPED_UNICODE);
        return Response::json($listaOrdenes);
    }

    public function nuevoPedido(){

        $ordenes = (Input::get('ordenes'));
        $ordenes = json_decode($ordenes);
        foreach ($ordenes as $orden){
            $cliente_id = $this->verificarCliente($orden);
            $this->crearControlPedido($cliente_id,$orden);
        }
        return Response::json(['ok']);
    }
    /*Debido a que la API de tienda nube, no puede enviar mas de 200 productos por pagina, lo que hace esta funcion
    es tomar la cantidad de productos que hay en tienda nube y lo divide por la cantidad de productos por pagina. Con
    Esta informacion la urilizo en el FOR para solicitar todas las paginas que tienen los articulos*/
    public function obtengoCantConsultas($api,$cantidadPorPaginas,$fecha_min,$fecha_max)
    {
        try {
            $query = $api->get("orders?page=1&per_page=1&status=open&created_at_min=$fecha_min&created_at_max=$fecha_max");
            $cantidadConsultas = (ceil(($query->headers['x-total-count'] / $cantidadPorPaginas)));
        }catch (API\Exception $e){
            //Si no hay resultado, para que no de error la consulta se pasa $cantidadConsultas = 0
            $cantidadConsultas = 0;
        }
        return $cantidadConsultas;
    }

    /*La funcioon verifica si existe un pedido con el # de orden que llega y devuelve si se puede crear o un nuevo pedido.
    Si devuelve true, se puede crear un pedido porque no existe ninguno con ese # de orden
    Si devuelve false, no se puede crear ya que hay un pedido con ese # de orden */
    private function verificarOrgen($nroOrden,$tienda)
    {
        $pedido = ControlPedidos::where('ordenWeb',$nroOrden)
                                  ->where('local',$tienda)->get();
        if ($pedido->isEmpty()) {
            return true;
        }else {
            return false;
        }
    }

    /*La funcioon verifica si existe el cliente, verificando el mail y devuelve si se puede crear o un nuevo cliente.
    Si devuelve true, se puede crear el cliente porque no existe ninguno con ese mail
    Si devuelve false, no se puede crear ya que hay un cliente con esa direccion de mail */
    private function verificarCliente($orden){
        $cliente = Clientes::where('mail',$orden->Mail)->get();
        if ($cliente->isEmpty()){
            $cliente_id = $this->crearCliente($orden);
            return $cliente_id;
        }else {
            return $cliente[0]->id_clientes;
        }
    }

    private function crearCliente($datos){
        $id_Provincia = $this->getProvincia_id($datos->Provincia);
        $cliente_id = Clientes::create([
            "Nombre" => $datos->Nombre,
            "Apellido" => $datos->Apellido,
            "Apodo" => "",
            "Direccion" => $datos->Direccion,
            "Mail" => $datos->Mail,
            "Telefono" => $datos->Telefono,
            "Cuit" => $datos->Cuit,
            "Localidad" => $datos->Localidad,
            "Provincia" => "",
            "Id_provincia" => $id_Provincia
        ]);
        return $cliente_id->id;
    }

    private function getProvincia_id ($provincia){
        $id = Provincias::where('nombre',$provincia)->get();
        if (!$id->isEmpty()){
            return $id[0]->id;
        }else {
            return 1;
        }
    }

    private function crearControlPedido($cliente_id,$orden){
        $nroPedido = $this->getNroPedido();
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        ControlPedidos::create([
            'nroPedido' => $nroPedido['nroPedido'],
            'id_cliente' => $cliente_id,
            'Vendedora' => 'PAGINA ',
            'Fecha' => $fecha,
            'Total' => 0,
            'OrdenWeb' => $orden->OrdenWeb,
            'cajera' => 'ReplicaTN',
            'totalweb' => $orden->TotalWeb,
            'local' => $orden->Tienda
        ]);
    }

    private function getNroPedido(){
        $generaNroPedido = new GeneraNroPedidos();
        $nroPedido = $generaNroPedido->Generar();
        return $nroPedido;
    }
}
