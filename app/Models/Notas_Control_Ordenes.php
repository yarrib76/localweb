<?php

namespace Donatella\Models;


use Illuminate\Database\Eloquent\Model;

class Notas_Control_Ordenes extends Model
{
    protected $table = 'notas_control_orden';
    public $timestamps = false;
    protected $fillable = ['fecha_creacion','notas','users_id','id_compras'];
}