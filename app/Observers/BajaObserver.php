<?php

namespace App\Observers;

use App\Models\BajaActivo;
use App\Services\NotificationService;

class BajaObserver
{
    /**
     * Handle the BajaActivo "created" event.
     */
    public function created(BajaActivo $baja): void
    {
        NotificationService::bajaCreada($baja);
    }
}
