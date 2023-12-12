<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
    protected $table = 'factura';
    public $timestamps = false;
    protected $fillable = ['NroFactura', 'Articulo','Detalle' , 'Cantidad', 'PrecioArgen', 'PrecioUnitario',
                            'PrecioVenta', 'Ganancia', 'Cajera', 'Vendedora','Fecha'];

}
