<?php
namespace App\Http\Controllers;
use App\Services\FuenteService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FuenteController extends Controller
{
    protected $service;
    public function __construct(FuenteService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Fuentes/Index', ['fuentes' => $this->service->getAll()]);
    }
    public function create()
    {
        return Inertia::render('Catalogos/Fuentes/Create');
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|unique:fuentes']);
        $this->service->create($request->all());
        return redirect()->route('fuentes.index');
    }
    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Fuentes/Edit', ['fuente' => $this->service->getById($id)]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('fuentes.index');
    }
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('fuentes.index');
    }
}
