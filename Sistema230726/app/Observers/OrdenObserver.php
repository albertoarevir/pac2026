<?php

namespace App\Observers;

use App\Models\Orden;
use App\Models\EstadoCompra;
use App\Services\BitacoraService;

class OrdenObserver
{
    // Propiedad estática para evitar duplicados en la misma petición
    protected static $bloqueo = false;

    /**
     * Traduce IDs numéricos a nombres descriptivos.
     */
    private function traducirValor($campo, $valor)
    {
        if (is_null($valor) || $valor === '') return 'Nulo';

        try {
            switch ($campo) {
                case 'estado_id':
                    return EstadoCompra::find($valor)->detalle ?? "ID: $valor";
                default:
                    return $valor;
            }
        } catch (\Exception $e) {
            return $valor;
        }
    }

    public function created(Orden $orden): void
    {
        if (self::$bloqueo) return;
        self::$bloqueo = true;

        $datos = "";
        foreach ($orden->getAttributes() as $campo => $valor) {
            if (in_array($campo, ['created_at', 'updated_at'])) continue;
            $datos .= "{$campo}: " . $this->traducirValor($campo, $valor) . "\n";
        }

        BitacoraService::registrar(
            'ORDEN DE COMPRAS', 
            'CREAR', 
            "Nueva orden registrada", 
            null, 
            $datos, 
            $orden->id_proyecto // Captura el ID de proyecto del esquema
        );
    }

    public function updated(Orden $orden): void
    {
        if (self::$bloqueo) return;

        $cambios = $orden->getDirty();

        // Ignorar si no hay cambios o solo cambió el timestamp
        if (empty($cambios) || (count($cambios) === 1 && isset($cambios['updated_at']))) {
            return;
        }

        self::$bloqueo = true;

        $anterior = ""; $nuevo = "";
        foreach ($cambios as $campo => $valorNuevo) {
            if ($campo === 'updated_at') continue;

            $valorAnterior = $orden->getOriginal($campo);
            $anterior .= "{$campo}: " . $this->traducirValor($campo, $valorAnterior) . "\n";
            $nuevo .= "{$campo}: " . $this->traducirValor($campo, $valorNuevo) . "\n";
        }

        if (!empty($nuevo)) {
            BitacoraService::registrar(
                'ORDEN DE COMPRAS', 
                'EDITAR', 
                "Modificación de orden", 
                $anterior, 
                $nuevo, 
                $orden->id_proyecto
            );
        }
    }

   public function deleted(Orden $orden): void
{
    // Usamos una variable local para el ID por si el objeto se limpia rápido
    $proyectoId = $orden->id_proyecto; 

    \App\Services\BitacoraService::registrar(
        'ORDENES', 
        'ELIMINAR', 
        "Se eliminó la orden número: " . ($orden->numero ?? $orden->id), 
        "Datos al eliminar: \n" . json_encode($orden->getAttributes(), JSON_PRETTY_PRINT), 
        null, 
        $proyectoId // Usamos el campo id_proyecto de tu tabla
    );
}
}