<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;
    protected $fillable = [
        'detalle',
    ];

     protected $table = 'estados'; // La tabla 'estados' general

    public function modalidades()
    {
        return $this->hasMany(Modalidad::class, 'estado_id');
    }
}