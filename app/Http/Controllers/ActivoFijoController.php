<?php

namespace App\Http\Controllers;

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

        // Basic filtering via query strings (for demonstration)
        // In a real app, this would be more robust filter logic in the Repository

        return Inertia::render('Activos/Index', [
            'activos' => ActivoFijoResource::collection($activos),
            'filters' => $request->only(['search', 'categoria', 'departamento']),
            'categorias' => $this->categoriaService->getAll(),
            'departamentos' => $this->departamentoService->getAll(),
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
        ]);

        $this->service->create($request->all());
        return redirect()->route('activos.index')->with('success', 'Activo creado correctamente.');
    }

    public function edit(string $id)
    {
        return Inertia::render('Activos/Edit', [
            'activo' => $this->service->getById($id),
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

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string',
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
        ]);

        $this->service->update($id, $request->all());
        return redirect()->route('activos.index')->with('success', 'Activo actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('activos.index')->with('success', 'Activo eliminado.');
    }
}
