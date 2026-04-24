<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Codigo extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigopre',
        'detalle',
        'codigo_id'
    ];
   

    public function clasificador()
        {
            return $this->belongsTo(Clasificador::class, 'codigo_id', 'codigo_id'); 
        }

}

