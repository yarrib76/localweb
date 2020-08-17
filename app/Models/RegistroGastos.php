<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroGastos extends Model
{
    protected $table = 'vendedores';
    protected $fillable = ['id','nombre', 'apellido'];

    public function items(){
        return $this->belongsTo('Donatella\Models\Items', 'item_id');
    }

}
