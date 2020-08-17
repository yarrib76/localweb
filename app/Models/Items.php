<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Items extends Model
{
    protected $table = 'items';
    protected $fillable = ['nombre'];

    public function gastos(){
        return $this->belongsTo('Donatella\Models\RegistroGastos', 'id');
    }

    public function itemsTotal(){
        return DB::select('SELECT fecha, item_id as id, items.nombre, sum(importe) as Total FROM gastosadmin.registro_gastos inner join gastosadmin.items
        on item_id = items.id group by items.nombre ORDER by id ASC');
    }
    public function itemsTotalAnioMes($anio,$mes){
        return DB::select('SELECT fecha, item_id as id, items.nombre, sum(importe) as Total FROM gastosadmin.registro_gastos inner join gastosadmin.items
        on item_id = items.id where fecha >= "'. $anio . '/' . $mes . '/01'.'" and
        fecha <= "'. $anio . '/' . $mes . '/31'.'"
        group by items.nombre ORDER by Total desc');
    }
}
