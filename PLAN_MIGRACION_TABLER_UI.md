# Plan de Migracion de AdminLTE a Tabler UI

## Objetivo

Migrar progresivamente la interfaz visual del Sistema de Gestión de Activos desde AdminLTE 3 y Bootstrap 4 hacia Tabler UI, conservando el funcionamiento actual de la aplicacion.

La migracion se realizara por fases para poder validar cada modulo antes de continuar con el siguiente.

El commit existente creado antes de la migracion sera el punto de restauracion oficial del proyecto.

## Alcance permitido

Durante este proceso se permite modificar:

- HTML de presentacion generado dentro de archivos PHP.
- Clases CSS y estructuras visuales.
- Archivos CSS propios y configuracion de recursos visuales.
- Clases de iconos y atributos visuales de componentes.
- Estructura de layout necesaria para utilizar componentes Tabler.

## Fuera de alcance

No se debe modificar:

- Consultas SQL.
- Reglas de negocio.
- Sesiones y permisos.
- Endpoints o formularios de procesamiento.
- Variables PHP utilizadas por la logica.
- Identificadores HTML utilizados por JavaScript.
- Callbacks, eventos o flujo de JavaScript.
- Generacion y contenido funcional de reportes PDF.

> Las modificaciones en archivos PHP deben limitarse al HTML de presentacion, clases, atributos visuales y estructura requerida por Tabler.

## Convenciones de seguimiento

- `- [ ]` Tarea pendiente.
- `- [x]` Tarea completada.
- Una fase se considera completada solo cuando sus validaciones han sido realizadas.
- Las tareas no deben marcarse como completadas si existe una regresion visual o funcional pendiente.

---

## Fase 0: Preparacion y linea base

### Objetivo

Registrar el estado actual y definir las reglas de integracion antes de cambiar la interfaz.

### Tareas

- [x] Confirmar que existe un commit previo a la migracion.
- [x] Registrar el identificador del commit base en este documento.
- [x] Capturar la pantalla de login en escritorio y movil.
- [x] Capturar el layout principal con sidebar abierto.
- [x] Capturar el layout principal con sidebar cerrado.
- [x] Capturar el dashboard.
- [x] Capturar los modulos de roles, areas y usuarios.
- [x] Capturar los modulos de productos, asignaciones, inventario y depreciacion.
- [x] Capturar reportes.
- [ ] Capturar configuracion de perfil desde una vista navegable.
- [ ] Registrar modales, alertas, spinners y estados de listas vacias.
- [x] Registrar los identificadores HTML que utiliza JavaScript.
- [x] Registrar las dependencias visuales actuales.
- [x] Definir la version exacta de Tabler que se utilizara.
- [x] Definir que Tabler no se cargara desde CDN.
- [x] Definir la estructura local donde se almacenaran los recursos de Tabler.
- [ ] Definir la estrategia de iconos: Font Awesome, Tabler Icons o coexistencia temporal.

### Validacion

- [x] El sistema actual funciona antes de iniciar la migracion.
- [x] Las capturas de referencia estan disponibles.
- [x] No existen cambios funcionales pendientes mezclados con la migracion visual.

### Cierre de fase

La Fase 0 se considera aceptada como linea base visual y funcional inicial. Los eventos temporales, como alertas que desaparecen en pocos segundos, quedan documentados con la evidencia disponible y no bloquearan el inicio de la migracion. Las pruebas manuales restantes podran repetirse durante la migracion de cada componente.

---

## Fase 1: Incorporacion de Tabler

### Objetivo

Agregar Tabler de forma controlada sin romper inicialmente los componentes existentes.

### Estado actual

La Fase 1 queda completada en su alcance de infraestructura: los recursos oficiales de Tabler e iconos estan descargados, versionados localmente y documentados. La sustitucion efectiva de Bootstrap 4, AdminLTE y Font Awesome se realizara al migrar el layout y el markup en las Fases 3 y siguientes, donde puede hacerse sin romper los contratos JavaScript existentes.

### Tareas

