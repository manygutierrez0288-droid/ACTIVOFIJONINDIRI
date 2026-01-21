<?php
namespace App\Http\Controllers;
use App\Services\CargoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CargoController extends Controller
{
    protected $service;
    public function __construct(CargoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Cargos/Index', ['cargos' => \App\Http\Resources\CargoResource::collection($this->service->getAll())]);
    }
    public function create()
    {
        return Inertia::render('Catalogos/Cargos/Create');
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|unique:cargos']);
        $this->service->create($request->all());
        return redirect()->route('cargos.index')->with('success', 'Cargo creado correctamente.');
    }
    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Cargos/Edit', ['cargo' => $this->service->getById($id)]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('cargos.index')->with('success', 'Cargo actualizado correctamente.');
    }
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('cargos.index')->with('success', 'Cargo eliminado correctamente.');
    }
}
