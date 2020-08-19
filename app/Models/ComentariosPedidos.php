<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class ComentariosPedidos extends Model
{
    protected $table = 'comentariospedidos';
    public $timestamps = false;
    protected $fillable = ['users_id','controlpedidos_id','comentario', 'fecha'];
}
