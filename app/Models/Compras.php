<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    protected $table = 'compras';
    public $timestamps = false;
    protected $fillable = ['OrdenCompra','Articulo','Detalle','Cantidad','PrecioOrigen',
        'PrecioArgen','Proveedor','FechaCompra','TipoOrden','Observaciones'];
}
