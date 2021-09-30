<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito_abandonado extends Model
{
    protected $table = 'carritos_abandonados';
    public $timestamps = false;
    protected $fillable = ['id_tienda_nube','nombre_contacto','cel_contacto','email_contacto','total','estado','fecha'];
}
