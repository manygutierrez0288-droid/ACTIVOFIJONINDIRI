<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $activos = DB::table('activo_fijos')
            ->join('categorias', 'activo_fijos.categoria_id', '=', 'categorias.id')
            ->select('activo_fijos.id', 'activo_fijos.codigo_inventario', 'categorias.codigo as cat_code')
            ->whereNotNull('activo_fijos.codigo_inventario')
            ->get();

        foreach ($activos as $activo) {
            $code = $activo->codigo_inventario;

            // Extract year (first 2 chars)
            $year = substr($code, 0, 2);

            // Extract sequence (last 4 chars)
            $seq = substr($code, -4);

            // Get correct category code
            $catCode = $activo->cat_code ?? '00';

            // Reconstruct new format: YY-CatCode-NNNN
            $newCode = $year . '-' . $catCode . '-' . $seq;

            DB::table('activo_fijos')
                ->where('id', $activo->id)
                ->update(['codigo_inventario' => $newCode]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $activos = DB::table('activo_fijos')
            ->join('categorias', 'activo_fijos.categoria_id', '=', 'categorias.id')
            ->select('activo_fijos.id', 'activo_fijos.codigo_inventario', 'categorias.codigo as cat_code')
            ->whereNotNull('activo_fijos.codigo_inventario')
            ->get();

        foreach ($activos as $activo) {
            $code = $activo->codigo_inventario;

            // Return to old format (YYCatCodeNNNN)
            // Extract year and sequence
            $year = substr($code, 0, 2);
            $seq = substr($code, -4);
            $catCode = $activo->cat_code ?? '00';

            $oldCode = $year . $catCode . $seq;

            DB::table('activo_fijos')
                ->where('id', $activo->id)
                ->update(['codigo_inventario' => $oldCode]);
        }
    }
};
