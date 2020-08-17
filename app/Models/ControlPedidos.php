<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class ControlPedidos extends Model
{
    protected $table = 'controlpedidos';
    public $timestamps = false;
    protected $fillable = ['nroPedido','Vendedora','Fecha','Estado','Total', 'OrdenWeb','ultactualizacion'];

    public function clientes()
    {
        return $this->belongsTo('Donatella\Models\Clientes', 'id_cliente', 'id_clientes');
    }
}
