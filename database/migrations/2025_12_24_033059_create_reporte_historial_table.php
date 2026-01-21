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
        Schema::create('reporte_historial', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_reporte');
            $table->string('codigo_generado')->unique(); // e.g. REP-AF-2025-001
            $table->foreignId('user_id')->constrained('users');
            $table->integer('anio');
            $table->integer('secuencial'); // 1, 2, 3...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_historial');
    }
};
