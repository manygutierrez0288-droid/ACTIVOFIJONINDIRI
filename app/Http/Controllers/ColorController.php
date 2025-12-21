<?php
namespace App\Http\Controllers;
use App\Services\ColorService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ColorController extends Controller
{
    protected $service;
    public function __construct(ColorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Colores/Index', ['colores' => \App\Http\Resources\ColorResource::collection($this->service->getAll())]);
    }
    public function create()
    {
        return Inertia::render('Catalogos/Colores/Create');
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|unique:colors']);
        $this->service->create($request->all());
        return redirect()->route('colores.index');
    }
    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Colores/Edit', ['color' => $this->service->getById($id)]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('colores.index');
    }
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('colores.index');
    }
}
