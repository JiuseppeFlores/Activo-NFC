# Plan de Ajustes Visuales - Sistema de Gestión de Activos (Post-Migracion Tabler UI)

> **Contexto**: La migracion de AdminLTE 3 a Tabler UI 1.4.0 fue completada con exito.
> Este documento detalla los ajustes visuales identificados para elevar la calidad del sistema al nivel premium de Tabler UI.

---

## Ajuste 1: Sidebar - Color y Branding del menu lateral

**Problema**: El sidebar aparece completamente blanco, sin identidad visual propia.

### Tareas
- [x] Aplicar la clase `navbar-dark` al `<aside>` del sidebar para activar el tema oscuro nativo de Tabler.
- [x] Agregar `data-bs-theme="dark"` al `<aside>` para forzar el esquema de colores oscuro.
- [x] Verificar que los iconos y textos del menu contrasten correctamente sobre el fondo oscuro.
- [x] Ajustar el color de fondo del header superior (`navbar-dark bg-primary`) para que sea coherente con el sidebar.
- [x] Revisar que el logo/nombre del sistema en el sidebar tenga legibilidad correcta.
- [x] Comprobar que los items activos del menu (`nav-link active`) se vean correctamente sobre fondo oscuro.

### Validacion
- [x] El sidebar tiene color propio y diferencia visual clara respecto al contenido.
- [x] Los items del menu son legibles y el estado activo es visible.
- [x] No hay conflictos de contraste entre texto e iconos sobre el nuevo fondo.

---

## Ajuste 2: Navbar superior - Informacion del usuario y opciones de sesion

**Problema**: El navbar superior esta vacio (solo muestra el nombre del sistema). No hay avatar del usuario, nombre, ni acceso a opciones de perfil o cierre de sesion.

### Tareas
- [x] Agregar al `<header>` una seccion derecha (`ms-auto`) con avatar, nombre del usuario y dropdown.
- [x] El dropdown debe incluir: "Mi Perfil", "Configuracion" (solo admin), separador, "Cerrar Sesion" (color rojo, icono `ti ti-logout`).
- [x] Usar cache-busting con timestamp en el avatar del navbar.
- [x] Eliminar o simplificar el bloque de avatar/nombre del sidebar (ahora muestra el avatar real del usuario).
- [x] Verificar que el dropdown use Bootstrap 5 (`data-bs-toggle="dropdown"`).

### Validacion
- [x] El navbar muestra avatar, nombre del usuario y dropdown funcional.
- [x] Las opciones del dropdown navegan correctamente (Mi Perfil via `configUser(1)`, Configuracion via `configWeb(1)`).
- [x] El cierre de sesion desde el navbar funciona.
- [x] El avatar refleja la imagen actual del usuario (con cache-busting).

---

## Ajuste 3: Tablas - Compactacion de filas (table-sm)

**Problema**: Las tablas tienen demasiado padding vertical, generando espacio desperdiciado.

### Archivos afectados
- `producto/listaproducto.php`
- `usuario/listausuario.php`
- `asignacion/listaasignacion.php`
- `inventario/listainventario.php`
- `depreciacionTabla/listaDepreciacion.php`
- `area/listaarea.php`
- `rol/listarol.php`

### Tareas
- [x] Agregar la clase `table-sm` a todas las tablas principales.
- [x] Revisar que los badges y avatares dentro de las celdas no pierdan legibilidad.
- [x] Verificar que las celdas de "Opciones" no queden apretadas al usar `table-sm`.

### Validacion
- [x] Las filas de las tablas son compactas pero siguen siendo legibles.
- [x] Los badges y avatares se ven correctamente dentro de celdas compactas.
- [x] Los botones de accion mantienen tamano minimo usable.

---

## Ajuste 4: Tablas - Eliminar columna "Informacion" con expander duplicado

**Problema**: La primera columna "Informacion" usa un elemento `<details><summary>` que expande datos ya visibles en las demas columnas, generando duplicacion innecesaria.

### Archivos afectados
- `producto/listaproducto.php`
- `usuario/listausuario.php`
- `asignacion/listaasignacion.php`
- `inventario/listainventario.php`
- `depreciacionTabla/listaDepreciacion.php`

