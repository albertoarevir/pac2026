<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Departamento; 

class Presupuesto extends Model
{
    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'year',
        'clasificador',
        'item',
        'monto',
        'departamento_id',      
        'observaciones',  
    ];

    /**
     * Relación con el modelo Departamento.
     * Un presupuesto pertenece a un departamento.
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }
}