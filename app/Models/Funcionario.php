<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Funcionario extends Model
{
    use HasFactory;
    use HasRoles;
    protected $fillable = [
        'Rut',
        'Codigo',
        'Grado',
        'Nombres',
        'Apellidos',
        'Dotacion',
        'Email',
        
    ];
}