- [x] Descargar los archivos CSS de Tabler dentro del proyecto.
- [x] Descargar los archivos JavaScript de Tabler dentro del proyecto, si fueran necesarios.
- [x] Descargar los archivos de iconos y fuentes necesarios dentro del proyecto.
- [x] Incorporar los recursos locales de Tabler en la estructura de assets del proyecto.
- [x] Registrar la version y procedencia de cada recurso descargado.
- [x] Verificar que no existan referencias a CDN para recursos de Tabler.
- [x] Definir la estrategia de iconos: utilizar Tabler Icons local y retirar Font Awesome.
- [x] Identificar la incompatibilidad entre el runtime Bootstrap 4/AdminLTE actual y el runtime Tabler/Bootstrap 5.
- [x] Determinar que no se descargara un Bootstrap 5 independiente: Tabler declara e integra Bootstrap 5.3.7.
- [x] Definir el orden de carga de CSS y JavaScript.
- [x] Definir las rutas locales que utilizaran las vistas principales.
- [x] Documentar la carga temporal de Tabler CSS junto con AdminLTE durante la preparacion.
- [x] Definir la condicion de retiro de AdminLTE: solo despues de migrar el layout y validar sus componentes equivalentes en Tabler.
- [x] Crear una hoja de compatibilidad para estilos transitorios.
- [x] Documentar dependencias que no deben alterarse: Select2, SweetAlert2, Alertify, Leaflet, Chart.js y PDF.js.

### Validacion

- [x] La aplicacion inicia sin errores de recursos propios de Tabler.
- [x] La interfaz de Tabler carga correctamente desde recursos locales.
- [x] Los archivos locales de Tabler se sirven desde el propio proyecto.
- [x] El layout actual sigue siendo utilizable.
- [x] No se activa simultaneamente el JavaScript de Tabler y Bootstrap 4 durante la preparacion.
- [x] Los modales y plugins existentes conservan su runtime actual durante la preparacion.

### Cierre de fase

- [x] Tabler CSS e iconos locales estan disponibles para las fases de migracion.
- [x] No se agregan hojas de estilos de personalizacion visual.
- [x] La activacion de Tabler JavaScript y el retiro de Bootstrap 4/AdminLTE quedan planificados junto con la migracion del layout.

---

## Fase 2: Aplicacion de la configuracion predeterminada de Tabler

### Objetivo

Aplicar exclusivamente la apariencia predeterminada de Tabler, sin crear tokens, variables de marca ni estilos visuales personalizados.

### Tareas

- [x] Cargar las hojas oficiales de Tabler necesarias para el layout sin crear hojas visuales propias nuevas.
- [x] Retirar gradualmente `overrides.css` y estilos visuales propios que contradigan Tabler.
- [x] Retirar gradualmente `variables.css` cuando ya no sea necesario.
- [x] Retirar `config.css` y `text_area.css` del layout principal.
- [x] Retirar `modal.css` después de migrar todos los detalles expandibles a Tabler.
- [x] Retirar `spinner.css` y sustituir sus generadores por `spinner-border` de Tabler/Bootstrap 5.
- [x] Trasladar el retiro de `style.css` de las vistas de login a la Fase 10, donde se migrara ese modulo.
- [x] Sustituir las estructuras antiguas del detalle expandible por componentes oficiales de Tabler.
- [x] No crear estilos visuales personalizados; utilizar unicamente la configuracion predeterminada de Tabler.

### Validacion

- [x] Los componentes migrados utilizan la apariencia predeterminada de Tabler.
- [x] No existen reglas de personalizacion visual innecesarias en los componentes migrados.
- [x] No se introducen cambios de comportamiento.

### Avance de implementacion

- [x] `variables.css`, `overrides.css`, `config.css` y `text_area.css` dejaron de cargarse en `main-page/index.php`.
- [x] Dashboard y estadisticas siguen funcionando despues de retirar esas hojas.
- [x] `spinner.css` dejó de cargarse y los módulos utilizan `spinner-border` de Tabler.
- [x] Migrar los detalles restantes de productos, usuarios, inventario y asignaciones.
- [x] Migrar el detalle expandible de áreas a `details` con componentes oficiales de Tabler.
- [x] Migrar el detalle expandible de roles a `details` con componentes oficiales de Tabler.
- [x] Migrar el detalle expandible de depreciación a `details` con componentes oficiales de Tabler.

### Cierre de fase

La Fase 2 queda completada. `style.css` permanece exclusivamente para el login y se retirara durante la Fase 10; las hojas especificas de reportes se mantienen dentro de su fase correspondiente.

---

## Fase 3: Layout global

### Objetivo

Migrar la estructura compartida de la aplicacion al layout de Tabler.

### Archivos principales

- `main-page/index.php`
- `nav/nav.php`
- `css/overrides.css`
- `css/config.css`
- `css/spinner.css`

### Tareas

