<?php

namespace App\Http\Controllers;

use App\Models\ActivoFijo;
use App\Http\Resources\ActivoFijoResource;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\DepartamentoResource;
use App\Http\Resources\UbicacionResource;
use App\Services\ActivoFijoService;
use App\Services\CategoriaService;
use App\Services\DepartamentoService;
use App\Services\UbicacionService;
use App\Services\MarcaService;
use App\Services\ModeloService;
use App\Services\ColorService;
use App\Services\FuenteService;
use App\Services\ProveedorService;
use App\Services\PersonalResponsableService;
use App\Services\EstadoActivoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ActivoFijoController extends Controller
{
    protected $service;
    protected $categoriaService;
    protected $departamentoService;
    protected $ubicacionService;
    protected $marcaService;
    protected $modeloService;
    protected $colorService;
    protected $fuenteService;
    protected $proveedorService;
    protected $responsableService;
    protected $estadoService;

    public function __construct(
        ActivoFijoService $service,
        CategoriaService $categoriaService,
        DepartamentoService $departamentoService,
        UbicacionService $ubicacionService,
        MarcaService $marcaService,
        ModeloService $modeloService,
        ColorService $colorService,
        FuenteService $fuenteService,
        ProveedorService $proveedorService,
        PersonalResponsableService $responsableService,
        EstadoActivoService $estadoService
    ) {
        $this->service = $service;
        $this->categoriaService = $categoriaService;
        $this->departamentoService = $departamentoService;
        $this->ubicacionService = $ubicacionService;
        $this->marcaService = $marcaService;
        $this->modeloService = $modeloService;
        $this->colorService = $colorService;
        $this->fuenteService = $fuenteService;
        $this->proveedorService = $proveedorService;
        $this->responsableService = $responsableService;
        $this->estadoService = $estadoService;
    }

    public function index(Request $request)
    {
        $activos = $this->service->getAll();

        return Inertia::render('Activos/Index', [
            'activos' => ActivoFijoResource::collection($activos),
            'filters' => $request->only(['search', 'categoria', 'departamento']),
            'categorias' => $this->categoriaService->getAll(),
            'departamentos' => $this->departamentoService->getAll(),
            'ubicaciones' => $this->ubicacionService->getAll(),
            'marcas' => $this->marcaService->getAll(),
            'modelos' => $this->modeloService->getAll(),
            'colores' => $this->colorService->getAll(),
            'fuentes' => $this->fuenteService->getAll(),
            'proveedores' => $this->proveedorService->getAll(),
            'responsables' => $this->responsableService->getAll(),
            'estados' => $this->estadoService->getAll(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Activos/Create', [
            'categorias' => $this->categoriaService->getAll(),
            'departamentos' => $this->departamentoService->getAll(),
            'ubicaciones' => $this->ubicacionService->getAll(),
            'marcas' => $this->marcaService->getAll(),
            'modelos' => $this->modeloService->getAll(),
            'colores' => $this->colorService->getAll(),
            'fuentes' => $this->fuenteService->getAll(),
            'proveedores' => $this->proveedorService->getAll(),
            'responsables' => $this->responsableService->getAll(),
            'estados' => $this->estadoService->getAll(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'numero_serie' => 'nullable|string|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'ubicacion_id' => 'required|exists:ubicacions,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'color_id' => 'nullable|exists:colors,id',
            'fuente_id' => 'nullable|exists:fuentes,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'responsable_id' => 'nullable|exists:personal_responsables,id',
            'estado_id' => 'required|exists:estado_activos,id',
            'fecha_adquisicion' => 'nullable|date',
            'valor_adquisicion' => 'nullable|numeric|min:0',
            'vida_util_anios' => 'required|integer|min:0',
            'valor_residual' => 'nullable|numeric|min:0',
            'valor_residual_automatico' => 'boolean',
            'imagen' => 'nullable|image|max:5120',
        ]);

        $data = $request->all();
        if ($request->hasFile('imagen')) {
            $data['imagen_url'] = $request->file('imagen')->store('activos', 'public');
        }

        $this->service->create($data);
        return redirect()->route('activos.index')->with('success', 'Activo creado correctamente.');
    }

    public function show(string $id)
    {
        $activo = $this->service->getById($id);

        // Get recent audit history
        $auditorias = \App\Models\AuditoriaActivo::where('activo_fijo_id', $id)
            ->with('usuario')
            ->orderBy('fecha_hora', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($auditoria) {
                return [
                    'id' => $auditoria->id,
                    'accion' => $auditoria->accion,
                    'usuario' => $auditoria->usuario ? $auditoria->usuario->name : 'Sistema',
                    'fecha_hora' => $auditoria->fecha_hora->format('d/m/Y H:i:s'),
                    'valores_anteriores' => $auditoria->valores_anteriores,
                    'valores_nuevos' => $auditoria->valores_nuevos,
                ];
            });

        // Get maintenance history
        $mantenimientos = \App\Models\Mantenimiento::where('activo_fijo_id', $id)
            ->with(['tecnico', 'proveedor', 'estado'])
            ->orderBy('fecha', 'desc')
            ->get();

        return Inertia::render('Activos/Show', [
            'activo' => (new ActivoFijoResource($activo))->resolve(),
            'auditorias' => $auditorias,
            'mantenimientos' => $mantenimientos
        ]);
    }

    public function edit(string $id)
    {
        return redirect()->route('activos.index', ['edit_id' => $id]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string',
            'numero_serie' => 'nullable|string|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'ubicacion_id' => 'required|exists:ubicacions,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'color_id' => 'nullable|exists:colors,id',
            'fuente_id' => 'nullable|exists:fuentes,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'responsable_id' => 'nullable|exists:personal_responsables,id',
            'estado_id' => 'required|exists:estado_activos,id',
            'fecha_adquisicion' => 'nullable|date',
            'valor_adquisicion' => 'nullable|numeric|min:0',
            'vida_util_anios' => 'required|integer|min:0',
            'valor_residual' => 'nullable|numeric|min:0',
            'valor_residual_automatico' => 'boolean',
            'imagen' => 'nullable|image|max:5120',
        ]);

        $data = $request->all();
        if ($request->hasFile('imagen')) {
            $activo = $this->service->getById($id);
            if ($activo->imagen_url) {
                Storage::disk('public')->delete($activo->imagen_url);
            }
            $data['imagen_url'] = $request->file('imagen')->store('activos', 'public');
        }

        $this->service->update($id, $data);
        return redirect()->route('activos.index')->with('success', 'Activo actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('activos.index')->with('success', 'Activo eliminado.');
    }

    public function print(ActivoFijo $activoFijo)
    {
        $activoFijo->load(['categoria', 'departamento', 'ubicacion', 'marca', 'modelo', 'color', 'responsable', 'estado']);
        return Inertia::render('Activos/Ficha', [
            'activo' => (new ActivoFijoResource($activoFijo))->resolve(),
        ]);
    }

    public function actaAsignacion(ActivoFijo $activo)
    {
        $activo->load(['categoria', 'departamento', 'ubicacion', 'marca', 'modelo', 'responsable.cargo']);
        return Inertia::render('Activos/ActaAsignacion', [
            'activo' => $activo,
            'fecha_emision' => now()->format('d/m/Y'),
        ]);
    }
}
