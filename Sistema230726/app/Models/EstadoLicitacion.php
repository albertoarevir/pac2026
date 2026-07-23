<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoLicitacion extends Model
{
    use HasFactory;
    protected $fillable = [
        'detalle',
    ];

      protected $table = 'estado_licitacions'; // Asegúrate de que el nombre de la tabla sea correcto

public function licitaciones()
{
    return $this->hasMany(EstadoLicitacion::class, 'estado_id');

}


}