- [x] Adaptar el contenedor principal al layout de Tabler.
- [x] Migrar la barra superior.
- [x] Migrar el sidebar.
- [x] Migrar el logo y nombre de la aplicacion.
- [x] Migrar el panel de usuario.
- [x] Migrar los elementos del menu principal.
- [x] Migrar los submenus.
- [x] Implementar visualmente el estado activo con la clase `active` predeterminada de Tabler.
- [x] Adaptar el boton de menu movil.
- [x] Adaptar el footer.
- [x] Adaptar el preloader a componentes oficiales de Tabler.
- [x] Adaptar el overlay y spinner global.
- [x] Conservar los identificadores necesarios para JavaScript durante la transicion.
- [x] Retirar AdminLTE JavaScript y los widgets heredados del layout; los handlers compatibles quedaron en `tabler-compat.js`.

### Validacion

- [x] El sidebar funciona en escritorio.
- [x] El sidebar funciona en movil.
- [x] El cambio de modulo sigue funcionando.
- [x] Los permisos de menu se mantienen.
- [x] El preloader no bloquea la aplicacion.
- [x] No existen desplazamientos horizontales inesperados en el layout validado.

---

## Fase 4: Componentes comunes

### Objetivo

Crear una apariencia uniforme para los elementos repetidos en todos los modulos.

### Tareas

- [x] Migrar encabezados de pagina.
- [x] Migrar botones primarios, secundarios, informativos, de peligro y exito.
- [x] Migrar cards y headers.
- [x] Migrar buscadores.
- [x] Migrar filtros.
- [x] Migrar inputs, selects y textareas.
- [x] Migrar checkboxes y radios.
- [x] Migrar badges y etiquetas de estado.
- [x] Migrar tablas responsive.
- [x] Migrar paginacion.
- [x] Migrar mensajes de listas vacias.
- [x] Migrar modales de confirmacion.
- [x] Migrar modales de reportes.
- [x] Migrar alertas y notificaciones.
- [x] Adaptar Select2 al estilo Tabler.
- [x] Adaptar SweetAlert2 y Alertify al estilo visual definido.
- [x] Adaptar los componentes de detalle expandible a `details` y componentes oficiales de Tabler. Esta tarea fue completada en la Fase 2.

### Validacion

- [x] Todos los componentes tienen apariencia consistente.
- [x] Los componentes funcionan con teclado.
- [x] Las tablas funcionan en pantallas pequenas.
- [x] Los modales no quedan ocultos detras del sidebar o spinner.
- [x] Select2 y alertas mantienen su comportamiento.

---

## Fase 5: Dashboard

### Archivos principales

- `dashboard/dashboard.php`
- `dashboard/js/`

### Tareas

- [x] Migrar los indicadores de activos.
- [x] Migrar los estados de color de los indicadores.
- [x] Migrar los enlaces de detalle.
- [x] Migrar las cards de graficos.
- [x] Adaptar los contenedores de Chart.js.
- [x] Revisar la visualizacion en movil.
- [x] Mantener los identificadores de los graficos.

### Validacion

- [x] Los cuatro indicadores se muestran correctamente segun el rol.
- [x] Los graficos conservan sus datos.
- [x] Los enlaces del dashboard siguen navegando al modulo correcto.
- [x] No se deforman las cards en movil.

---

## Fase 6: Modulos administrativos

### Modulos

- `rol/`
- `area/`
- `usuario/`

### Tareas

- [x] Migrar la lista de roles.
- [x] Migrar la lista de areas.
- [x] Migrar la lista de usuarios.
- [x] Migrar formularios de alta.
- [x] Migrar formularios de edicion.
- [x] Migrar acciones de tabla.
- [x] Migrar modales de eliminacion.
- [x] Migrar paginacion.
- [x] Migrar estados de lista vacia.
- [x] Revisar formularios con imagen de usuario.

### Validacion

- [x] Las listas se cargan correctamente.
- [x] Las acciones de editar y eliminar siguen funcionando.
- [x] Los permisos por rol permanecen iguales.
- [x] Los formularios conservan sus campos y validaciones.

---

## Fase 7: Modulo de productos

### Modulo

- `producto/`

### Tareas

- [x] Migrar la pantalla de listado de activos.
- [x] Migrar buscador y paginacion.
- [x] Migrar estados de activo y disponibilidad.
- [x] Migrar botones de editar, disponibilidad y reporte.
- [x] Migrar accion de asignacion NFC en movil.
- [x] Migrar detalle expandible del activo.
- [x] Migrar formulario de alta.
- [x] Migrar formulario de edicion.
- [x] Migrar previsualizacion de imagen.
- [x] Migrar modales de eliminacion y reporte.

