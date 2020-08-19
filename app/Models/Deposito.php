<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    protected $table = 'deposito';
    public $timestamps = false;
    protected $fillable = ['Articulo','Cantidad','Detalle','PrecioOrigen','PrecioCOnvertido','Moneda',
        'PrecioManual','Gastos','Ganancia','Proveedor','ImageName','ProveedorSKU'];
}
