<?php

namespace Donatella\Models;

use Illuminate\Database\Eloquent\Model;

class NewArtiTN extends Model
{
    protected $table = 'newartitn';
    public $timestamps = false;
    protected $fillable = ['id','Identificador de URL','Nombre','Categorías','Nombre de propiedad 1','Valor de propiedad 1','Precio',
        'Precio Promocional','SKU','Mostrar en tienda','Descripción','Título para SEO', 'Marca'];
}
