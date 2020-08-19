<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class ImportExcelControl extends Model
{
    protected $table = 'importexeclcontrol';
    protected $fillable = ['Articulo','PrecioOrigenViejo','PrecioOrigenNuevo','PecioConvertidoViejo',
        'PrecioConvertidoNuevo','PrecioManualViejo','PrecioManualNuevo','Fecha'];
}
