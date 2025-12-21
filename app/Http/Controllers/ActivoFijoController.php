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
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivoFijoController extends Controller
{
    protected $service;
    protected $categoriaService;
    protected $departamentoService;
    protected $ubicacionService;

    public function __construct(
        ActivoFijoService $service,
        CategoriaService $categoriaService,
        DepartamentoService $departamentoService,
        UbicacionService $ubicacionService
    ) {
        $this->service = $service;
        $this->categoriaService = $categoriaService;
        $this->departamentoService = $departamentoService;
        $this->ubicacionService = $ubicacionService;
    }

    public function index(Request $request)
    {
        $activos = $this->service->getAll();

        // Basic filtering via query strings (for demonstration)
        // In a real app, this would be more robust filter logic in the Repository

        return Inertia::render('Activos/Index', [
            'activos' => ActivoFijoResource::collection($activos),
            'filters' => $request->only(['search', 'categoria', 'departamento']),
            'categorias' => CategoriaResource::collection($this->categoriaService->getAll()),
            'departamentos' => DepartamentoResource::collection($this->departamentoService->getAll()),
        ]);
    }

    public function create()
    {
        return Inertia::render('Activos/Create', [
            'categorias' => CategoriaResource::collection($this->categoriaService->getAll()),
            'departamentos' => DepartamentoResource::collection($this->departamentoService->getAll()),
            'ubicaciones' => UbicacionResource::collection($this->ubicacionService->getAll()),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'ubicacion_id' => 'nullable|exists:ubicacions,id',
        ]);

        $this->service->create($request->all());
        return redirect()->route('activos.index')->with('success', 'Activo creado.');
    }

    public function edit(string $id)
    {
        return Inertia::render('Activos/Edit', [
            'activo' => new ActivoFijoResource($this->service->getById($id)),
            'categorias' => CategoriaResource::collection($this->categoriaService->getAll()),
            'departamentos' => DepartamentoResource::collection($this->departamentoService->getAll()),
            'ubicaciones' => UbicacionResource::collection($this->ubicacionService->getAll()),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'ubicacion_id' => 'nullable|exists:ubicacions,id',
        ]);

        $this->service->update($id, $request->all());
        return redirect()->route('activos.index')->with('success', 'Activo actualizado.');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('activos.index')->with('success', 'Activo eliminado.');
    }
}
