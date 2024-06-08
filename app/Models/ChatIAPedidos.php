<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class ChatIAPedidos extends Model
{
    protected $table = 'chatpedidosia';
    public $timestamps = false;
    protected $fillable = ['id_users','id_controlpedidos','chat', 'fecha'];
}
