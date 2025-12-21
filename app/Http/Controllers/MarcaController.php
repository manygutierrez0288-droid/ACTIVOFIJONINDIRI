<?php
namespace App\Http\Controllers;
use App\Services\MarcaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarcaController extends Controller
{
    protected $service;
    public function __construct(MarcaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Marcas/Index', ['marcas' => \App\Http\Resources\MarcaResource::collection($this->service->getAll())]);
    }
    public function create()
    {
        return Inertia::render('Catalogos/Marcas/Create');
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|unique:marcas']);
        $this->service->create($request->all());
        return redirect()->route('marcas.index');
    }
    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Marcas/Edit', ['marca' => $this->service->getById($id)]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('marcas.index');
    }
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('marcas.index');
    }
}
