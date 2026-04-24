<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;
    protected $fillable = [
        'detalle'
    ];

    public function especies()
    {
        return $this->hasMany(Especie::class, 'departamento_id');
    }
    
}
