<?php

namespace App\Observers;

use App\Models\ActivoFijo;
use App\Services\NotificationService;

class ActivoFijoObserver
{
    public function creating(ActivoFijo $activoFijo): void
    {
        // Tracking de usuario
        if (auth()->check()) {
            $activoFijo->user_creacion_id = auth()->id();
        }

        // Regla de Negocio: Heredar vida útil de la categoría si no se especifica
        if (is_null($activoFijo->vida_util_anios)) {
            $vidaUtil = 5; // Default fallback

            if ($activoFijo->categoria_id) {
                $categoria = \App\Models\Categoria::find($activoFijo->categoria_id);
                if ($categoria && !is_null($categoria->vida_util_anios)) {
                    $vidaUtil = $categoria->vida_util_anios;
                }
            }

            $activoFijo->vida_util_anios = $vidaUtil;
        }

        // Regla de Negocio: Valor Residual del 20% automático
        if ($activoFijo->valor_residual_automatico && $activoFijo->valor_adquisicion > 0) {
            $activoFijo->valor_residual = round($activoFijo->valor_adquisicion * 0.20, 2);
        }
    }

    /**
     * Handle the ActivoFijo "updating" event.
     */
    public function updating(ActivoFijo $activoFijo): void
    {
        if (auth()->check()) {
            $activoFijo->user_modificacion_id = auth()->id();
        }
    }

    /**
     * Handle the ActivoFijo "created" event.
     */
    public function created(ActivoFijo $activoFijo): void
    {
        NotificationService::activoCreado($activoFijo);
    }

    /**
     * Handle the ActivoFijo "updated" event.
     */
    public function updated(ActivoFijo $activoFijo): void
    {
        if ($activoFijo->isDirty('responsable_id')) {
            NotificationService::activoAsignado($activoFijo, $activoFijo->responsable_id);
        }
    }

    /**
     * Handle the ActivoFijo "deleted" event.
     */
    public function deleted(ActivoFijo $activoFijo): void
    {
        //
    }

    /**
     * Handle the ActivoFijo "restored" event.
     */
    public function restored(ActivoFijo $activoFijo): void
    {
        //
    }

    /**
     * Handle the ActivoFijo "force deleted" event.
     */
    public function forceDeleted(ActivoFijo $activoFijo): void
    {
        //
    }
}
