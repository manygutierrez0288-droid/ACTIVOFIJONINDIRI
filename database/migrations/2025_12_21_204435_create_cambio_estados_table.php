<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cambio_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_fijo_id')->constrained('activo_fijos')->onDelete('cascade');
            $table->foreignId('estado_anterior_id')->constrained('estado_activos')->onDelete('restrict');
            $table->foreignId('estado_nuevo_id')->constrained('estado_activos')->onDelete('restrict');
            $table->dateTime('fecha')->useCurrent();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cambio_estados');
    }
};
