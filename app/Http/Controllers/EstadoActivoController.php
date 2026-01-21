<?php
namespace App\Http\Controllers;
use App\Services\EstadoActivoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstadoActivoController extends Controller
{
    protected $service;
    public function __construct(EstadoActivoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/EstadosActivo/Index', ['estados' => \App\Http\Resources\EstadoActivoResource::collection($this->service->getAll())]);
    }
    public function create()
    {
        return Inertia::render('Catalogos/EstadosActivo/Create');
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|unique:estado_activos']);
        $this->service->create($request->all());
        return redirect()->route('estados.index')->with('success', 'Estado creado correctamente.');
    }
    public function edit(string $id)
    {
        return Inertia::render('Catalogos/EstadosActivo/Edit', ['estado' => $this->service->getById($id)]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('estados.index')->with('success', 'Estado actualizado correctamente.');
    }
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('estados.index')->with('success', 'Estado eliminado correctamente.');
    }
}
