<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ActivoFijoController;
use App\Http\Controllers\FuenteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\PersonalResponsableController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\EstadoActivoController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\TecnicoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User & Roles
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    // Catálogos Básicos
    Route::resource('departamentos', DepartamentoController::class);
    Route::resource('ubicaciones', UbicacionController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('fuentes', FuenteController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('cargos', CargoController::class);
    Route::resource('responsables', PersonalResponsableController::class);

    // Catálogos de Activos
    Route::resource('marcas', MarcaController::class);
    Route::resource('modelos', ModeloController::class);
    Route::get('modelos/marca/{marca}', [ModeloController::class, 'getByMarca'])->name('modelos.byMarca');
    Route::resource('estados', EstadoActivoController::class);
    Route::resource('colores', ColorController::class);
    Route::resource('tecnicos', TecnicoController::class);

    // Activos Fijos
    Route::resource('activos', ActivoFijoController::class);
});

require __DIR__ . '/auth.php';
