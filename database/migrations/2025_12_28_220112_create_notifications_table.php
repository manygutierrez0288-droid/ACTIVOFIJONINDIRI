<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // Tipo de notificación
            $table->string('title'); // Título breve
            $table->text('message'); // Mensaje descriptivo
            $table->json('data')->nullable(); // Datos adicionales (link, id del activo, etc.)
            $table->timestamp('read_at')->nullable(); // Fecha de lectura
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index('user_id');
            $table->index('read_at');
            $table->index(['user_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
