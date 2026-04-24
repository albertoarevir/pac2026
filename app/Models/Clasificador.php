<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clasificador extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo_id',
        'detalle'
    ];

  public function codigos()
  {
    return $this->hasMany(Codigo::class, 'codigo_id', 'codigo_id'); 

  }


}
