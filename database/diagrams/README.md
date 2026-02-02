# Diagramas de Base de Datos SIAFNIN

Este directorio contiene los diagramas de la base de datos del Sistema Integral de Administración de Activos Fijos de Nicaragua (SIAFNIN), organizados por módulos funcionales.

## 📋 Archivos de Diagramas

### Diagrama Completo
- **`schema.dbml`** - Esquema completo de la base de datos (40+ tablas)

### Diagramas Modulares (Recomendados para Documentación)

1. **`01-autenticacion-autorizacion.dbml`**
   - Usuarios y autenticación
   - Roles y permisos
   - Sesiones y notificaciones
   - **Tablas:** users, roles, permissions, usuario_rol, role_permission, sessions, password_reset_tokens, notifications

2. **`02-catalogos-maestros.dbml`**
   - Catálogos de clasificación
   - Catálogos de productos
   - Catálogos de proveedores
   - Catálogos de personal
   - **Tablas:** categorias, departamentos, ubicacions, estado_activos, marcas, modelos, colors, proveedors, fuentes, cargos, personal_responsables, tecnicos

3. **`03-activos-fijos-principales.dbml`**
   - Tabla principal de activos fijos
   - Extensiones: Vehículos y Terrenos
   - **Tablas:** activo_fijos, vehiculos, terrenos

4. **`04-transacciones-activos.dbml`**
   - Mantenimientos
   - Movimientos/traslados
   - Bajas de activos
   - Cambios de estado
   - Auditoría completa
   - **Tablas:** mantenimientos, movimientos, baja_activos, cambio_estados, auditoria_activos

5. **`05-inventarios.dbml`**
   - Procesos de inventario físico
   - Detalles de verificación
   - **Tablas:** inventarios, inventario_detalles

6. **`06-sistema-reportes.dbml`**
   - Configuración del sistema
   - Historial de reportes
   - Caché y trabajos en cola
   - **Tablas:** settings, reporte_historial, cache, jobs, failed_jobs

## 🎨 Cómo Generar Imágenes para Documentación

### Opción 1: Usando dbdiagram.io (Recomendado)

1. **Abre tu navegador** y ve a: https://dbdiagram.io/d

2. **Para cada diagrama modular:**
   - Abre el archivo `.dbml` correspondiente
   - Copia todo el contenido
   - Pega en el editor de dbdiagram.io
   - Haz clic en **"Export"** → **"Export to PNG"** o **"Export to PDF"**
   - Selecciona **"High Quality"** para mejor resolución
   - Descarga la imagen

3. **Nombra las imágenes descargadas:**
   - `01-autenticacion-autorizacion.png`
   - `02-catalogos-maestros.png`
   - `03-activos-fijos-principales.png`
   - `04-transacciones-activos.png`
   - `05-inventarios.png`
   - `06-sistema-reportes.png`

### Opción 2: Usando Captura de Pantalla

1. Abre cada diagrama en dbdiagram.io
2. Presiona **Windows + Shift + S**
3. Selecciona el área del diagrama
4. Pega en tu documento de Word/Google Docs

## 📄 Estructura Recomendada para Documentación

```
DOCUMENTACIÓN TÉCNICA - SIAFNIN
Base de Datos

1. Introducción
   - Descripción general del sistema
   - Tecnologías utilizadas (MySQL, Laravel)

2. Diagrama General
   - [Insertar imagen del diagrama completo]
   - Descripción de las áreas principales

3. Módulos del Sistema

   3.1 Autenticación y Autorización
       - [Insertar imagen 01-autenticacion-autorizacion.png]
       - Descripción de tablas y relaciones
   
   3.2 Catálogos Maestros
       - [Insertar imagen 02-catalogos-maestros.png]
       - Descripción de tablas y relaciones
   
   3.3 Activos Fijos Principales
       - [Insertar imagen 03-activos-fijos-principales.png]
       - Descripción de tablas y relaciones
   
   3.4 Transacciones de Activos
       - [Insertar imagen 04-transacciones-activos.png]
       - Descripción de tablas y relaciones
   
   3.5 Gestión de Inventarios
       - [Insertar imagen 05-inventarios.png]
       - Descripción de tablas y relaciones
   
   3.6 Sistema y Reportes
       - [Insertar imagen 06-sistema-reportes.png]
       - Descripción de tablas y relaciones

4. Diccionario de Datos
   - Descripción detallada de cada tabla
   - Tipos de datos y restricciones

5. Relaciones y Reglas de Negocio
   - Integridad referencial
   - Reglas de validación
```

## 💡 Consejos para Impresión

1. **Orientación:** Usa orientación **horizontal (landscape)** para mejor visualización
2. **Tamaño:** Exporta en **alta calidad** desde dbdiagram.io
3. **Escala:** Si el diagrama es muy grande, considera:
   - Imprimir en tamaño A3 en lugar de A4
   - Dividir en múltiples páginas
   - Usar los diagramas modulares en lugar del completo

4. **Colores:** Los diagramas se ven mejor en **color**, pero también funcionan en blanco y negro

## 🔗 Enlaces Útiles

- **dbdiagram.io:** https://dbdiagram.io
- **Documentación DBML:** https://dbml.dbdiagram.io/docs/
- **Repositorio del proyecto:** https://github.com/manygutierrez0288-droid/ACTIVOFIJONINDIRI

## 📝 Notas Adicionales

- Los diagramas modulares son **más fáciles de leer** que el diagrama completo
- Cada módulo es **independiente** y puede ser visualizado por separado
- Las tablas de referencia en los diagramas modulares están **simplificadas** para claridad
- El diagrama completo (`schema.dbml`) contiene **todas las relaciones** completas

---

**Última actualización:** 2026-02-01
**Sistema:** SIAFNIN - Sistema Integral de Administración de Activos Fijos de Nicaragua
