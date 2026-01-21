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
        Schema::table('auditoria_activos', function (Blueprint $table) {
            $table->dropForeign(['activo_fijo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auditoria_activos', function (Blueprint $table) {
            $table->foreign('activo_fijo_id')
                ->references('id')
                ->on('activo_fijos')
                ->onDelete('cascade');
        });
    }
};
