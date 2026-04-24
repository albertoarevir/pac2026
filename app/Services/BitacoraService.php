<?php

namespace App\Services;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class BitacoraService
{
    public static function registrar($modulo, $accion, $descripcion, $anterior = null, $nuevo = null, $proyecto_id = null)
    {
        return Bitacora::create([
            'user_id'          => Auth::id(),
            'modulo'           => $modulo,
            'proyecto_id'      => $proyecto_id, // Guarda el ID del proyecto capturado
            'accion'           => $accion,
            'descripcion'      => $descripcion,
            'campo_anterior'   => $anterior,
            'campo_modificado' => $nuevo,
            'ip'               => Request::ip(),
            'user_agent'       => Request::userAgent(),
        ]);
    }
}