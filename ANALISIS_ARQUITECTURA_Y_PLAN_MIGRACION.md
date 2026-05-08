# Análisis de Arquitectura y Plan de Migración a SOLID/Clean Code

Este documento detalla el estado actual del proyecto **Activos-NFC** (Backend PHP) y propone una hoja de ruta para migrarlo a una arquitectura limpia (Clean Architecture) basada en principios SOLID.

## 1. Análisis del Estado Actual

### Hallazgos Principales
*   **Acoplamiento Fuerte (High Coupling):** Los archivos actuales (ej. `producto/listaproducto.php`) mezclan lógica de acceso a datos (SQL), lógica de negocio, manejo de sesiones y generación de interfaz (HTML) en un solo archivo.
*   **Lógica de Negocio Dispersa:** No existe una capa centralizada donde residan las reglas de negocio. Si una regla cambia (ej. cómo se calcula la depreciación), debe cambiarse en múltiples archivos.
*   **Dificultad de Pruebas:** Es casi imposible realizar pruebas unitarias sobre el código actual sin depender de una base de datos activa y un servidor web.
*   **API en Transición:** Se observa un inicio de estructuración en la carpeta `api/`, con namespaces y una clase de respuesta estándar, lo cual es un excelente punto de partida.

---

## 2. Propuesta de Arquitectura Limpia (Clean Architecture)

Proponemos una estructura de capas que desacople la lógica de negocio de los detalles técnicos (Base de Datos, Frameworks, UI).

### Estructura de Directorios Sugerida para `api/`
```text
api/
├── src/
│   ├── Domain/           # Capa 1: Entidades e Interfaces de Repositorios (Reglas de Negocio Puras)
│   │   ├── Entities/     # Ej: Activo.php, Usuario.php
│   │   └── Repository/   # Interfaces: ActivoRepositoryInterface.php
│   ├── Application/      # Capa 2: Casos de Uso (Orquestación de la lógica)
│   │   ├── Services/     # Ej: ObtenerDetalleActivoService.php
│   │   └── DTOs/         # Objetos de transferencia de datos para entrada/salida
│   └── Infrastructure/   # Capa 3: Implementaciones Técnicas
│       ├── Persistence/  # Implementaciones de Repositorios (ej. SqlServerActivoRepository.php)
│       ├── Controllers/  # Manejadores de peticiones HTTP
│       └── External/     # Integraciones con APIs externas, Logs, etc.
├── nucleo/               # Utilidades globales (Respuesta, Autenticacion)
└── v1/                   # Entry points (solo instancian y ejecutan el controlador)
```

---

## 3. Aplicación de Principios SOLID

| Principio | Aplicación en la Migración |
| :--- | :--- |
| **SRP (Responsabilidad Única)** | Los archivos de `v1/` ya no harán consultas SQL. Solo delegarán al Controlador, el cual delegará al Servicio, y este al Repositorio. |
| **OCP (Abierto/Cerrado)** | Los servicios dependerán de interfaces de repositorio. Podremos cambiar la base de datos (ej. SQL Server a MySQL) sin tocar una sola línea de lógica de negocio. |
| **LSP (Sustitución de Liskov)** | Cualquier implementación de un Repositorio debe cumplir estrictamente con el contrato de la interfaz. |
| **ISP (Segregación de Interfaces)** | Crearemos interfaces específicas para cada entidad en lugar de una interfaz gigante. |
| **DIP (Inversión de Dependencias)** | Las capas de alto nivel (Application/Domain) no dependerán de las de bajo nivel (Infrastructure). Se usará Inyección de Dependencias. |

---

## 4. Hoja de Ruta de Migración (Roadmap)

### Fase 1: Cimientos (Corto Plazo)
1.  **Implementar Autoloading (PSR-4):** Configurar `composer.json` (o un autoloader manual) para manejar las clases en `api/src/`.
2.  **Crear Entidades del Dominio:** Mapear las tablas de la BD a clases PHP puras (POPO - Plain Old PHP Objects).
3.  **Definir Interfaces de Repositorio:** Definir qué acciones se pueden hacer con cada entidad (ej. `buscarPorId`, `listarTodos`).

### Fase 2: Refactorización de la API (Mediano Plazo)
1.  **Migrar `obtener.php` a Controlador/Servicio:** 
    *   Mover la lógica SQL a `Infrastructure/Persistence/SqlServerActivoRepository.php`.
    *   Crear un caso de uso `Application/Services/GetActivoById.php`.
2.  **Estandarizar DTOs:** Asegurar que la API siempre devuelva objetos estructurados y no "arrays de BD" crudos.

### Fase 3: Desacoplamiento de la Web Legacy (Largo Plazo)
1.  **Migrar `producto/listaproducto.php`:** Reemplazar las llamadas directas a `sqlsrv_query` por el uso de los nuevos Servicios de la API.
2.  **Separar UI de Lógica:** Convertir los archivos legacy en simples "vistas" que consumen datos de los Servicios.

---

## 5. Ejemplo de Transformación (Caso: Activos)

**Antes (obtener.php):**
```php
$sql = "SELECT * FROM tblProducto WHERE idProducto = ?";
$consulta = sqlsrv_query($con, $sql, [$id]);
$activo = sqlsrv_fetch_array($consulta);
```

**Después (Con Arquitectura Limpia):**
```php
// En el Controlador
$service = new GetActivoByIdService(new SqlServerActivoRepository($con));
$activoDto = $service->execute($id);
Respuesta::json($activoDto);
```

---
> [!NOTE]
> Este documento ha sido generado para guiar el proceso de refactorización hacia estándares modernos de desarrollo.