### Tareas
- [x] Eliminar la variable `$otro` (el bloque `<details>`) de cada archivo afectado.
- [x] Eliminar la columna `<th>Informacion</th>` del encabezado de cada tabla.
- [x] Eliminar la celda `<td>$otro</td>` de cada fila.
- [x] En `usuario/listausuario.php`: reemplazar por celda con avatar del usuario (`avatar avatar-sm rounded-circle`).
- [x] En `listaproducto.php`: reemplazar por celda con avatar del producto (`avatar avatar-sm rounded`).
- [x] En `asignacion/listaasignacion.php`: eliminar columna directamente (sin reemplazo).
- [x] En `depreciacionTabla/listaDepreciacion.php`: eliminar `$otro` y columna; conservar el sub-panel expandible de `bienDetalle` si aporta valor exclusivo no repetido.

### Validacion
- [x] Ninguna tabla tiene la columna "Informacion" con el `<details>` duplicador.
- [x] Las tablas de usuarios y productos muestran avatar en lugar de la columna eliminada.
- [x] En `area/listaarea.php`: eliminar expander y agregar columna `#` con idArea.
- [x] En `rol/listarol.php`: eliminar expander y agregar columna `#` con idRol.
- [x] En modulos sin avatar (asignacion, inventario, depreciacion, area, rol): agregar columna `#` con badge del ID del registro.

---

## Ajuste 5: Tablas - Botones de accion agrupados en dropdown

**Problema**: Las tablas muestran multiples botones de accion separados por registro, ocupando demasiado espacio horizontal.

### Archivos afectados
- `producto/listaproducto.php` (Editar, Cambiar estado, PDF, NFC)
- `usuario/listausuario.php` (Eliminar, Editar, Reporte Asignaciones)
- `asignacion/listaasignacion.php` (Eliminar, Editar)
- `inventario/listainventario.php` (si aplica)
- `depreciacionTabla/listaDepreciacion.php` (Editar)
- `area/listaarea.php` (Eliminar, Editar)
- `rol/listarol.php` (Eliminar, Editar)

### Tareas
- [x] Reemplazar los botones individuales por un `<div class="dropdown">` con boton trigger `btn btn-sm btn-ghost-secondary dropdown-toggle` e icono `ti ti-dots-vertical`.
- [x] Colocar las acciones previas como `<a class="dropdown-item">` dentro del menu desplegable.
- [x] Para acciones destructivas (Eliminar): agregar `text-danger` al item y separarlo con `<div class="dropdown-divider">`.
- [x] Mantener todos los callbacks JS (`onclick`, `data-bs-toggle`, etc.) intactos dentro de los items.
- [x] Respetar la logica de ocultamiento por rol (`$hide` / `hidden`) en cada item del dropdown.
- [x] Mantener la logica de solo-movil / solo-escritorio para acciones de NFC y PDF.

### Validacion
- [x] Todas las columnas "Opciones" tienen un unico dropdown de acciones.
- [x] Todas las acciones previas siguen siendo funcionales desde el dropdown.
- [x] Los permisos por rol siguen aplicandose correctamente.
- [x] Las acciones condicionales movil/escritorio funcionan correctamente.

---

## Ajuste 6: Secciones de listado - Cabecera profesional con page-header

**Problema**: Las paginas de listado tienen cabeceras muy simples, sin subtitulos descriptivos ni botones de accion bien posicionados.

### Archivos afectados
- `producto/producto.php`
- `usuario/usuario.php`
- `asignacion/asignacion.php`
- `inventario/inventario.php`
- `depreciacionTabla/depreciacionTabla.php`
- `area/area.php`
- `rol/rol.php`

### Tareas
- [x] Agregar subtitulo descriptivo en el `page-header` de cada modulo.
- [x] Mover el boton "Nuevo" / "Registrar" a `page-header-actions` (extremo derecho del header).
- [x] Integrar los filtros de busqueda en un toolbar compacto bajo el `page-header` (`card card-body py-2`).
- [x] Agregar `breadcrumb` nativo de Tabler para indicar la ubicacion del modulo.

