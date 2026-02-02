# Diagramas UML de Clases - SIAFNIN

Este directorio contiene los **diagramas UML de clases** del sistema SIAFNIN en formato **Mermaid**, organizados por módulos funcionales.

## 📋 Archivos de Diagramas UML

1. **`01-autenticacion-clases.md`** - Autenticación y Autorización
   - User, Role, Permission, Notification
   - Relaciones de roles y permisos

2. **`02-catalogos-clases.md`** - Catálogos Maestros
   - Categoria, Departamento, Ubicacion, EstadoActivo
   - Marca, Modelo, Color
   - Proveedor, Fuente
   - Cargo, PersonalResponsable, Tecnico

3. **`03-activos-fijos-clases.md`** - Activos Fijos Principales
   - ActivoFijo (clase principal con todos sus atributos y métodos)
   - Vehiculo (herencia de ActivoFijo)
   - Terreno (herencia de ActivoFijo)
   - Relaciones con catálogos

4. **`04-transacciones-clases.md`** - Transacciones de Activos
   - Mantenimiento, Movimiento
   - BajaActivo, CambioEstado
   - AuditoriaActivo

5. **`05-inventarios-clases.md`** - Gestión de Inventarios
   - Inventario, InventarioDetalle

6. **`06-sistema-clases.md`** - Sistema y Reportes
   - Setting, ReporteHistorial

## 🎨 Cómo Generar Imágenes de los Diagramas UML

### Opción 1: Usar Mermaid Live Editor (Más Fácil)

1. **Abre tu navegador** y ve a: https://mermaid.live

