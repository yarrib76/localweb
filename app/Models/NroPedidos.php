<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class NroPedidos extends Model
{
    protected $table = 'nropedido';
    public $timestamps = false;
    protected $fillable = ['Nropedido'];
}
