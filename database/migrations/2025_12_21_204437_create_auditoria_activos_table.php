<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auditoria_activos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_fijo_id')->constrained('activo_fijos')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
            $table->string('accion'); // created, updated, deleted
            $table->text('valores_anteriores')->nullable();
            $table->text('valores_nuevos')->nullable();
            $table->dateTime('fecha_hora');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_activos');
    }
};
