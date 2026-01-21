<?php

namespace App\Http\Controllers;

use App\Services\ReporteService;
use App\Services\CategoriaService;
use App\Services\DepartamentoService;
use App\Services\UbicacionService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    protected $service;
    protected $categoriaService;
    protected $departamentoService;
    protected $ubicacionService;

    public function __construct(
        ReporteService $service,
        CategoriaService $categoriaService,
        DepartamentoService $departamentoService,
        UbicacionService $ubicacionService
    ) {
        $this->service = $service;
        $this->categoriaService = $categoriaService;
        $this->departamentoService = $departamentoService;
        $this->ubicacionService = $ubicacionService;
    }

    public function index()
    {
        return Inertia::render('Reportes/Index', [
            'categorias' => $this->categoriaService->getAll(),
            'departamentos' => $this->departamentoService->getAll(),
            'ubicaciones' => $this->ubicacionService->getAll(),
        ]);
    }

    public function data(Request $request)
    {
        $type = $request->input('type', 'inventario');
        $filters = $request->all();

        $data = $this->service->getReportData($type, $filters);

        return response()->json($data);
    }

    public function export(Request $request)
    {
        $type = $request->input('report_type', 'inventario'); // report_type from frontend
        $format = $request->input('format', 'excel'); // excel, pdf, csv
        $filters = $request->all();

        // Obtener Datos y asegurar que sea una colección
        $data = collect($this->service->getReportData($type, $filters));

        if ($data->isEmpty()) {
            return back()->with('error', 'El reporte seleccionado no contiene datos con los filtros actuales.');
        }

        $filename = 'reporte_' . $type . '_' . date('Y-m-d_H-i');

        // Excel Export
        if ($format === 'excel') {
            try {
                return Excel::download(new GenericExport($data), $filename . '.xlsx');
            } catch (\Exception $e) {
                \Log::error('Error exportando Excel: ' . $e->getMessage());
                return back()->with('error', 'Error al generar el archivo Excel. Por favor intente de nuevo.');
            }
        }

        // PDF Export
        if ($format === 'pdf') {
            $headers = array_keys((array) $data->first());

            $settings = [
                'institution_name' => \App\Models\Setting::get('institution_name', 'SIAFNIN'),
                'institution_logo' => \App\Models\Setting::get('institution_logo'),
                'report_header' => \App\Models\Setting::get('report_header', 'Reporte Oficial'),
                'report_footer' => \App\Models\Setting::get('report_footer', 'Generado por SIAFNIN'),
            ];

            $pdf = Pdf::loadView('reports.pdf', [
                'data' => $data,
                'headers' => $headers,
                'title' => 'Reporte de ' . ucfirst(str_replace('_', ' ', $type)),
                'settings' => $settings
            ])->setPaper('letter', 'landscape');

            return $pdf->stream($filename . '.pdf');
        }

        // CSV Fallback
        $csvContent = $this->service->generateCsv($data);
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.csv\"");
    }

    public function generarConsecutivo(Request $request)
    {
        $type = $request->input('type', 'inventario');
        // e.g. REP-AF-2025-001

        $anio = date('Y');

        // Find last sequential number for this year
        $lastReport = \App\Models\ReporteHistorial::where('anio', $anio)->orderBy('secuencial', 'desc')->first();
        $nextSecuencial = $lastReport ? $lastReport->secuencial + 1 : 1;

        $codigo = sprintf('REP-AF-%s-%03d', $anio, $nextSecuencial);

        // Register it
        \App\Models\ReporteHistorial::create([
            'tipo_reporte' => $type,
            'codigo_generado' => $codigo,
            'user_id' => auth()->id(),
            'anio' => $anio,
            'secuencial' => $nextSecuencial
        ]);

        return response()->json(['codigo' => $codigo]);
    }
}
