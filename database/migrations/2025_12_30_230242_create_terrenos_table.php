<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('terrenos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_fijo_id')->unique()->constrained('activo_fijos')->onDelete('cascade');
            $table->string('numero_escritura', 100)->unique();
            $table->decimal('area_metros_cuadrados', 12, 2);
            $table->decimal('frente_metros', 10, 2)->nullable();
            $table->decimal('fondo_metros', 10, 2)->nullable();
            $table->text('lindero_norte')->nullable();
            $table->text('lindero_sur')->nullable();
            $table->text('lindero_este')->nullable();
            $table->text('lindero_oeste')->nullable();
            $table->string('coordenadas_gps')->nullable();
            $table->string('uso_suelo')->nullable();
            $table->string('zonificacion')->nullable();
            $table->decimal('valor_catastral', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terrenos');
    }
};
