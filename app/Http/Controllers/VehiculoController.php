<?php

namespace App\Http\Controllers;

use App\Services\VehiculoService;
use App\Http\Resources\VehiculoResource;
use App\Models\Categoria;
use App\Models\Departamento;
use App\Models\Ubicacion;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Color;
use App\Models\Fuente;
use App\Models\Proveedor;
use App\Models\PersonalResponsable;
use App\Models\EstadoActivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VehiculoController extends Controller
{
    protected VehiculoService $service;

    public function __construct(VehiculoService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $vehiculos = $this->service->getAll();

        return Inertia::render('Vehiculos/Index', [
            'vehiculos' => VehiculoResource::collection($vehiculos),
            'filters' => $request->only(['search', 'edit_id']),
            'categorias' => Categoria::all(),
            'departamentos' => Departamento::all(),
            'ubicaciones' => Ubicacion::all(),
            'marcas' => Marca::all(),
            'modelos' => Modelo::all(),
            'colores' => Color::all(),
            'fuentes' => Fuente::all(),
            'proveedores' => Proveedor::all(),
            'responsables' => PersonalResponsable::all(),
            'estados' => EstadoActivo::all(),
        ]);
    }

    public function create()
    {
        return redirect()->route('vehiculos.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'placa' => 'required|string|max:20|unique:vehiculos,placa',
            'numero_circulacion' => 'nullable|string|max:50',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'kilometraje' => 'nullable|numeric|min:0',
            'tipo_combustible' => 'nullable|string|max:50',
            'capacidad_pasajeros' => 'nullable|integer|min:1',
            'categoria_id' => 'required|exists:categorias,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'ubicacion_id' => 'required|exists:ubicacions,id',
            'marca_id' => 'required|exists:marcas,id',
            'modelo_id' => 'required|exists:modelos,id',
            'color_id' => 'nullable|exists:colors,id',
            'fuente_id' => 'nullable|exists:fuentes,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'responsable_id' => 'nullable|exists:personal_responsables,id',
            'estado_id' => 'required|exists:estado_activos,id',
            'fecha_adquisicion' => 'required|date',
            'valor_adquisicion' => 'required|numeric|min:0',
            'vida_util_anios' => 'required|integer|min:1',
            'valor_residual' => 'nullable|numeric|min:0',
            'codigo_inventario' => 'nullable|string|max:50',
            'numero_serie' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|max:5120',
        ]);

        $data = $validated;
        if ($request->hasFile('imagen')) {
            $data['imagen_url'] = $request->file('imagen')->store('activos', 'public');
        }

        $this->service->create($data);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo registrado exitosamente');
    }

    public function show(string $id)
    {
        $vehiculo = $this->service->getById($id);

        return Inertia::render('Vehiculos/Show', [
            'vehiculo' => (new VehiculoResource($vehiculo))->resolve()
        ]);
    }

    public function edit(string $id)
    {
        return redirect()->route('vehiculos.index', ['edit_id' => $id]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'placa' => 'required|string|max:20|unique:vehiculos,placa,' . $id,
            'numero_circulacion' => 'nullable|string|max:50',
            'anio' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'kilometraje' => 'nullable|numeric|min:0',
            'tipo_combustible' => 'nullable|string|max:50',
            'capacidad_pasajeros' => 'nullable|integer|min:1',
            'categoria_id' => 'required|exists:categorias,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'ubicacion_id' => 'required|exists:ubicacions,id',
            'marca_id' => 'required|exists:marcas,id',
            'modelo_id' => 'required|exists:modelos,id',
            'color_id' => 'nullable|exists:colors,id',
            'fuente_id' => 'nullable|exists:fuentes,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'responsable_id' => 'nullable|exists:personal_responsables,id',
            'estado_id' => 'required|exists:estado_activos,id',
            'fecha_adquisicion' => 'required|date',
            'valor_adquisicion' => 'required|numeric|min:0',
            'vida_util_anios' => 'required|integer|min:1',
            'valor_residual' => 'nullable|numeric|min:0',
            'codigo_inventario' => 'nullable|string|max:50',
            'numero_serie' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('imagen')) {
            $vehiculo = $this->service->getById($id);
            if ($vehiculo->activoFijo->imagen_url) {
                Storage::disk('public')->delete($vehiculo->activoFijo->imagen_url);
            }
            $data['imagen_url'] = $request->file('imagen')->store('activos', 'public');
        }

        $this->service->update($id, $data);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehículo eliminado exitosamente');
    }

    public function print(string $id)
    {
        $vehiculo = $this->service->getById($id);

        return Inertia::render('Vehiculos/Ficha', [
            'vehiculo' => (new VehiculoResource($vehiculo))->resolve()
        ]);
    }
}
