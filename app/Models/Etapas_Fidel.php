<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Etapas_Fidel extends Model
{
    protected $table = 'clientes_fidel_etapas';
    protected $fillable = ['id_clientes_fidel_etapas','nombre_etapa'];
    public $timestamps = false;
}
