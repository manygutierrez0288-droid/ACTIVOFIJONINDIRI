<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartamentoResource;
use App\Services\DepartamentoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartamentoController extends Controller
{
    protected $service;

    public function __construct(DepartamentoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Departamentos/Index', [
            'departamentos' => DepartamentoResource::collection($this->service->getAll()),
        ]);
    }

    public function create()
    {
        return Inertia::render('Catalogos/Departamentos/Create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->create($request->all());
        return redirect()->route('departamentos.index')->with('success', 'Departamento creado.');
    }

    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Departamentos/Edit', [
            'departamento' => (new DepartamentoResource($this->service->getById($id)))->resolve(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('departamentos.index')->with('success', 'Departamento actualizado.');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('departamentos.index')->with('success', 'Departamento eliminado.');
    }
}