### Validacion
- [x] Cada listado tiene un `page-header` con titulo, subtitulo y acciones alineadas.
- [x] Los filtros de busqueda estan en un toolbar compacto.
- [x] Los botones de "Nuevo" son visibles y bien diferenciados del contenido.

---

## Ajuste 7: Empty states - Componente visual mejorado

**Problema**: El estado vacio usa un HTML basico. Tabler ofrece un componente mas completo con subtitulo y accion.

### Archivos afectados
Todos los archivos `lista*.php`

### Tareas
- [x] Estandarizar el HTML del estado vacio usando el componente `.empty` completo de Tabler.
- [x] Usar icono contextual por modulo (ej. `ti ti-users` para usuarios, `ti ti-device-desktop` para productos).
- [x] Agregar subtitulo descriptivo ("No se encontraron resultados para esta busqueda.").
- [x] Agregar boton de accion en el empty state cuando corresponda.

### Validacion
- [x] Todos los estados vacios usan el componente `.empty` con icono contextual y subtitulo.
- [x] Los estados vacios son visualmente coherentes en todo el sistema.

---

## Ajuste 8: Dashboard - Colores tematicos en stat cards

**Problema**: Los stat cards del dashboard no tienen identidad de color, dificultando la lectura rapida de los indicadores KPI.

### Archivos afectados
- `dashboard/dashboard.php`

### Tareas
- [x] Aplicar colores de acento en las stat cards: azul (bienes totales), verde (asignados), amarillo (no asignados), rojo (depreciados).
- [x] Agregar fondo de acento (`bg-blue-lt`, `bg-green-lt`, etc.) en el icono de cada stat card.
- [x] Revisar que la barra de progreso `#tiempo-restante` sigue siendo visible con colores coherentes.
- [x] Verificar que los IDs JS de los stat cards se mantengan intactos.

### Validacion
- [x] Los stat cards tienen identidad de color propia y diferenciada.
- [x] Los iconos son legibles sobre sus fondos de acento.
- [x] Los datos del dashboard siguen cargando correctamente por AJAX.

---

## Ajuste 9: Formularios - Consistencia visual y UX

**Problema**: Los formularios de alta y edicion carecen de separadores visuales entre grupos de campos y ayuda contextual.

### Archivos afectados
- `producto/add.php`, `producto/edit.php`
- `usuario/add.php`, `usuario/edit.php`
- `asignacion/add.php`, `asignacion/edit.php`
- `inventario/add.php`
- `depreciacionTabla/edit.php`

### Tareas
- [x] Agregar separadores de seccion entre grupos de campos relacionados.
- [x] Agregar texto de ayuda (`<small class="form-hint">`) en campos que lo requieran (contrasena, codigo NFC, fechas).
- [x] Asegurar que el boton de guardado tenga el icono `ti ti-device-floppy` o `ti ti-check`.
- [x] En `asignacion/edit.php`: hacer mas prominente el banner de alerta "VENCIDA" con `.alert-important`.

### Validacion
- [x] Los formularios son visualmente coherentes y organizados por secciones.
- [x] Los campos de ayuda estan presentes donde se necesitan.
- [x] Los botones de guardado tienen iconos descriptivos.

---

## Ajuste 10: Modales - Coherencia visual y Bootstrap 5

**Problema**: Algunos modales de reporte y documento no siguen consistentemente el patron Tabler UI / Bootstrap 5.

### Archivos afectados
- `usuario/modal_reporte.php`
- `producto/modal_reporte.php`
- `asignacion/modal_reporte.php`
- `asignacion/modal_documento.php`
- `inventario/modal_reporte.php`

### Tareas
- [x] Verificar que todos los modales usan `modal-blur` y `modal-dialog-centered`.
- [x] Asegurar que el boton de cierre usa solo `data-bs-dismiss="modal"`.
- [x] En modales de reporte, usar el icono `ti ti-file-analytics` en el boton de generacion.
- [x] Verificar que el tamano de los modales es apropiado al contenido.

### Validacion
- [x] Todos los modales tienen `modal-blur` y `modal-dialog-centered`.
- [x] Los botones de cierre y accion siguen el patron Bootstrap 5.
- [x] Los modales no exceden el tamano apropiado para su contenido.

