<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periferico extends Model
{
    protected $table = 'perifericos';

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Imagen',
        'Precio'
    ];
}