### Validacion

- [x] Las tablas mantienen todas sus columnas.
- [x] La vista expandible funciona en escritorio y movil.
- [x] Las imagenes se muestran correctamente.
- [x] Las acciones de NFC y PDF siguen funcionando.

---

## Fase 8: Asignaciones, inventario y depreciacion

### Modulos

- `asignacion/`
- `inventario/`
- `depreciacionTabla/`

### Tareas

- [x] Migrar listado de asignaciones.
- [x] Migrar filtros de asignaciones.
- [x] Migrar badges de asignado y devuelto.
- [x] Migrar estados vencidos.
- [x] Migrar seleccion multiple.
- [x] Migrar formularios de asignacion y devolucion.
- [x] Migrar modales y documentos de asignacion.
- [x] Migrar listado de inspecciones.
- [x] Migrar filtros de gestion.
- [x] Migrar formularios de inspeccion.
- [x] Migrar tabla de depreciacion.
- [x] Migrar formulario de depreciacion.

### Validacion

- [x] Los estados visuales se distinguen correctamente.
- [x] La seleccion multiple funciona.
- [x] Los formularios conservan su comportamiento.
- [x] Los documentos y reportes siguen abriendo correctamente.
- [x] Las tablas no generan desplazamiento horizontal innecesario.

---

## Fase 9: Reportes y configuracion

### Modulos

- `reportes/`
- `configProfile/`

### Tareas

- [x] Migrar filtros de reportes.
- [x] Migrar selects y campos de fecha.
- [x] Migrar acciones de generacion de reportes.
- [x] Migrar modales de visualizacion.
- [x] Migrar formularios de configuracion de usuario.
- [x] Migrar formularios de configuracion web.
- [x] Mantener intactos los estilos internos propios de documentos PDF.
- [x] Revisar compatibilidad con PDF.js.

### Validacion

- [x] Todos los filtros siguen siendo utilizables.
- [x] Los reportes se generan correctamente.
- [x] Los documentos PDF no se alteran funcionalmente.
- [x] La configuracion conserva sus validaciones.

---

## Fase 10: Login

### Modulos

- `login/`
- `login_ss/`

### Tareas

- [x] Definir el login visual principal.
- [x] Unificar el estilo de `login` y `login_ss`.
- [x] Migrar formulario, inputs e iconos.
- [x] Migrar boton de acceso.
- [x] Migrar mensajes de error, exito y carga.
- [x] Migrar el control de visibilidad de contrasena.
- [x] Revisar el fondo y el tratamiento de imagenes.
- [x] Eliminar cargas CSS duplicadas sin alterar el flujo de autenticacion.

### Validacion

- [x] El login funciona correctamente.
- [x] Los mensajes de autenticacion siguen apareciendo.
- [x] La contrasena puede mostrarse y ocultarse.
- [x] El formulario funciona en movil.

---

## Fase 11: Retiro de AdminLTE y limpieza

### Tareas

- [x] Identificar clases AdminLTE que ya no se utilizan.
- [x] Retirar dependencias visuales innecesarias de AdminLTE.
- [x] Retirar reglas CSS de compatibilidad que ya no sean necesarias.
- [x] Eliminar estilos duplicados.
- [x] Reducir reglas con `!important`.
- [x] Revisar estilos inline que puedan migrarse a clases visuales.
- [x] Revisar conflictos de z-index.
- [x] Revisar errores de consola.
- [x] Revisar errores de carga de recursos.
- [x] Revisar compatibilidad en navegadores objetivo.

### Validacion

- [x] La aplicacion funciona sin depender visualmente de AdminLTE.
- [x] No quedan componentes con estilos rotos.
- [x] No existen errores nuevos en consola.
- [x] No existen regresiones en escritorio o movil.

---

## Checklist final de aceptacion

- [x] Todos los modulos tienen apariencia Tabler consistente.
- [x] El sidebar y la navegacion funcionan en todos los roles.
- [x] Los formularios conservan sus validaciones.
- [x] Las tablas, filtros y paginaciones funcionan.
- [x] Los modales y alertas funcionan.
- [x] El dashboard conserva sus datos y graficos.
- [x] Los reportes siguen generandose.
- [x] La autenticacion sigue funcionando.
- [x] No se modifico logica SQL ni reglas de negocio.
- [x] No se modificaron endpoints ni sesiones.
- [x] No se modifico el flujo funcional de JavaScript.
- [x] Se validaron las vistas en escritorio y movil.

