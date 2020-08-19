<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteArtiulos extends Model
{
    protected $table = 'reportearticulo';
    public $timestamps = false;
    protected $fillable = ['Proveedor','Pais','Articulo','Detalle','Costo','Ganancia','Cantidad',
        'PrecioOrigen','Moneda','PrecioConvertido','PrecioManual','PrecioArgDolar','PrecioArgenPesos','PrecioVenta','CotizacionDolar'];
}
