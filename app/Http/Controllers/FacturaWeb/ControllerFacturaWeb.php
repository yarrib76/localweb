<?php

namespace Donatella\Http\Controllers\FacturaWeb;

use Carbon\Carbon;
use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\ControlPedidos;
use Donatella\Models\FacturacionHist;
use Donatella\Models\Facturas;
use Donatella\Models\Tipo_Pagos;
use Donatella\Models\Vendedores;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ControllerFacturaWeb extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    public function view(Request $request)
    {
        $nameCajera = Auth::user()->name;
        $clienteDireccionIP = $request->ip();
        $auto = $this->autorizacionFacturaWeb($clienteDireccionIP);
        if ($auto){
            $control = 'Autorizado';
        }else $control = 'NoAutorizado';
        //Provisorio para poder acceder desde samira y hacer pruebas
        //$control = 'Autorizado';
        return view('facturaweb.factmenuprincipal', compact('nameCajera','control'));
    }

    public function getArticulos()
    {
        $botonManual = Input::get('botonManual');
        $nroArticulo = Input::get('nroArticulo');
        if ($botonManual === 'true'){
            $articulo = DB::select('select Articulo, Detalle, Cantidad from samira.articulos
                                      where articulo = "'.$nroArticulo.'"');
            return Response::json($articulo);
        }
        $articulos = DB::select('select Articulo, Detalle, Cantidad from samira.articulos');
        return Response::json($articulos);
    }

    public function precioArticulo()
    {
        $nroArticulo = Input::get('nroArticulo');
        $articulo = Articulos::where('Articulo', '=', $nroArticulo)->get();
        $precio = new Precio();
        $precio = $precio->query($articulo[0]);
        return $precio;
    }

    public function listaVendedoras(){
        $vendedoras = Vendedores::where('tipo', '<>', '0')->get(); //Las que tienen tipo 0 estan desabilitadas
        return Response::json($vendedoras);
    }

    public function listaTipoPagos(){
        $tipo_pagos = Tipo_Pagos::all();
        return Response::json($tipo_pagos);
    }

    public function getClientes(){
        $clientes = DB::select('select id_clientes, nombre, apellido, mail from samira.clientes;');
        return Response::json($clientes);
    }

    public function facturar(){
        $fecha =  Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        $articulosFactura = json_decode((Input::get('articulos')));
        $cliente_id = Input::get('cliente_id');
        $tipo_pago_id = Input::get('tipo_pago_id');
        $nroFactura = Input::get('nroFactura');
        $total = Input::get('total');
        $descuento = Input::get('descuento');
        $porcentajeDescuento = Input::get('porcentajeDescuento');
        $envio = Input::get('envio');
        $totalEnvio = Input::get('totalEnvio');
        $gananciaTotal = 0.0;
        $precioArgentina = 0;
        $vendedora = Input::get('vendedora');
        $listoParaEnvio = Input::get('listoParaEnvio');
        $nroPedido = Input::get('nroPedido');
        $esPedido = Input::get('esPedido');

        foreach ($articulosFactura as $articuloFactura) {
            $gananciaTotal += $articuloFactura->Ganancia;
            $precioArgentina += $articuloFactura->PrecioArgen;
            $this->descontarArticulos($articuloFactura->Articulo, $articuloFactura->Cantidad);
            $this->addArticulosToFactura($articuloFactura,$nroFactura,$fecha,$vendedora);
        }
        $this->creaFacturaHistorica($nroFactura,$total,$porcentajeDescuento,$descuento,$gananciaTotal,$fecha,$cliente_id,$envio,$totalEnvio,$tipo_pago_id,$precioArgentina);
        $this->acturlizarNroFactura();
        if($esPedido = "SI"){
            $this->actualizaControlPedidos($nroFactura,$listoParaEnvio,$nroPedido);
        }
        // dump($vendedora);
        // dump($cliente_id,$tipo_pago_id, $nroFactura, $total, $descuento, $porcentajeDescuento, $envio, $totalEnvio);
    }

    public function getNroFactura(){
        $nroFactura = DB::select('select * from samira.nrofactura');
        return $nroFactura;
    }

    public function autorizacionFacturaWeb($clienteDireccionIP){
        $autorizacion = DB::select('select * from samira.autorizacion_facturaweb
                                    where ip_autorizada = "'.$clienteDireccionIP.'"');

        /*Agregue esta linea para puentear la utorización en perido de prueba*/
       // return true;

        if (!empty($autorizacion)){
            return true;
        } else return false;
    }

    public function descontarArticulos($nroArticulo,$cantidad)
    {
        $articulo = Articulos::where('Articulo', '=', $nroArticulo);
        $contidadActual = $articulo->get()[0]['Cantidad'];
        $articulo->update([
            'Cantidad' => $contidadActual - $cantidad,
        ]);
    }

    public function addArticulosToFactura($articuloFactura,$nroFactura,$fecha,$vendedora)
    {
        Facturas::create([
            'NroFactura' => $nroFactura,
            'Articulo' => $articuloFactura->Articulo,
            'Detalle' => $articuloFactura->Detalle,
            'Cantidad' => $articuloFactura->Cantidad,
            'PrecioArgen' => $articuloFactura->PrecioArgen,
            'PrecioUnitario' => $articuloFactura->PrecioUnitario,
            'PrecioVenta' => $articuloFactura->PrecioVenta,
            'Ganancia' => $articuloFactura->Ganancia,
            'Cajera' => $articuloFactura->Cajera,
            'Vendedora' => $vendedora,
            'Fecha' => $fecha,
        ]);
    }


    public function creaFacturaHistorica($nroFactura,$total,$porcentajeDescuento,$descuento,$gananciaTotal,$fecha,$cliente_id,$envio,$totalEnvio,$tipo_pago_id,$precioArgentina)
    {
        if ($porcentajeDescuento > 0){
            $gananciaTotal = $descuento - $precioArgentina;
        } else {
            $descuento = null;
        }
        FacturacionHist::create([
            'NroFactura' => $nroFactura,
            'Total' => $total,
            'Porcentaje' => $porcentajeDescuento,
            'Descuento' => $descuento,
            'Ganancia' => $gananciaTotal,
            'Fecha' => $fecha,
            'Estado' => 0,
            'id_clientes' => $cliente_id,
            'envio' => $envio,
            'totalEnvio' => $totalEnvio,
            'id_tipo_pago' => $tipo_pago_id
        ]);
    }

    public function acturlizarNroFactura()
    {
        DB::select('UPDATE samira.nrofactura SET NroFactura = NroFactura + 1' );
    }

    /*PEDIDOS*/
    public function getPedidos()
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $pedidos = DB::select('SELECT control.nropedido, CONCAT (cli.nombre, "," , cli.apellido) as Cliente, control.ordenWeb, control.total,
                            DATE_FORMAT(control.fecha, "%d de %M %Y") AS fecha, control.vendedora, control.id_cliente
                            FROM samira.controlpedidos as control
                            inner join samira.clientes cli ON control.id_cliente = cli.id_clientes
                            WHERE estado = 1
                            ORDER BY control.nropedido DESC;');
        return Response::json($pedidos);
    }

    public function getPedidosArticulos(){
        $nroPedido = Input::get('nroPedido');
        $pedidosArticulos = DB::select('select * from samira.pedidotemp
                                        where nroPedido = "'.$nroPedido.'"');
        return Response::json($pedidosArticulos);
    }

    public function actualizaControlPedidos($nroFactura,$listoParaEnvio,$nroPedido)
    {
        $controlPedido = ControlPedidos::where('nroPedido', '=',$nroPedido);
        $controlPedido->update([
            'nrofactura' => $nroFactura,
            'Estado' => 0,
            'empaquetado' => $listoParaEnvio,
        ]);
    }
    /*PEDIDOS*/
}
