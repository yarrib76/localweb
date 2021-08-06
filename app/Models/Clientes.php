<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'clientes';
    public $timestamps = true;
    protected $fillable = ['Nombre','Apellido','Direccion','Mail','Telefono','Cuit','Localidad','Provincia','Apodo',
        'Id_provincia','encuesta'];

    public function provincias(){
        return $this->belongsTo('Donatella\Models\Provincias', 'id_provincia', 'id');
    }
}
