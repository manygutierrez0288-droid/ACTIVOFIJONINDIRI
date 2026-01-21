<?php

namespace App\Observers;

use App\Models\Movimiento;
use App\Services\NotificationService;

class MovimientoObserver
{
    /**
     * Handle the Movimiento "created" event.
     */
    public function created(Movimiento $movimiento): void
    {
        NotificationService::movimientoCreado($movimiento);
    }
}
