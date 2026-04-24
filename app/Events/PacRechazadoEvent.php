<?php
namespace App\Events;

use App\Models\Pac;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PacRechazadoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pac;

    public function __construct(Pac $pac)
    {
        $this->pac = $pac;
    }
}