---

## Ajuste 11: Paginacion - Estilo coherente con Tabler UI

**Problema**: La paginacion usa `simplePagination.css` con un parche de estilos. Tabler UI tiene su propio componente de paginacion nativo.

### Archivos afectados
- `dist_pagination/simplePagination.css`
- Todos los modulos con paginacion

### Tareas
- [x] Investigar si `simplePagination.js` permite configurar las clases CSS emitidas.
- [x] Si es posible, configurar `simplePagination.js` para que emita clases de Tabler UI (`pagination`, `page-item`, `page-link`).
- [x] Si no es posible, aplicar CSS override en `simplePagination.css` para mapear las clases existentes a la apariencia de Tabler UI.
- [x] Centrar la paginacion en el footer de la card con el wrapper nativo de Tabler.

### Validacion
- [x] La paginacion tiene apariencia coherente con el resto del sistema Tabler UI.
- [x] La funcionalidad de paginacion (cambio de pagina, carga AJAX) sigue intacta.

---

## Ajuste 12: Login - Verificacion final de assets y funcionalidad

**Problema**: Las rutas de assets del login fueron corregidas pero requieren verificacion funcional completa.

### Archivos afectados
- `login/index.php`
- `login_ss/index.php`

### Tareas
- [x] Verificar que `../js_lib/plugins/tabler/css/tabler.min.css` resuelve correctamente en ambas paginas.
- [x] Verificar que `../js_lib/plugins/tabler/js/tabler.min.js` resuelve correctamente.
- [x] Confirmar que el avatar icono de marca (`avatar avatar-xl bg-primary-subtle text-primary rounded-circle`) muestra el icono `ti ti-nfc` correctamente.
- [x] En `login_ss/index.php`: verificar que el listener `.password-toggle` de `login.js` no conflictua con `onclick="mostrarContrasena()"`.
- [x] Verificar que no hay errores de consola al cargar el login.

### Validacion
- [x] El login se renderiza completamente con Tabler UI sin errores de consola.
- [x] El avatar icono de marca es visible y correctamente estilizado.
- [x] El toggle de contrasena funciona en ambas paginas de login.

---

## Ajuste 13: Footer - Meta-informacion del sistema

**Problema**: El footer del layout principal esta vacio, sin informacion del sistema.

### Archivos afectados
- `main-page/index.php`

### Tareas
- [x] Agregar al footer: nombre del sistema y anio actual con `date('Y')`.
- [x] Usar la estructura del footer de Tabler con `<div class="text-secondary">`.
- [ ] Opcionalmente agregar enlace a "Soporte" o enlace de administracion para el rol 1.

### Validacion
- [x] El footer muestra informacion util del sistema.
- [x] El footer es coherente con la identidad visual del sistema.

---

## Registro de avances

| Fecha | Ajuste | Modulo / Componente | Responsable | Estado |
|---|---|---|---|---|
| 2026-08-20 | Ajuste 1 | Sidebar color y branding | - | **Completado** |
| 2026-08-20 | Ajuste 2 | Navbar usuario y sesion | - | **Completado** |
| 2026-08-20 | Ajuste 3 | Tablas compactas (table-sm) | - | **Completado** |
| 2026-08-20 | Ajuste 4 | Eliminar columna expander | - | **Completado** |
| - | Ajuste 5 | Botones agrupados en dropdown | - | Pendiente |
| - | Ajuste 6 | Page headers profesionales | - | Pendiente |
| - | Ajuste 7 | Empty states mejorados | - | Pendiente |
| - | Ajuste 8 | Dashboard stat cards con colores | - | Pendiente |
| - | Ajuste 9 | Formularios UX y consistencia | - | Pendiente |
| - | Ajuste 10 | Modales coherencia Bootstrap 5 | - | Pendiente |
| - | Ajuste 11 | Paginacion Tabler nativa | - | Pendiente |
| - | Ajuste 12 | Login assets y coherencia | - | Pendiente |
| - | Ajuste 13 | Footer meta-informacion | - | Pendiente |

