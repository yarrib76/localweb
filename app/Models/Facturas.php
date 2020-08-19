<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
    protected $table = 'factura';
    protected $fillable = ['NroFactura', 'Articulo','Detalle' ,'Fecha','Cantidad'];

}
