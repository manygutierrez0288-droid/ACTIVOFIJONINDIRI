<?php

namespace App\Http\Controllers;

use App\Services\TerrenoService;
use App\Http\Resources\TerrenoResource;
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

class TerrenoController extends Controller
{
    protected TerrenoService $service;

    public function __construct(TerrenoService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $terrenos = $this->service->getAll();

        return Inertia::render('Terrenos/Index', [
            'terrenos' => TerrenoResource::collection($terrenos),
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
        return Inertia::render('Terrenos/Create', [
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Datos del activo fijo
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'ubicacion_id' => 'nullable|exists:ubicacions,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'color_id' => 'nullable|exists:colors,id',
            'fuente_id' => 'nullable|exists:fuentes,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'responsable_id' => 'nullable|exists:personal_responsables,id',
            'estado_id' => 'required|exists:estado_activos,id',
            'fecha_adquisicion' => 'required|date',
            'valor_adquisicion' => 'required|numeric|min:0',
            'vida_util_anios' => 'nullable|integer|min:0',
            'valor_residual' => 'nullable|numeric|min:0',
            'codigo_inventario' => 'nullable|string|max:50',
            'numero_serie' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|max:2048',

            // Datos específicos del terreno
            'numero_escritura' => 'required|string|max:100|unique:terrenos,numero_escritura',
            'area_metros_cuadrados' => 'required|numeric|min:0.01',
            'frente_metros' => 'nullable|numeric|min:0',
            'fondo_metros' => 'nullable|numeric|min:0',
            'lindero_norte' => 'nullable|string',
            'lindero_sur' => 'nullable|string',
            'lindero_este' => 'nullable|string',
            'lindero_oeste' => 'nullable|string',
            'coordenadas_gps' => 'nullable|string|max:255',
            'uso_suelo' => 'nullable|string|max:100',
            'zonificacion' => 'nullable|string|max:100',
            'valor_catastral' => 'nullable|numeric|min:0',
            'dominio' => 'required|string|max:100',
        ]);

        $data = $validated;
        if ($request->hasFile('imagen')) {
            $data['imagen_url'] = $request->file('imagen')->store('activos', 'public');
        }

        $this->service->create($data);

        return redirect()->route('terrenos.index')
            ->with('success', 'Terreno registrado exitosamente');
    }

    public function show(string $id)
    {
        $terreno = $this->service->getById($id);

        return Inertia::render('Terrenos/Show', [
            'terreno' => (new TerrenoResource($terreno))->resolve()
        ]);
    }

    public function edit(string $id)
    {
        $terreno = $this->service->getById($id);

        return Inertia::render('Terrenos/Edit', [
            'terreno' => (new TerrenoResource($terreno))->resolve(),
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

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            // Datos del activo fijo
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'departamento_id' => 'nullable|exists:departamentos,id',
            'ubicacion_id' => 'nullable|exists:ubicacions,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'modelo_id' => 'nullable|exists:modelos,id',
            'color_id' => 'nullable|exists:colors,id',
            'fuente_id' => 'nullable|exists:fuentes,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'responsable_id' => 'nullable|exists:personal_responsables,id',
            'estado_id' => 'required|exists:estado_activos,id',
            'fecha_adquisicion' => 'required|date',
            'valor_adquisicion' => 'required|numeric|min:0',
            'vida_util_anios' => 'nullable|integer|min:0',
            'valor_residual' => 'nullable|numeric|min:0',
            'codigo_inventario' => 'nullable|string|max:50',
            'numero_serie' => 'nullable|string|max:100',
            'imagen' => 'nullable|image|max:2048',

            // Datos específicos del terreno
            'numero_escritura' => 'required|string|max:100|unique:terrenos,numero_escritura,' . $id,
            'area_metros_cuadrados' => 'required|numeric|min:0.01',
            'frente_metros' => 'nullable|numeric|min:0',
            'fondo_metros' => 'nullable|numeric|min:0',
            'lindero_norte' => 'nullable|string',
            'lindero_sur' => 'nullable|string',
            'lindero_este' => 'nullable|string',
            'lindero_oeste' => 'nullable|string',
            'coordenadas_gps' => 'nullable|string|max:255',
            'uso_suelo' => 'nullable|string|max:100',
            'zonificacion' => 'nullable|string|max:100',
            'valor_catastral' => 'nullable|numeric|min:0',
            'dominio' => 'required|string|max:100',
        ]);

        $data = $validated;

        if ($request->hasFile('imagen')) {
            $terreno = $this->service->getById($id);
            if ($terreno->activoFijo->imagen_url) {
                Storage::disk('public')->delete($terreno->activoFijo->imagen_url);
            }
            $data['imagen_url'] = $request->file('imagen')->store('activos', 'public');
        }

        $this->service->update($id, $data);

        return redirect()->route('terrenos.index')
            ->with('success', 'Terreno actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $this->service->delete($id);

        return redirect()->route('terrenos.index')
            ->with('success', 'Terreno eliminado exitosamente');
    }
}
