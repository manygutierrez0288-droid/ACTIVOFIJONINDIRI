<?php

namespace App\Observers;

use App\Models\ActivoFijo;
use App\Models\AuditoriaActivo;
use Illuminate\Support\Facades\Auth;

class AuditoriaObserver
{
    /**
     * Handle the ActivoFijo "created" event.
     */
    public function created(ActivoFijo $activoFijo): void
    {
        $this->logAudit($activoFijo, 'created', null, $activoFijo->getAttributes());
    }

    /**
     * Handle the ActivoFijo "updated" event.
     */
    public function updated(ActivoFijo $activoFijo): void
    {
        $original = $activoFijo->getOriginal();
        $changes = $activoFijo->getChanges();

        // Only log if there are actual changes
        if (!empty($changes)) {
            $this->logAudit($activoFijo, 'updated', $original, $changes);
        }
    }

    /**
     * Handle the ActivoFijo "deleting" event.
     */
    public function deleting(ActivoFijo $activoFijo): void
    {
        $this->logAudit($activoFijo, 'deleted', $activoFijo->getAttributes(), null);
    }

    /**
     * Log the audit trail
     */
    private function logAudit(ActivoFijo $activoFijo, string $accion, ?array $valoresAnteriores, ?array $valoresNuevos): void
    {
        // Fallback para usuario_id (Prioridad: Auth > Model > Admin por defecto)
        $usuarioId = Auth::id()
            ?? $activoFijo->user_modificacion_id
            ?? $activoFijo->user_creacion_id
            ?? 1;

        AuditoriaActivo::create([
            'activo_fijo_id' => $activoFijo->id,
            'usuario_id' => $usuarioId,
            'accion' => $accion,
            'valores_anteriores' => $valoresAnteriores,
            'valores_nuevos' => $valoresNuevos,
            'fecha_hora' => now(),
        ]);
    }
}
