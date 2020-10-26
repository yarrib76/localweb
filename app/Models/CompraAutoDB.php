<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class CompraAutoDB extends Model
{
    protected $table = 'compraautomatica';
    public $timestamps = false;
    protected $fillable = ['idCompraAutomatica','articulo','cant_alerta'];
}
