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
        Schema::table('movimientos', function (Blueprint $table) {
            $table->enum('estado', ['pendiente', 'autorizado', 'rechazado'])->default('pendiente')->after('motivo');
            $table->foreignId('user_autorizador_id')->nullable()->after('estado')->constrained('users')->onDelete('restrict');
            $table->dateTime('fecha_autorizacion')->nullable()->after('user_autorizador_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropForeign(['user_autorizador_id']);
            $table->dropColumn(['estado', 'user_autorizador_id', 'fecha_autorizacion']);
        });
    }
};
