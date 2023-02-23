<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Notas_Clientes_Fidel extends Model
{
    protected $table = 'notas_clientes_fidel';
    public $timestamps = false;
    protected $fillable = ['id_notas_clientes_fidel','id_clientes_fidelizacion','fecha_creacion','notas','users_id'];
}
