<?php
namespace App\Listeners;

use App\Events\PacRechazadoEvent;
use App\Models\Modalidad;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon;

class CambiarEstadoModalidadListener
{
    public function handle(PacRechazadoEvent $event)
    {
        $pac = $event->pac;
        $modalidades = Modalidad::where('id_proyecto', $pac->id)->get();
        $fechaActual = Carbon::now();

        foreach ($modalidades as $modalidad) {
            $modalidad->estado_id = 6; // Rechazado
            $modalidad->observacion = 'Se cambia su estado a SUSPENDIDA con fecha ' . $fechaActual->format('d-m-Y H:i:s');
            $modalidad->save();
        }
    }
}