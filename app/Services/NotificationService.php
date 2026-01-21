<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Crear una notificación para un usuario específico.
     */
    public static function notify($userId, $type, $title, $message, $data = [])
    {
        try {
            return Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error("Error creando notificación: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Notificar a todos los administradores.
     */
    public static function notifyAdmins($type, $title, $message, $data = [])
    {
        // Obtener usuarios con rol de admin (basado en el nombre detectado o 'admin')
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'like', '%admin%')
                ->orWhere('name', 'allan calero'); // Basado en roles detectados
        })->get();

        // Fallback: Si no hay admins por rol, notificar al usuario Administrador (ID 2)
        if ($admins->isEmpty()) {
            $admins = User::where('name', 'Administrador')
                ->orWhere('id', 2)
                ->get();
        }

        foreach ($admins as $admin) {
            self::notify($admin->id, $type, $title, $message, $data);
        }
    }

    /**
     * Notificar creación de nuevo activo.
     */
    public static function activoCreado($activo)
    {
        self::notifyAdmins(
            'activo_created',
            '📦 Nuevo activo registrado',
            "Se ha registrado el activo: {$activo->nombre} (Código: {$activo->codigo})",
            ['activo_id' => $activo->id]
        );
    }

    /**
     * Notificar asignación de activo.
     */
    public static function activoAsignado($activo, $responsableId)
    {
        self::notifyAdmins(
            'activo_assigned',
            '👤 Activo asignado',
            "El activo {$activo->nombre} ha sido asignado a un nuevo responsable.",
            ['activo_id' => $activo->id]
        );
    }

    /**
     * Notificar registro de mantenimiento.
     */
    public static function mantenimientoCreado($mantenimiento)
    {
        $mantenimiento->load('activoFijo');
        self::notifyAdmins(
            'mantenimiento_created',
            '🛠️ Mantenimiento registrado',
            "Se ha registrado un mantenimiento para: {$mantenimiento->activoFijo->nombre}",
            ['activo_id' => $mantenimiento->activo_fijo_id, 'mantenimiento_id' => $mantenimiento->id]
        );
    }

    /**
     * Notificar baja de activo.
     */
    public static function bajaCreada($baja)
    {
        $baja->load('activoFijo');
        self::notifyAdmins(
            'baja_created',
            '🚫 Activo dado de baja',
            "El activo {$baja->activoFijo->nombre} ha sido dado de baja.",
            ['activo_id' => $baja->activo_fijo_id, 'baja_id' => $baja->id]
        );
    }

    /**
     * Notificar movimiento de activo.
     */
    public static function movimientoCreado($movimiento)
    {
        $movimiento->load(['activoFijo', 'ubicacionDestino']);
        self::notifyAdmins(
            'movimiento_created',
            '🔄 Movimiento de activo',
            "El activo {$movimiento->activoFijo->nombre} fue trasladado a {$movimiento->ubicacionDestino->nombre}.",
            ['activo_id' => $movimiento->activo_fijo_id, 'movimiento_id' => $movimiento->id]
        );
    }
}
