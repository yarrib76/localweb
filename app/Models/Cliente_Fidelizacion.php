<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente_Fidelizacion extends Model
{
    protected $table = 'clientes_fidelizacion';
    public $timestamps = false;
    protected $fillable = ['idclientes_fidelizacion','id_clientes','fecha_creacion','estado','fecha_ultima_compra','vendedora'];
}
