<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Transferencias extends Model
{
    protected $table = 'transferencias';
    protected $fillable = ['Articulo','Cantidad','UbicacionActual','UbicacionNueva','Usuario','Fecha'];
    public $timestamps = false;

    public function articulos(){
        return $this->belongsTo('Donatella\Models\Articulos', 'articulo');
    }
}
