<?php
namespace App\Http\Controllers;
use App\Services\ProveedorService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProveedorController extends Controller
{
    protected $service;
    public function __construct(ProveedorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Proveedores/Index', ['proveedores' => \App\Http\Resources\ProveedorResource::collection($this->service->getAll())]);
    }
    public function create()
    {
        return Inertia::render('Catalogos/Proveedores/Create');
    }
    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->create($request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }
    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Proveedores/Edit', ['proveedor' => $this->service->getById($id)]);
    }
    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado correctamente.');
    }
}
