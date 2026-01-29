<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\BajaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BackupController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});

// Ruta pública para escaneo de QR
Route::get('/consultar-activo/{id}', [App\Http\Controllers\PublicQRController::class, 'show'])->name('public.activo.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/global-search', [SearchController::class, 'index'])->name('global.search');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User & Roles (usuarios permission)
    Route::middleware('role:usuarios')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });

    // Catálogos Básicos (catalogos permission)
    Route::middleware('role:catalogos')->group(function () {
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
    });

    // Activos Fijos (activos permission)
    Route::middleware('role:activos')->group(function () {
        Route::resource('activos', ActivoFijoController::class);
        Route::post('/activos/{activo}', [ActivoFijoController::class, 'update'])->name('activos.update.post');
        Route::get('/activos/{activo}/historial', [App\Http\Controllers\AuditoriaController::class, 'show'])->name('activos.historial');
        Route::get('/activos/{activoFijo}/print', [ActivoFijoController::class, 'print'])->name('activos.print');
        Route::get('/activos/{activoFijo}/label', [ActivoFijoController::class, 'label'])->name('activos.label');

        // Operaciones de activos
        Route::get('/activos/{activo}/movimientos/create', [MovimientoController::class, 'create'])->name('movimientos.create');
        Route::post('/movimientos', [MovimientoController::class, 'store'])->name('movimientos.store');
        Route::get('/movimientos/pending', [MovimientoController::class, 'pending'])->name('movimientos.pending');
        Route::post('/movimientos/{movimiento}/autorizar', [MovimientoController::class, 'autorizar'])->name('movimientos.autorizar');
        Route::post('/movimientos/{movimiento}/rechazar', [MovimientoController::class, 'rechazar'])->name('movimientos.rechazar');
        Route::get('/activos/{activo}/mantenimientos/create', [MantenimientoController::class, 'create'])->name('mantenimientos.create');
        Route::post('/mantenimientos', [MantenimientoController::class, 'store'])->name('mantenimientos.store');
        Route::get('/mantenimientos/{mantenimiento}/print', [MantenimientoController::class, 'print'])->name('mantenimientos.print');
        Route::get('/activos/{activo}/bajas/create', [BajaController::class, 'create'])->name('bajas.create');
        Route::post('/bajas', [BajaController::class, 'store'])->name('bajas.store');

        // Actas y Constancias
        Route::get('/activos/{activo}/acta-asignacion', [ActivoFijoController::class, 'actaAsignacion'])->name('activos.acta-asignacion');
        Route::get('/movimientos/{movimiento}/acta-traslado', [MovimientoController::class, 'actaTraslado'])->name('movimientos.acta-traslado');
        Route::get('/bajas/{baja}/acta-baja', [BajaController::class, 'actaBaja'])->name('bajas.acta-baja');
    });

    // Vehículos (vehiculos permission)
    Route::middleware('role:vehiculos')->group(function () {
        Route::resource('vehiculos', VehiculoController::class);
        Route::post('/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])->name('vehiculos.update.post');
        Route::get('/vehiculos/{vehiculo}/print', [VehiculoController::class, 'print'])->name('vehiculos.print');
    });

    // Terrenos (terrenos permission)
    Route::middleware('role:terrenos')->group(function () {
        Route::resource('terrenos', App\Http\Controllers\TerrenoController::class);
        Route::post('/terrenos/{terreno}', [App\Http\Controllers\TerrenoController::class, 'update'])->name('terrenos.update.post');
    });

    // Inventarios Físicos (inventarios permission)
    Route::middleware('role:inventarios')->group(function () {
        Route::resource('inventarios', InventarioController::class);
        Route::post('/inventarios/{inventario}/verificar', [InventarioController::class, 'verificar'])->name('inventarios.verificar');
        Route::post('/inventarios/{inventario}/cerrar', [InventarioController::class, 'cerrar'])->name('inventarios.cerrar');
    });

    // Reportes (reportes permission)
    Route::middleware('role:reportes')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/data', [ReporteController::class, 'data'])->name('reportes.data');
        Route::get('/reportes/export', [ReporteController::class, 'export'])->name('reportes.export');
        Route::post('/reportes/generar-consecutivo', [ReporteController::class, 'generarConsecutivo'])->name('reportes.generar-consecutivo');
    });

    // Notificaciones (All Roles)
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Respaldos (Solo admin)
    Route::middleware('role:usuarios')->group(function () {
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups', [BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/download/{filename}', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
    });

    // Configuración
    // Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    // Route::post('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__ . '/auth.php';
