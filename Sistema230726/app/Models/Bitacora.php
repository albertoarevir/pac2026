<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id', 
    'modulo', 
    'proyecto_id', // Asegúrate de incluirlo aquí
    'accion', 
    'descripcion', 
    'campo_anterior', 
    'campo_modificado', 
    'ip', 
    'user_agent'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
