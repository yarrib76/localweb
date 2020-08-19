<?php

namespace Donatella\Http\Controllers\Articulo;

use Carbon\Carbon;
use Donatella\Ayuda\CodigoBarras;
use Donatella\Http\Requests\CreateArticuloRequests;
use Donatella\Models\Articulos;
use Donatella\Models\Compras;
use Donatella\Models\Deposito;
use Donatella\Models\Dolar;
use Donatella\Models\OrdenCompras;
use Donatella\Models\Proveedores;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use Donatella\Http\Requests;
use Donatella\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class ArticulosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Gerencia,Caja,Ventas');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articulos = DB::Select ('SELECT Arti.Articulo as Articulo, Arti.Detalle as Detalle, Arti.ProveedorSKU as ProveedorSKU, Arti.Cantidad as Cantidad,
                                    sum(if(Control.estado = 1, pedidotemp.Cantidad,0)) as Pedido, repoArt.PrecioVenta as PrecioVenta, Arti.ImageName, Arti.Web
                                    FROM samira.articulos as Arti
                                    left join samira.pedidotemp as pedidoTemp On Arti.Articulo = pedidoTemp.Articulo
                                    inner join samira.reportearticulo as repoArt On Arti.Articulo = repoArt.Articulo
                                    left join samira.controlpedidos as Control ON pedidotemp.NroPedido = Control.nropedido
                                    group by Arti.Articulo');
        //dd($articulos);
        /** Se cambia para incorpoar Cantidad en PedidosTemp
         * $articulos = Articulos::get()->load('repoArticulo');
        dd ($articulos); */
        return view('articulos.reporte', compact('articulos'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dolar = Dolar::get();
        $dolar = $dolar[0]->PrecioDolar;
        $nroOrden = OrdenCompras::get()[0]->NumeroOrden;
        return view ('articulos.nuevo', compact('dolar','nroOrden'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $codigoPais = '7798';
        $codigoBarra = new CodigoBarras();
        $articulo = $codigoPais . Input::get('Articulo');
        $codigoBit = $codigoBarra->crearDigitoCOntrol($articulo);
        $articulo = $articulo . $codigoBit;
        if (Input::get('Opciones') == 'opcion_dolares'){
            try {
                Articulos::create([
                    'Articulo' => $articulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => Input::get('PrecioConvertido'),
                    'Moneda' => 'uSs',
                    'PrecioManual' => 0,
                    'Gastos' => 0,
                    'Ganancia' => 0,
                    'Proveedor' => Input::get('proveedor_name')
                ]);

                Deposito::create([
                    'Articulo' => $articulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => Input::get('PrecioConvertido'),
                    'Moneda' => 'uSs',
                    'PrecioManual' => 0,
                    'Gastos' => 0,
                    'Ganancia' => 0,
                    'Proveedor' => Input::get('proveedor_name')
                ]);
                $this->guardarCompra($articulo,"Nuevo");

                //return redirect()->route('articulos.index');
                return view('popup.message');
            }catch (QueryException $ex) {
                switch ($ex->getCode()) {
                    case 23000:
                        return view('articulos.errores');
                        break;
                }
            }
        }

        if (Input::get('Opciones') == 'opcion_pesos'){
            try {
                Articulos::create([
                    'Articulo' => $articulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => Input::get('PrecioConvertido'),
                    'Moneda' => 'ARG',
                    'PrecioManual' => 0,
                    'Gastos' => 0,
                    'Ganancia' => 0,
                    'Proveedor' => Input::get('proveedor_name')
                ]);

                Deposito::create([
                    'Articulo' => $articulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => Input::get('PrecioConvertido'),
                    'Moneda' => 'ARG',
                    'PrecioManual' => 0,
                    'Gastos' => 0,
                    'Ganancia' => 0,
                    'Proveedor' => Input::get('proveedor_name')
                ]);
                $this->guardarCompra($articulo,"Nuevo");

                //return redirect()->route('articulos.index');
                return view('popup.message');
            }catch (QueryException $ex) {
                switch ($ex->getCode()) {
                    case 23000:
                        return view('articulos.errores');
                        break;
                }
            }
        }
        if (Input::get('Opciones') == 'opcion_manual'){
            try {
                Articulos::create([
                    'Articulo' => $articulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => 0,
                    'Moneda' => '',
                    'PrecioManual' => Input::get('Manual'),
                    'Gastos' => Input::get('Gastos'),
                    'Ganancia' => Input::get('Ganancia'),
                    'Proveedor' => Input::get('proveedor_name')
                ]);

                Deposito::create([
                    'Articulo' => $articulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => 0,
                    'Moneda' => '',
                    'PrecioManual' => Input::get('Manual'),
                    'Gastos' => Input::get('Gastos'),
                    'Ganancia' => Input::get('Ganancia'),
                    'Proveedor' => Input::get('proveedor_name')
                ]);
                $this->guardarCompra($articulo,"Nuevo");

               //return redirect()->route('articulos.index');
                return view('popup.message');
                }catch (QueryException $ex){
                    switch ($ex->getCode()){
                        case 23000: return view ('articulos.errores');
                        break;
                    }
                }


            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $articulo = Articulos::where('Articulo', '=', $id)->get();
        $articulo = $articulo[0];
        $dolar = Dolar::get();
        $dolar = $dolar[0]->PrecioDolar;
        $nroOrden = OrdenCompras::get()[0]->NumeroOrden;
        return view('articulos.edit', compact('articulo','dolar','nroOrden'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $articulo = Articulos::where('Articulo', $id);
        $deposito = Deposito::where('Articulo', $id);
        if (Input::get('Opciones') == 'opcion_dolares') {
            try {
                $articulo->update([
                    'Detalle' => Input::get('Detalle'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => Input::get('PrecioConvertido'),
                    'Moneda' => 'uSs',
                    'PrecioManual' => 0,
                    'Gastos' => 0,
                    'Ganancia' => 0,
                    'Proveedor' => Input::get('proveedor_name')
                ]);

                $deposito->update([
                    'Detalle' => Input::get('Detalle'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => Input::get('PrecioConvertido'),
                    'Moneda' => 'uSs',
                    'PrecioManual' => 0,
                    'Gastos' => 0,
                    'Ganancia' => 0,
                    'Proveedor' => Input::get('proveedor_name')
                ]);
          //      return redirect()->route('articulos.index');
            } catch (QueryException $ex) {
                switch ($ex->getCode()) {
                    case 23000:
                        return view('articulos.errores');
                        break;
                }
            }
        }

        if (Input::get('Opciones') == 'opcion_pesos') {
            try {
                $articulo->update([
                    'Detalle' => Input::get('Detalle'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => Input::get('PrecioConvertido'),
                    'Moneda' => 'ARG',
                    'PrecioManual' => 0,
                    'Gastos' => 0,
                    'Ganancia' => 0,
                    'Proveedor' => Input::get('proveedor_name')
                ]);

                $deposito->update([
                    'Detalle' => Input::get('Detalle'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => Input::get('PrecioConvertido'),
                    'Moneda' => 'ARG',
                    'PrecioManual' => 0,
                    'Gastos' => 0,
                    'Ganancia' => 0,
                    'Proveedor' => Input::get('proveedor_name')
                ]);
        //        return redirect()->route('articulos.index');
            } catch (QueryException $ex) {
                switch ($ex->getCode()) {
                    case 23000:
                        return view('articulos.errores');
                        break;
                }
            }
        }
        if (Input::get('Opciones') == 'opcion_manual') {
            try {
                $articulo->update([
                    'Detalle' => Input::get('Detalle'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => 0,
                    'Moneda' => '',
                    'PrecioManual' => Input::get('Manual'),
                    'Gastos' => Input::get('Gastos'),
                    'Ganancia' => Input::get('Ganancia'),
                    'Proveedor' => Input::get('proveedor_name')
                ]);

                $deposito->update([
                    'Detalle' => Input::get('Detalle'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioCOnvertido' => 0,
                    'Moneda' => '',
                    'PrecioManual' => Input::get('Manual'),
                    'Gastos' => Input::get('Gastos'),
                    'Ganancia' => Input::get('Ganancia'),
                    'Proveedor' => Input::get('proveedor_name')
                ]);
         //       return redirect()->route('articulos.index');
            } catch (QueryException $ex) {
                switch ($ex->getCode()) {
                    case 23000:
                        return view('articulos.errores');
                        break;
                }
            }
        }
        if(Input::get("RestaArti") == 1){
            $cantidad = ($articulo->get()[0]->Cantidad) - (int)(Input::get('Cantidad'));
            $articulo->update(['Cantidad' => $cantidad]);
            $deposito->update(['Cantidad' => $cantidad]);
        } else {
            $cantidad = ($articulo->get()[0]->Cantidad) + (int)(Input::get('Cantidad'));
            $articulo->update(['Cantidad' => $cantidad]);
            $deposito->update(['Cantidad' => $cantidad]);
        }

        $path = '/public/imagenes/articulos';
        //$imageName1 = Input::get('image_name_1');
        if (Input::file('image_name_1')) {
            $imageName1 = Input::file('image_name_1')->getClientOriginalName();
            $this->muevoArchivosImages($imageName1, $path);
            Articulos::where('Articulo', '=', $id)->update([
                'ImageName' => $imageName1,
            ]);
            Deposito::where('Articulo', '=', $id)->update([
                'ImageName' => $imageName1,
            ]);
        }

        $this->guardarCompra($articulo,"Modi");
        // return redirect()->route('articulos.index');
        return view('popup.message');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function muevoArchivosImages($imageName1,$path)
    {
        if (Input::file('image_name_1')){
            //  $imageName1 = Input::get('cod_articulo') . "1" . Carbon::now()->toTimeString() . "." . Input::file('image_name_1')->getClientOriginalExtension();
            Input::file('image_name_1')->move(
                base_path() . $path, $imageName1);
        }
    }

    public function guardarCompra($articulo,$origen)
    {
        if ($origen == "Modi"){
            $numArticulo = $articulo->get()[0]->Articulo;
        } else  $numArticulo = $articulo;
        $fecha = Carbon::now()->format('Y-m-d');
        if (Input::get('Opciones') == 'opcion_manual') {
            if (Input::get('RestaArti') == 1) {
                Compras::create([
                    'OrdenCompra' => Input::get('orden_compra'),
                    'Articulo' => $numArticulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioArgen' => Input::get('Manual'),
                    'Gastos' => Input::get('Gastos'),
                    'Ganancia' => Input::get('Ganancia'),
                    'Proveedor' => Input::get('proveedor_name'),
                    'FechaCompra' => $fecha,
                    'TipoOrden' => 1,
                    'Observaciones' => Input::get('txtobservaciones')
                ]);
            } else {
                Compras::create([
                    'OrdenCompra' => Input::get('orden_compra'),
                    'Articulo' => $numArticulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioArgen' => Input::get('Manual'),
                    'Gastos' => Input::get('Gastos'),
                    'Ganancia' => Input::get('Ganancia'),
                    'Proveedor' => Input::get('proveedor_name'),
                    'FechaCompra' => $fecha,
                    'TipoOrden' => 2,
                    'Observaciones' => Input::get('txtobservaciones')
                ]);
            }
        }
        if (Input::get('Opciones') <> 'opcion_manual') {
            if (Input::get('RestaArti') == 1) {
                Compras::create([
                    'OrdenCompra' => Input::get('orden_compra'),
                    'Articulo' => $numArticulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioArgen' => Input::get('PrecioConvertido'),
                    'Gastos' => Input::get('Gastos'),
                    'Ganancia' => Input::get('Ganancia'),
                    'Proveedor' => Input::get('proveedor_name'),
                    'FechaCompra' => $fecha,
                    'TipoOrden' => 1,
                    'Observaciones' => Input::get('txtobservaciones')
                ]);
            } else {
                Compras::create([
                    'OrdenCompra' => Input::get('orden_compra'),
                    'Articulo' => $numArticulo,
                    'Detalle' => Input::get('Detalle'),
                    'Cantidad' => Input::get('Cantidad'),
                    'PrecioOrigen' => Input::get('PrecioOrigen'),
                    'PrecioArgen' => Input::get('PrecioConvertido'),
                    'Gastos' => Input::get('Gastos'),
                    'Ganancia' => Input::get('Ganancia'),
                    'Proveedor' => Input::get('proveedor_name'),
                    'FechaCompra' => $fecha,
                    'TipoOrden' => 2,
                    'Observaciones' => Input::get('txtobservaciones')
                ]);
            }
        }
        $ordenCompras = OrdenCompras::all()[0];
        $nroOrden = $ordenCompras->NumeroOrden + 1;
        DB::table('OrdenCompras')->update(array('NumeroOrden' => $nroOrden));
    }

}
