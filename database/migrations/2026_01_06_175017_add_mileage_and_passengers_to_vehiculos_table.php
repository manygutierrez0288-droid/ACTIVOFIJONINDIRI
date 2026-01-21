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
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->decimal('kilometraje', 10, 2)->nullable()->after('anio');
            $table->integer('capacidad_pasajeros')->nullable()->after('kilometraje');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropColumn(['kilometraje', 'capacidad_pasajeros']);
        });
    }
};
