<?php
namespace App\Http\Controllers;
use App\Services\TecnicoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TecnicoController extends Controller
{
    protected $service;
    public function __construct(TecnicoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Tecnicos/Index', ['tecnicos' => \App\Http\Resources\TecnicoResource::collection($this->service->getAll())]);
    }
    public function create()
    {
        return Inertia::render('Catalogos/Tecnicos/Create');
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->create($request->all());
        return redirect()->route('tecnicos.index');
    }
    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Tecnicos/Edit', ['tecnico' => $this->service->getById($id)]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('tecnicos.index');
    }
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('tecnicos.index');
    }
}
