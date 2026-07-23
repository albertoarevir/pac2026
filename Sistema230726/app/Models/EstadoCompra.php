<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCompra extends Model
{
    use HasFactory;
    protected $fillable = [
        'detalle',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'id', 'estadocompras_id');
    }
    protected $table = 'estado_compras'; // Asegúrate de que el nombre de la tabla sea correcto
}
