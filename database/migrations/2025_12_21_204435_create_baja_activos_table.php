<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('baja_activos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_fijo_id')->constrained('activo_fijos')->onDelete('cascade');
            $table->date('fecha');
            $table->text('motivo');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baja_activos');
    }
};
