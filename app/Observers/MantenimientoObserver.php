<?php

namespace App\Observers;

use App\Models\Mantenimiento;
use App\Services\NotificationService;

class MantenimientoObserver
{
    /**
     * Handle the Mantenimiento "created" event.
     */
    public function created(Mantenimiento $mantenimiento): void
    {
        NotificationService::mantenimientoCreado($mantenimiento);
    }
}
