<?php
namespace App\Http\Controllers;
use App\Services\PersonalResponsableService;
use App\Services\CargoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonalResponsableController extends Controller
{
    protected $service;
    protected $cargoService;
    public function __construct(PersonalResponsableService $service, CargoService $cargoService)
    {
        $this->service = $service;
        $this->cargoService = $cargoService;
    }

    public function index()
    {
        return Inertia::render('Catalogos/PersonalResponsable/Index', ['responsables' => \App\Http\Resources\PersonalResponsableResource::collection($this->service->getAll())]);
    }
    public function create()
    {
        return Inertia::render('Catalogos/PersonalResponsable/Create', ['cargos' => $this->cargoService->getAll()]);
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required', 'cargo_id' => 'required|exists:cargos,id', 'numero_cedula' => 'required|unique:personal_responsables']);
        $this->service->create($request->all());
        return redirect()->route('responsables.index');
    }
    public function edit(string $id)
    {
        return Inertia::render('Catalogos/PersonalResponsable/Edit', [
            'responsable' => $this->service->getById($id),
            'cargos' => $this->cargoService->getAll()
        ]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required', 'cargo_id' => 'required|exists:cargos,id']);
        $this->service->update($id, $request->all());
        return redirect()->route('responsables.index');
    }
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('responsables.index');
    }
}
