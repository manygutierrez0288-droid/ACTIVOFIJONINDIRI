<?php

namespace App\Http\Controllers;

use App\Http\Resources\UbicacionResource;
use App\Services\UbicacionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UbicacionController extends Controller
{
    protected $service;

    public function __construct(UbicacionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('Catalogos/Ubicaciones/Index', [
            'ubicaciones' => UbicacionResource::collection($this->service->getAll()),
        ]);
    }

    public function create()
    {
        return Inertia::render('Catalogos/Ubicaciones/Create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->create($request->all());
        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación creada.');
    }

    public function edit(string $id)
    {
        return Inertia::render('Catalogos/Ubicaciones/Edit', [
            'ubicacion' => new UbicacionResource($this->service->getById($id)),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['nombre' => 'required|string']);
        $this->service->update($id, $request->all());
        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación actualizada.');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación eliminada.');
    }
}
