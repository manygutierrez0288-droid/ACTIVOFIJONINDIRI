<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\ActivoFijo;
use Carbon\Carbon;

class InventoryCodeRepairSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Repair Categories
        $categorias = Categoria::whereNull('codigo')->orWhere('codigo', '')->get();
        $startCode = Categoria::whereNotNull('codigo')->where('codigo', '!=', '')->count() + 1;

        foreach ($categorias as $index => $cat) {
            $cat->update(['codigo' => str_pad($startCode + $index, 2, '0', STR_PAD_LEFT)]);
        }

        // 2. Repair Assets
        $activos = ActivoFijo::whereNull('codigo_inventario')->orderBy('created_at')->get();

        foreach ($activos as $activo) {
            $year = Carbon::parse($activo->created_at)->format('y');
            $catCode = str_pad($activo->categoria->codigo ?? '00', 2, '0', STR_PAD_LEFT);

            // Find sequence for this specific year and category
            $lastSequence = ActivoFijo::where('categoria_id', $activo->categoria_id)
                ->whereYear('created_at', Carbon::parse($activo->created_at)->year)
                ->whereNotNull('codigo_inventario')
                ->where('id', '!=', $activo->id)
                ->get()
                ->map(fn($a) => (int) substr($a->codigo_inventario, -4))
                ->max() ?? 0;

            $newSequence = $lastSequence + 1;
            $activo->update([
                'codigo_inventario' => $year . $catCode . str_pad($newSequence, 4, '0', STR_PAD_LEFT)
            ]);
        }
    }
}
