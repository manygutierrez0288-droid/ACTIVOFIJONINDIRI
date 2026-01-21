<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\RoleResource;
use App\Services\UserService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index()
    {
        $users = $this->userService->getAll();
        $roles = $this->roleService->getAll();
        return Inertia::render('Users/Index', [
            'users' => UserResource::collection($users),
            'roles' => RoleResource::collection($roles),
        ]);
    }

    public function create()
    {
        $roles = $this->roleService->getAll();
        return Inertia::render('Users/Create', [
            'roles' => RoleResource::collection($roles)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'roles' => 'array|exists:roles,id'
        ]);

        $this->userService->create($request->all());

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(string $id)
    {
        $user = $this->userService->getById($id);
        $user->load('roles');
        $roles = $this->roleService->getAll();

        return Inertia::render('Users/Edit', [
            'user' => new UserResource($user),
            'roles' => RoleResource::collection($roles)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'roles' => 'array|exists:roles,id'
        ]);

        $this->userService->update($id, $request->all());

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $this->userService->delete($id);
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
