<?php
namespace App\Http\Controllers;
use App\Services\ModeloService;
use App\Services\MarcaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModeloController extends Controller
{
    protected $service;
    protected $marcaService;
    public function __construct(ModeloService $service, MarcaService $marcaService)
    {
        $this->service = $service;
        $this->marcaService = $marcaService;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Modelos/Index', [
            'modelos' => \App\Http\Resources\ModeloResource::collection($this->service->getAll()),
            'marcas' => $this->marcaService->getAll()
        ]);
    }
    public function create()
    {
        return Inertia::render('Catalogos/Modelos/Create', ['marcas' => $this->marcaService->getAll()]);
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required', 'marca_id' => 'required|exists:marcas,id']);
        $this->service->create($request->all());
        return redirect()->route('modelos.index')->with('success', 'Modelo creado correctamente.');
    }
    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Modelos/Edit', ['modelo' => $this->service->getById($id), 'marcas' => $this->marcaService->getAll()]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required', 'marca_id' => 'required|exists:marcas,id']);
        $this->service->update($id, $request->all());
        return redirect()->route('modelos.index')->with('success', 'Modelo actualizado correctamente.');
    }
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('modelos.index')->with('success', 'Modelo eliminado correctamente.');
    }
    public function getByMarca($marcaId)
    {
        return response()->json($this->service->getByMarca($marcaId));
    }
}
