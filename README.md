<p align="center">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="SIAFNIN Logo">
</p>

# SIAFNIN - Sistema de Inventario de Activos Fijos

## 📋 Descripción del Sistema
**SIAFNIN** es una plataforma integral desarrollada para la gestión, control, auditoría y administración del ciclo de vida de activos fijos, vehículos y terrenos. El sistema permite a las organizaciones mantener un registro detallado de su patrimonio, controlar asignaciones, realizar mantenimientos, gestionar bajas y generar reportes financieros y operativos precisos.

El sistema está construido como una **Single Page Application (SPA)** monolítica moderna, utilizando Laravel para el backend y Vue.js con Inertia.js para el frontend, proporcionando una experiencia de usuario fluida y reactiva.

---

## 🏗️ Arquitectura y Tecnología

### Stack Tecnológico
El proyecto utiliza tecnologías modernas y robustas para garantizar escalabilidad y mantenibilidad:

*   **Backend Framework**: [Laravel 12](https://laravel.com)
    *   **Autenticación**: Laravel Sanctum / Breeze.
    *   **Manejo de Datos**: Eloquent ORM.
    *   **Reportes**: `barryvdh/laravel-dompdf` (PDF) y `maatwebsite/excel` (Excel).
    *   **Inertia.js**: Puente entre Laravel y Vue.js.
*   **Frontend**:
    *   **Framework**: [Vue.js 3](https://vuejs.org/) (Composition API).
    *   **Estilos**: [Tailwind CSS](https://tailwindcss.com/) + [Flowbite](https://flowbite.com/) (Componentes UI).
    *   **Gráficos**: ApexCharts (`vue3-apexcharts`).
    *   **Iconografía**: Lucide Vue Next.
*   **Base de Datos**: MySQL.

### Patrones de Diseño Implementados
*   **MVC Modificado**: Uso de Controladores para lógica de negocio, pero retornando respuestas Inertia en lugar de vistas Blade tradicionales.
*   **Observers**: Implementación de `AuditoriaObserver` para registrar automáticamente cambios en modelos críticos (historial de auditoría).
*   **Role-Based Access Control (RBAC)**: Middleware personalizado (`role:xxx`) para gestión granular de permisos por módulo.
*   **Repository/Service Pattern**: Lógica de negocio encapsulada en reportes y exportaciones.

---

## 📦 Módulos y Funcionalidades

El sistema cuenta con más de **30 controladores** que orquestan la lógica de negocio, organizados en los siguientes módulos principales:

### 1. Gestión de Acceso y Seguridad
*   **Usuarios y Roles**: Administración completa de usuarios y asignación de roles y permisos.
*   **Autenticación**: Login, perfil de usuario y seguridad.
*   **Rutas**: `/users`, `/roles`, `/profile`.

### 2. Catálogos Base (Configuración)
Configuración de parámetros fundamentales para la clasificación de activos:
*   **Organización**: Departamentos, Ubicaciones.
*   **Clasificación**: Categorías, Tipos.
*   **Entidades**: Proveedores, Fuentes de Financiamiento.
*   **Personal**: Cargos, Personal Responsable, Técnicos.
*   **Características**: Marcas, Modelos, Colores, Estados del Activo.

### 3. Módulo de Activos Fijos
El núcleo del sistema para bienes muebles.
*   **Registro**: Alta de activos con detalles financieros, técnicos y ubicación.
*   **Historial**: Visualización de bitácora de cambios (auditoría).
*   **Documentación**: Generación de etiquetas y fichas técnicas.
*   **Rutas**: `/activos`, `/activos/{id}/historial`.

### 4. Operaciones y Movimientos
Gestión del flujo de vida de los activos:
*   **Movimientos**: Asignación y traslado de activos entre responsables o áreas.
    *   Flujo: Creación -> Autorización -> Rechazo/Aprobación.
    *   Generación de **Actas de Asignación** y **Actas de Traslado**.
*   **Mantenimiento**: Registro de servicios técnicos y reparaciones.
*   **Bajas**: Proceso formal de desincorporación de activos (con generación de **Acta de Baja**).

### 5. Módulo de Vehículos
Gestión especializada para el parque vehicular.
*   **Ficha Vehicular**: Registro extendido (placa, chasis, motor, kilometraje).
*   **Depreciación**: Cálculo específico para vehículos.
*   **Rutas**: `/vehiculos`.

### 6. Módulo de Terrenos
Administración de bienes inmuebles y propiedades.
*   **Catastro**: Registro de propiedades, ubicación geoespacial y valor fiscal.
*   **Rutas**: `/terrenos`.

### 7. Inventarios Físicos
Módulo para conciliación de inventario ("Toma Física").
*   **Proceso**: Apertura de inventario -> Verificación física -> Cierre y conciliación.
*   **Rutas**: `/inventarios`.

### 8. Reportes y Analítica
Generación de documentos oficiales y análisis de datos.
*   **Formatos**: Exportación a PDF y Excel.
*   **Tipos de Reporte**: Inventario General, Por Custodio, Por Ubicación, Vehículos, Depreciación, Historial de Movimientos.
*   **Dashboard**: Vista general con métricas clave y gráficos (ApexCharts).

### 9. Sistema (Admin)
*   **Backups**: Gestión de copias de seguridad de la base de datos.
*   **Notificaciones**: Sistema de alertas para autorizaciones y eventos importantes.

---

## 🚀 Rutas Principales (Resumen)

| Módulo | Ruta Base | Funcionalidad |
| :--- | :--- | :--- |
| **Dashboard** | `/dashboard` | Panel principal |
| **Activos** | `/activos` | CRUD Activos Fijos |
| **Vehículos** | `/vehiculos` | CRUD Vehículos |
| **Terrenos** | `/terrenos` | CRUD Terrenos |
| **Movimientos** | `/movimientos` | Gestión de Traslados |
| **Bajas** | `/bajas` | Gestión de Bajas |
| **Inventario** | `/inventarios` | Levantamiento Físico |
| **Reportes** | `/reportes` | Centro de Reportes |
| **Usuarios** | `/users` | Gestión de Usuarios |

---

## 🛠️ Instalación y Despliegue

### Requisitos Previos
*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   MySQL

### Pasos de Instalación

1.  **Clonar el repositorio**:
    ```bash
    git clone https://github.com/tu-usuario/siafnin.git
    cd siafnin
    ```

2.  **Instalar dependencias de Backend**:
    ```bash
    composer install
    ```

3.  **Instalar dependencias de Frontend**:
    ```bash
    npm install
    ```

4.  **Configuración de Entorno**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Configura tus credenciales de base de datos en el archivo `.env`.*

5.  **Base de Datos y Semillas**:
    ```bash
    php artisan migrate --seed
    ```
    *Esto creará las tablas y usuarios iniciales (Admin, roles base).*

6.  **Ejecutar el Servidor de Desarrollo**:
    En una terminal:
    ```bash
    php artisan serve
    ```
    En otra terminal (para compilar assets en tiempo real):
    ```bash
    npm run dev
    ```

## 📄 Licencia
Este software es privado y propietario. Todos los derechos reservados.
