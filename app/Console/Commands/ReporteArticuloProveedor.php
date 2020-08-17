<?php

namespace Donatella\Console\Commands;

use Carbon\Carbon;
use Donatella\Ayuda\Precio;
use Donatella\Models\Articulos;
use Donatella\Models\Dolar;
use Donatella\Models\Proveedores;
use Donatella\Models\ReporteArtiulos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReporteArticuloProveedor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reporte1:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Llena tabla ReporteArticulo';

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
        $articulos = Articulos::orderBy("Proveedor")->get();
        $precioAydua = new Precio();
        $articulosPreoveedores[] = [];
        DB::select('truncate table samira.reportearticulo');
        foreach ($articulos as $articulo) {
            if (!is_null($articulo->Proveedor)) {
                $precio = $precioAydua->query($articulo);
                $proveedor = Proveedores::where('Nombre', $articulo->Proveedor)->get();
                ReporteArtiulos::create([
                    'Proveedor' => $proveedor[0]->Nombre,
                    'Pais' => $proveedor[0]->Pais,
                    'Articulo' => $articulo->Articulo,
                    'Detalle' => $articulo->Detalle,
                    'Costo' => $precio[0]['Gastos'],
                    'Ganancia' => $precio[0]['Ganancia'],
                    'Cantidad' => $articulo->Cantidad,
                    'PrecioOrigen' => $articulo->PrecioOrigen,
                    'Moneda' => $articulo->Moneda,
                    'PrecioConvertido' => $articulo->PrecioConvertido,
                    'PrecioManual' => $articulo->PrecioManual,
                    'PrecioArgDolar' => ($articulo->PrecioConvertido * $precio[0]['Gastos']),
                    'PrecioArgenPesos' => ($articulo->PrecioManual * $precio[0]['Gastos']),
                    'PrecioVenta' => $precio[0]['PrecioVenta'],
                    'CotizacionDolar' => Dolar::all()[0]->PrecioDolar
                ]);
            }
        }
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"))->toDateString();
        DB::table('statusreportes')->update(array('Fecha' => $fecha));
    }
}
