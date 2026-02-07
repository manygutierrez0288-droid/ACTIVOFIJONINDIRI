# Diagramas de Secuencia del Sistema - SIAFNIN

Este documento contiene los flujos lógicos detallados de los procesos críticos del sistema, representados mediante diagramas de secuencia de **Mermaid**. Estos diagramas son ideales para visualizar la interacción entre el usuario, el frontend, controladores, servicios y la base de datos.

---

## 1. Módulo de Seguridad: Autenticación (Login)

Este diagrama muestra el flujo desde que el usuario ingresa sus credenciales hasta que el sistema regenera la sesión.

```mermaid
sequenceDiagram
    actor User as Usuario / Personal
    participant View as Frontend (Vue - Login)
    participant Ctrl as AuthenticatedSessionController
    participant Auth as Laravel Auth / Guard
    participant DB as Database (users table)

    User->>View: Ingresa correo y contraseña
    View->>Ctrl: POST /login (Request)
    Ctrl->>Auth: authenticate(credentials)
    Auth->>DB: SELECT user WHERE email = ?
    DB-->>Auth: User Data (Encrypted Password)
    Auth->>Auth: Verify Hash / Match
    
    alt Credenciales Válidas
        Auth-->>Ctrl: Success
        Ctrl->>View: Regenerate Session & Redirect (Dashboard)
        View-->>User: Muestra Panel de Control
    else Credenciales Inválidas
        Auth-->>Ctrl: Failure Exception
        Ctrl-->>View: 422 Unprocessable Entity (Errors)
        View-->>User: Muestra error "Credenciales no coinciden"
    end
```

---

## 2. Gestión de Activos: Alta de Activo (Registro)

Proceso central de registro de bienes en el sistema, incluyendo la persistencia en base de datos y el registro automático en la tabla de auditoría.

```mermaid
sequenceDiagram
    actor User as Encargado de Activos
    participant View as Frontend (Vue - Assets/Create)
    participant Ctrl as ActivoFijoController
    participant Svc as ActivoFijoService
    participant DB as Database (activo_fijos)
    participant Audit as Database (auditoria_activos)

    User->>View: Rellena formulario (Nombre, Serie, Valor, etc.)
    View->>Ctrl: POST /activos (Multipart Form Data)
    Ctrl->>Ctrl: Validar Requerimientos (validate)
    
    opt Con Imagen
        Ctrl->>Ctrl: Guardar imagen en Storage (public/activos)
    end

    Ctrl->>Svc: create(data)
    Svc->>DB: INSERT INTO activo_fijos
    DB-->>Svc: Success (ID generado)
    
    Svc->>Audit: Registrar Acción "CREATED" (AuditObserver)
    Audit-->>Svc: Confirmación Log
    
    Svc-->>Ctrl: Regresa objeto ActivoFijo
    Ctrl-->>View: 302 Redirect (Success Message)
    View-->>User: Notificación "Activo creado correctamente"
```

---

## 3. Operaciones: Traslado de Activo (Movimiento)

Este flujo es complejo ya que involucra dos estados: la creación de una solicitud pendiente y su posterior autorización por un superior.

### 3.1. Solicitud de Traslado

```mermaid
sequenceDiagram
    actor User as Encargado
    participant View as Frontend (Vue - Movimientos/Create)
    participant Ctrl as MovimientoController
    participant Model as Movimiento Model
    participant DB as Database (movimientos)

    User->>View: Selecciona destino, responsable y motivo
    View->>Ctrl: POST /movimientos
    Ctrl->>Ctrl: Validar Destino != Origen
    Ctrl->>Model: Create(status: 'pendiente')
    Model->>DB: INSERT INTO movimientos
    DB-->>Ctrl: OK
    Ctrl-->>View: Redirect (Pending Notification)
    View-->>User: "Solicitud registrada, pendiente de autorización"
```

### 3.2. Autorización de Traslado

```mermaid
sequenceDiagram
    actor Admin as Administrador / Jefe
    participant View as Frontend (Vue - Movimientos/Pending)
    participant Ctrl as MovimientoController
    participant DB as Database

    Admin->>View: Clic en "Autorizar"
    View->>Ctrl: POST /movimientos/{id}/autorizar
    
    Note over Ctrl, DB: Inicio de Transacción DB
    
    Ctrl->>DB: UPDATE movimientos SET estado = 'autorizado'
    Ctrl->>DB: UPDATE activo_fijos SET ubicacion_id = ?, responsable_id = ?
    
    Note over Ctrl, DB: Fin de Transacción
    
    DB-->>Ctrl: Commit OK
    Ctrl-->>View: Success Response
    View-->>Admin: "Movimiento autorizado, activo actualizado"
```

---

## 4. Módulo de Reportes: Generación de Datos

Flujo de consulta y filtrado dinámico utilizado para visualizar inventarios o cálculos de depreciación.

```mermaid
sequenceDiagram
    actor User as Auditor / Jefe
    participant View as Frontend (Vue - Reportes/Index)
    participant Ctrl as ReporteController
    participant Svc as ReporteService
    participant DB as Database

    User->>View: Selecciona tipo de reporte y filtros (Fechas/Categoría)
    View->>Ctrl: GET /reportes/data?type=X&filters=Y
    Ctrl->>Svc: getReportData(type, filters)
    Svc->>Svc: applyFilters(Query Builder)
    Svc->>DB: SELECT FROM activos JOIN relaciones...
    DB-->>Svc: Collection (Data)
    
    Svc->>Svc: Transformar Data (formateo de moneda, fechas)
    Svc-->>Ctrl: Array Final
    Ctrl-->>View: JSON Response
    View->>View: Renderizar Tabla Dinámica + Totales
    View-->>User: Muestra resultados en pantalla
```

---

## 5. Auditoría: Seguimiento de Cambios (Logs)

Muestra cómo el sistema recupera el historial de lo que ha sucedido con un bien específico.

```mermaid
sequenceDiagram
    actor User as Auditor
    participant View as Frontend (Vue - Activos/Show)
    participant Ctrl as ActivoFijoController
    participant DB as Database (auditoria_activos)

    User->>View: Abre detalle de un Activo
    View->>Ctrl: GET /activos/{id}
    Ctrl->>DB: SELECT FROM auditoria_activos WHERE activo_fijo_id = ?
    DB-->>Ctrl: Listado de cambios (Antes/Después)
    Ctrl-->>View: Datos del activo + Historial de Auditoría
    View->>View: Comparar JSON (valores_anteriores vs nuevos)
    View-->>User: Muestra línea del tiempo de modificaciones
```

---

> [!TIP]
> Puedes visualizar estos diagramas copiando el código en el [Mermaid Live Editor](https://mermaid.live).
