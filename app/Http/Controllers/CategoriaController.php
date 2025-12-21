<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriaResource;
use App\Services\CategoriaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    protected $service;

    public function __construct(CategoriaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Categorias/Index', [
            'categorias' => CategoriaResource::collection($this->service->getAll()),
        ]);
    }

    public function create()
    {
        return Inertia::render('Catalogos/Categorias/Create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->create($request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría creada.');
    }

    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Categorias/Edit', [
            'categoria' => new CategoriaResource($this->service->getById($id)),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada.');
    }
}
