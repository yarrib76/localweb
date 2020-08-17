<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class PedidosTemp extends Model
{
    protected $table = 'pedidotemp';
    public $timestamps = false;
    protected $fillable = ['nroPedido','Articulo','Detalle','Cantidad','PrecioArgen','PrecioUnitario',
    'PrecioVenta','Ganancia','Descuento','Cajera','Vendedora','Fecha', 'Estado','Id'];
}