2. **Para cada diagrama:**
   - Abre el archivo `.md` correspondiente
   - Copia el contenido del bloque de código Mermaid (todo lo que está entre \`\`\`mermaid y \`\`\`)
   - Pega en el editor de Mermaid Live
   - El diagrama se generará automáticamente

3. **Exporta la imagen:**
   - Haz clic en **"Actions"** → **"PNG"** o **"SVG"**
   - Descarga la imagen
   - Nombra según el módulo (ej: `01-autenticacion-clases.png`)

### Opción 2: Usar Visual Studio Code

1. **Instala la extensión** "Markdown Preview Mermaid Support"
2. Abre cualquier archivo `.md` con diagramas Mermaid
3. Presiona `Ctrl+Shift+V` para ver la vista previa
4. Haz clic derecho en el diagrama → **"Copy Image"** o captura de pantalla

### Opción 3: Usar GitHub/GitLab

1. Sube los archivos `.md` a tu repositorio
2. GitHub y GitLab renderizan automáticamente los diagramas Mermaid
3. Captura de pantalla del diagrama renderizado

### Opción 4: Usar draw.io (Para edición adicional)

1. Ve a https://app.diagrams.net
2. Importa el código Mermaid
3. Edita y personaliza según necesites
4. Exporta como PNG, PDF o SVG

## 📊 Características de los Diagramas UML

### Notación Utilizada

- **`+`** = Atributo/método público
- **`-`** = Atributo/método privado
- **`#`** = Atributo/método protegido
- **`<<stereotype>>`** = Estereotipos (catalog, auth, entity)

### Tipos de Relaciones

- **`--o`** = Agregación (tiene muchos)
- **`--|>`** = Herencia/Generalización
- **`--*`** = Composición
- **`--`** = Asociación simple

### Multiplicidad

- **`1`** = Uno
- **`*`** = Muchos
- **`0..1`** = Cero o uno
- **`1..*`** = Uno o más

## 📄 Estructura Recomendada para Documentación

```
DOCUMENTACIÓN TÉCNICA - SIAFNIN
Diagramas UML de Clases

1. Introducción
   - Descripción del modelo de clases
   - Convenciones utilizadas

2. Módulos del Sistema

   2.1 Autenticación y Autorización
       - [Insertar imagen 01-autenticacion-clases.png]
       - Descripción de clases principales
       - Patrones de diseño utilizados
   
   2.2 Catálogos Maestros
       - [Insertar imagen 02-catalogos-clases.png]
       - Descripción de clases de catálogo
       - Relaciones entre catálogos
   
   2.3 Activos Fijos Principales
       - [Insertar imagen 03-activos-fijos-clases.png]
       - Clase ActivoFijo (núcleo del sistema)
       - Herencia: Vehiculo y Terreno
       - Métodos de cálculo de depreciación
   
   2.4 Transacciones de Activos
       - [Insertar imagen 04-transacciones-clases.png]
       - Gestión de mantenimientos
       - Sistema de movimientos
       - Auditoría completa
   
   2.5 Gestión de Inventarios
       - [Insertar imagen 05-inventarios-clases.png]
       - Proceso de inventario físico
       - Verificación de activos
   
   2.6 Sistema y Reportes
       - [Insertar imagen 06-sistema-clases.png]
       - Configuración del sistema
       - Historial de reportes

3. Patrones de Diseño Implementados
   - Observer Pattern (AuditoriaObserver)
   - Repository Pattern (Eloquent ORM)
   - Factory Pattern (HasFactory trait)

4. Métodos Importantes
   - Cálculos de depreciación
   - Validaciones de negocio
   - Relaciones Eloquent
```

## 🔍 Detalles Técnicos

### Clase ActivoFijo (Núcleo del Sistema)

La clase `ActivoFijo` es la entidad central del sistema e incluye:

**Atributos Principales:**
- Información básica (nombre, código inventario)
- Referencias a catálogos (categoría, departamento, ubicación, etc.)
- Datos financieros (valor adquisición, depreciación)
- Auditoría (usuario creación/modificación)

**Métodos de Cálculo:**
- `getDepreciacionAnualAttribute()` - Depreciación por año
- `getDepreciacionMensualAttribute()` - Depreciación por mes
- `getMesesDepreciadosAttribute()` - Meses transcurridos
- `getDepreciacionAcumuladaCalculadaAttribute()` - Depreciación total
- `getValorNetoCalculadoAttribute()` - Valor actual del activo

**Relaciones:**
- BelongsTo: 12 relaciones con catálogos
- HasOne: Vehiculo, Terreno (herencia)
- HasMany: Bajas, Cambios de Estado, Mantenimientos, Movimientos, Auditorías

### Patrón de Herencia

```
ActivoFijo (Clase Base)
    ├── Vehiculo (Extensión para vehículos)
    └── Terreno (Extensión para terrenos)
```

Este patrón permite:
- Reutilización de código
- Especialización de atributos
- Polimorfismo en consultas

## 💡 Consejos para Documentación

1. **Combina diagramas:** Usa tanto diagramas de base de datos (DBML) como diagramas de clases (UML)
2. **Explica las relaciones:** Documenta por qué existen ciertas relaciones
3. **Destaca métodos importantes:** Especialmente los cálculos de depreciación
4. **Incluye ejemplos:** Muestra cómo se usan las clases en el código

## 🔗 Enlaces Útiles

- **Mermaid Live Editor:** https://mermaid.live
- **Documentación Mermaid:** https://mermaid.js.org/syntax/classDiagram.html
- **Laravel Eloquent:** https://laravel.com/docs/eloquent
- **Repositorio del proyecto:** https://github.com/manygutierrez0288-droid/ACTIVOFIJONINDIRI

## 📝 Diferencias entre Diagramas

### Diagrama de Base de Datos (DBML) vs Diagrama de Clases (UML)

| Aspecto | DBML | UML |
|---------|------|-----|
| **Enfoque** | Estructura de datos | Comportamiento y lógica |
| **Muestra** | Tablas, columnas, FK | Clases, métodos, atributos |
| **Relaciones** | Foreign keys | Asociaciones, herencia |
| **Uso** | Diseño de BD | Diseño de software |
| **Incluye** | Tipos de datos SQL | Métodos y lógica de negocio |

**Recomendación:** Incluye AMBOS tipos de diagramas en tu documentación para una visión completa del sistema.

---

**Última actualización:** 2026-02-01
**Sistema:** SIAFNIN - Sistema Integral de Administración de Activos Fijos de Nicaragua
