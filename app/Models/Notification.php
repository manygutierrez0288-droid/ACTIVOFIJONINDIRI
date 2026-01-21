<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Obtener el usuario dueño de la notificación.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para obtener solo las no leídas.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
