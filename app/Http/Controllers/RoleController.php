<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        $roles = $this->roleService->getAll();
        return Inertia::render('Roles/Index', [
            'roles' => RoleResource::collection($roles),
        ]);
    }

    public function create()
    {
        return Inertia::render('Roles/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        $this->roleService->create($request->all());

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function edit(string $id)
    {
        $role = $this->roleService->getById($id);
        return Inertia::render('Roles/Edit', [
            'role' => new RoleResource($role),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $this->roleService->update($id, $request->all());

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $this->roleService->delete($id);
        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
