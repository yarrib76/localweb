<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedores extends Model
{
    protected $table = 'vendedores';
    protected $fillable = ['Id','Nombre','Password','Tipo'];

    public $timestamps = false;
}
