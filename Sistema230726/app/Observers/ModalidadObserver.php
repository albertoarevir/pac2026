<?php

namespace App\Observers;

use App\Models\Modalidad;
use App\Models\EstadoLicitacion;
use App\Services\BitacoraService;

class ModalidadObserver
{
    protected static $bloqueo = false;

    private function traducirValor($campo, $valor)
    {
        if (is_null($valor) || $valor === '') return 'Nulo';
        try {
            switch ($campo) {
                case 'estado_id':
                    return EstadoLicitacion::find($valor)->detalle ?? "ID: $valor";
                default:
                    return $valor;
            }
        } catch (\Exception $e) {
            return $valor;
        }
    }

    public function created(Modalidad $modalidad): void
    {
        if (self::$bloqueo) return;
        self::$bloqueo = true;

        $datos = "";
        foreach ($modalidad->getAttributes() as $campo => $valor) {
            if (in_array($campo, ['created_at', 'updated_at'])) continue;
            $datos .= "{$campo}: " . $this->traducirValor($campo, $valor) . "\n";
        }

        BitacoraService::registrar(
            'LICITACIONES', 
            'CREAR', 
            "Registro inicial", 
            null, 
            $datos, 
            $modalidad->id_proyecto
        );
    }

    public function updated(Modalidad $modalidad): void
    {
        if (self::$bloqueo) return;
        $cambios = $modalidad->getDirty();

        if (empty($cambios) || (count($cambios) === 1 && isset($cambios['updated_at']))) {
            return;
        }

        self::$bloqueo = true;

        $anterior = ""; $nuevo = "";
        foreach ($cambios as $campo => $valorNuevo) {
            if ($campo === 'updated_at') continue;
            $valorAnterior = $modalidad->getOriginal($campo);
            $anterior .= "{$campo}: " . $this->traducirValor($campo, $valorAnterior) . "\n";
            $nuevo .= "{$campo}: " . $this->traducirValor($campo, $valorNuevo) . "\n";
        }

        if (!empty($nuevo)) {
            BitacoraService::registrar(
                'LICITACIONES', 
                'EDITAR', 
                "Cambio detectado", 
                $anterior, 
                $nuevo, 
                $modalidad->id_proyecto
            );
        }
    }

    /**
     * MÉTODO AGREGADO: Registra la eliminación en la bitácora
     */
    public function deleted(Modalidad $modalidad): void
    {
        if (self::$bloqueo) return;
        self::$bloqueo = true;

        // Capturamos los datos antes de que se elimine el registro
        $datosEliminados = "";
        foreach ($modalidad->getAttributes() as $campo => $valor) {
            $datosEliminados .= "{$campo}: " . $this->traducirValor($campo, $valor) . "\n";
        }

        BitacoraService::registrar(
            'LICITACIONES', 
            'ELIMINAR', 
            "Se eliminó la licitación/modalidad ID: " . $modalidad->id, 
            $datosEliminados, 
            null, 
            $modalidad->id_proyecto // Usamos el campo id_proyecto de tu tabla
        );
    }
}