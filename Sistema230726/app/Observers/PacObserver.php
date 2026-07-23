<?php

namespace App\Observers;

use App\Models\Pac;
use App\Models\Departamento;
use App\Models\Especie;
use App\Models\FuenteFinanciamiento;
use App\Models\Estado;
use App\Services\BitacoraService;

class PacObserver
{
    protected static $bloqueo = false;

    private function traducirValor($campo, $valor)
    {
        if (is_null($valor) || $valor === '') return 'Nulo';

        try {
            switch ($campo) {
                case 'departamento_id':
                    return Departamento::find($valor)->detalle ?? "ID: $valor";
                case 'especie_id':
                    return Especie::find($valor)->detalle ?? "ID: $valor";
                case 'fuente_financiamiento':
                    return FuenteFinanciamiento::find($valor)->detalle ?? "ID: $valor";
                case 'estado_id': 
                case 'estado_modificacion':
                    return Estado::find($valor)->detalle ?? "ID: $valor";
                default:
                    return $valor; 
            }
        } catch (\Exception $e) {
            return $valor;
        }
    }

    public function created(Pac $pac): void
    {
        if (self::$bloqueo) return;
        self::$bloqueo = true;

        $datos = "";
        foreach ($pac->getAttributes() as $campo => $valor) {
            if (in_array($campo, ['created_at', 'updated_at'])) continue;
            $datos .= "{$campo}: " . $this->traducirValor($campo, $valor) . "\n";
        }

        BitacoraService::registrar(
            'PAC', 
            'CREAR', 
            "Nuevo registro de PAC", 
            null, 
            $datos, 
            $pac->id
        );
    }

    public function updated(Pac $pac): void
    {
        if (self::$bloqueo) return;
        $cambios = $pac->getDirty();

        if (empty($cambios) || (count($cambios) === 1 && isset($cambios['updated_at']))) {
            return;
        }

        self::$bloqueo = true;

        $anterior = ""; $nuevo = "";
        foreach ($cambios as $campo => $valorNuevo) {
            if ($campo === 'updated_at') continue;
            $valorAnterior = $pac->getOriginal($campo);
            $anterior .= "{$campo}: " . $this->traducirValor($campo, $valorAnterior) . "\n";
            $nuevo .= "{$campo}: " . $this->traducirValor($campo, $valorNuevo) . "\n";
        }

        if (!empty($nuevo)) {
            BitacoraService::registrar(
                'PAC', 
                'EDITAR', 
                "Modificación de datos", 
                $anterior, 
                $nuevo, 
                $pac->id
            );
        }
    }

    /**
     * REGISTRA LA ELIMINACIÓN
     */
    public function deleted(Pac $pac): void
    {
        if (self::$bloqueo) return;
        self::$bloqueo = true;

        // Capturamos los datos que tenía el registro antes de desaparecer
        $datosEliminados = "";
        foreach ($pac->getAttributes() as $campo => $valor) {
            $datosEliminados .= "{$campo}: " . $this->traducirValor($campo, $valor) . "\n";
        }

        BitacoraService::registrar(
            'PAC', 
            'ELIMINAR', 
            "Se eliminó el registro de PAC ID: " . $pac->id, 
            $datosEliminados, 
            null, 
            $pac->id // Usamos el ID como referencia del proyecto
        );
    }
}