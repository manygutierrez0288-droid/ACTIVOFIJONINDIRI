# Diagramas de Actividad del Sistema - SIAFNIN

A diferencia de los diagramas de secuencia (que muestran quién habla con quién), los **Diagramas de Actividad** funcionan como diagramas de flujo que describen la lógica de los procesos y la toma de decisiones.

---

## 1. Proceso de Autenticación (Login)

```mermaid
graph TD
    A([Inicio]) --> B[Usuario ingresa credenciales]
    B --> C{¿Datos válidos?}
    C -- No --> D[Mostrar error de credenciales]
    D --> B
    C -- Sí --> E[Regenerar Sesión]
    E --> F[Redirigir al Dashboard]
    F --> G([Fin])
```

---

## 2. Proceso de Alta de Activo

```mermaid
graph TD
    Start([Inicio]) --> Input[Ingresar datos del activo]
    Input --> Img{¿Tiene imagen?}
    Img -- Sí --> Upload[Subir a Storage]
    Img -- No --> Save[Guardar en Base de Datos]
    Upload --> Save
    Save --> Audit[Registrar en Tabla de Auditoría]
    Audit --> End([Fin - Activo Guardado])
```

---

## 3. Proceso de Traslado (Movimiento)

Este diagrama muestra el flujo de decisión desde la solicitud hasta la actualización física del activo.

```mermaid
graph TD
    Req([Solicitud de Traslado]) --> Data[Ingresar Destino y Motivo]
    Data --> Pend[Estado: PENDIENTE]
    Pend --> Review{Revisión por Jefe}
    
    Review -- Rechazar --> Reject[Estado: RECHAZADO]
    Reject --> End([Notificar al solicitante])
    
    Review -- Autorizar --> Auth[Estado: AUTORIZADO]
    Auth --> Transact[Afectar Base de Datos]
    Transact --> Update[Actualizar Ubicación del Activo]
    Update --> Print[Habilitar Acta de Traslado]
    Print --> End
```

---

## 4. Proceso de Generación de Reportes

```mermaid
graph TD
    A([Inicio]) --> B[Seleccionar Tipo de Reporte]
    B --> C[Definir Filtros: fechas, categorías, etc.]
    C --> D[Ejecutar Consulta en el Servidor]
    D --> E{¿Existen Datos?}
    E -- No --> F[Mostrar mensaje: Sin Resultados]
    F --> C
    E -- Sí --> G[Formatear datos: moneda y fechas]
    G --> H[Renderizar Tabla y Totales]
    H --> I([Fin])
```

---

## 5. Proceso de Gestión de Mantenimiento

```mermaid
graph TD
    A([Inicio]) --> B[Registrar Necesidad de Mantenimiento]
    B --> C[Asignar Técnico y Proveedor]
    C --> D[Activo pasa a estado: EN MANTENIMIENTO]
    D --> E[Realización del trabajo técnico]
    E --> F[Registrar costos y hallazgos]
    F --> G{¿Trabajo finalizado?}
    G -- No --> E
    G -- Sí --> H[Activo regresa a estado: DISPONIBLE]
    H --> I([Fin - Historial Actualizado])
```

---

## 6. Proceso de Baja de Activo

```mermaid
graph TD
    A([Inicio]) --> B[Seleccionar Activo para Baja]
    B --> C[Definir Motivo: Robo, Deterioro, Venta, etc.]
    C --> D[Subir Documento de Respaldo / Acta]
    D --> E[Confirmar Baja en el Sistema]
    E --> F[Activo cambia a estado: DADO DE BAJA]
    F --> G[Cese de depreciación automática]
    G --> H([Fin - Registro Contable Final])
```

---

## 7. Proceso de Inventario Físico (Verificación)

```mermaid
graph TD
    A([Inicio]) --> B[Crear Nueva Sesión de Inventario]
    B --> C[Cargar Listado Teórico de Activos]
    C --> D[Escaneo de QR / Verificación Física]
    D --> E{¿Activo encontrado?}
    E -- Sí --> F[Marcar como VERIFICADO]
    E -- No --> G[Marcar como FALTANTE /OBSERVACIÓN]
    F --> H{¿Faltan activos por revisar?}
    G --> H
    H -- Sí --> D
    H -- No --> I[Generar Informe de Diferencias]
    I --> J([Fin de Inventario])
```

---

## 8. Proceso de Gestión de Usuarios (RBAC)

```mermaid
graph TD
    A([Inicio]) --> B[Crear/Editar Usuario]
    B --> C[Asignar Roles: Administrador, Responsable, etc.]
    C --> D[Definir Estado: Activo / Inactivo]
    D --> E[Guardar en Base de Datos]
    E --> F[Sistema regenera permisos en tiempo real]
    F --> G([Fin - Acceso Configurado])
```

---

## 9. Proceso de Consulta Pública (Escaneo QR)

Este proceso es vital para la transparencia y auditoría externa sin necesidad de que el auditor esté logueado.

```mermaid
graph TD
    A([Escaneo de Código QR]) --> B[Captura de ID de Activo vía URL]
    B --> C[Solicitud GET al Servidor Publico]
    C --> D[Búsqueda en Base de Datos]
    D --> E{¿Activo existe?}
    E -- No --> F[Mostrar Error 404 - No Encontrado]
    E -- Sí --> G[Cargar Ficha Técnica Pública]
    G --> H[Renderizar Vista Blade Directa]
    H --> I([Fin - Visualización de Datos])
```

---

## 10. Proceso de Edición de Datos de Activo

```mermaid
graph TD
    A([Inicio]) --> B[Buscar Activo en Listado]
    B --> C[Abrir Formulario de Edición]
    C --> D[Modificar campos: Ubicación, Valor, etc.]
    D --> E[Subir nueva Imagen si aplica]
    E --> F[Guardar Cambios]
    F --> G[Registrar Acción: UPDATED en Auditoría]
    G --> H[Comparar valores anteriores vs nuevos]
    H --> I([Fin - Datos Actualizados])
```

---

> [!NOTE]
> Estos diagramas utilizan la sintaxis `graph TD` de Mermaid, que es el estándar para representar diagramas de actividad y flujos de proceso.