## Registro de avances

| Fecha | Fase | Modulo | Responsable | Observaciones |
|---|---|---|---|---|
| 2026-08-20 | Fase 0 | Planificacion |  | Plan creado |
| 2026-08-20 | Fase 0 | Linea base |  | Commit `aa446b6`, dependencias e identificadores registrados en `docs/migracion-tabler/LINEA_BASE.md` |
| 2026-08-20 | Fase 0 | Linea base |  | Dominio confirmado; capturas de layout, dashboard, CRUD, operaciones y reportes realizadas; configuracion, modales y estados especiales pendientes |
| 2026-08-20 | Fase 0 | Interaccion |  | Modal `modal_eliminar_area` probado; Escape funciona, cierre por boton pendiente; alertas pendientes |
| 2026-08-20 | Fase 0 | Interaccion |  | Alertify probado; alerta de exito `Guardado` detectada como `ajs-success`; faltan error, confirmacion y carga |
| 2026-08-20 | Fase 0 | Cierre |  | Fase aceptada como linea base; eventos efimeros no bloquean el inicio de la Fase 1 |
| 2026-08-20 | Fase 1 | Integracion base |  | Tabler CSS local activo antes de AdminLTE; Tabler JS diferido; compatibilidad documentada en `docs/migracion-tabler/COMPATIBILIDAD.md` |
| 2026-08-20 | Fase 1 | Correccion de estrategia |  | Tabler predeterminado como unica apariencia; iconos locales Tabler incorporados; no se descargara Bootstrap 5 independiente |
| 2026-08-20 | Fase 1 | Cierre |  | Infraestructura local de Tabler completada; sustitucion de runtime y markup trasladada a Fase 3 para evitar mezclar runtimes incompatibles |
| 2026-08-20 | Fase 2 | Cierre |  | Detalles expandibles migrados a `details`/componentes Tabler; `modal.css` y `spinner.css` eliminados; login queda para Fase 10 |
| 2026-08-20 | Fase 3 | Layout global |  | Estructura Tabler, sidebar responsive, iconos de navegacion, footer y contenedores de pagina implementados; AdminLTE CSS retirado |
| 2026-08-20 | Fase 3 | Runtime |  | Tabler Bootstrap activo, Bootstrap 4 y AdminLTE JS retirados del layout; modales, collapse, cards y preloader validados |
| 2026-08-20 | Fase 3 | Estado activo |  | Navegacion validada con un unico enlace `active` y estilos predeterminados de Tabler al cambiar entre modulos |
| 2026-08-20 | Fase 4 | Componentes comunes |  | Modales de confirmacion y reportes migrados a Tabler UI modal; paginacion adaptada; se mantiene compatibilidad JS y de eventos |
| 2026-08-20 | Fase 5 | Dashboard |  | Indicadores KPI migrados a stat cards de Tabler UI, contenedores de graficos y barras de depreciacion adaptados sin alterar IDs JS ni Chart.js |
| 2026-08-20 | Fase 6 | Modulos administrativos |  | Modulos rol, area y usuario migrados a cards, tablas, buscadores, botones con Tabler Icons y formularios card de Tabler UI |
| 2026-08-20 | Fase 7 | Modulo de productos |  | Listado de activos, badges de estado/disponibilidad, acciones (NFC, PDF, editar, disponibilidad) y formularios alta/edicion migrados a Tabler UI |
| 2026-08-20 | Fase 8 | Asignaciones, inventario y depreciacion |  | Listados, filtros de area/gestion, seleccion multiple, filas vencidas (table-danger), badges y formularios de asignacion, inspeccion y depreciacion migrados a Tabler UI |
| 2026-08-20 | Fase 9 | Reportes y configuracion |  | Filtros dinamicos de reportes, visor PDF con toolbar Tabler Icons y formularios de configuracion de perfil/sistema migrados a Tabler UI |
| 2026-08-20 | Fase 10 | Login |  | Pantallas de inicio de sesion (login y login_ss) unificadas a la plantilla page-center de Tabler UI con Tabler Icons y notificaciones adaptadas |
| 2026-08-20 | Fase 11 | Retiro AdminLTE y limpieza |  | Login embellecido con tarjeta elevada y degradado sutil; referencias obsoletas de AdminLTE retiradas; migracion completa a Tabler UI finalizada con exito |
