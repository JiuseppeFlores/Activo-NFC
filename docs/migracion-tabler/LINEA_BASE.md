# Linea base de la migracion a Tabler

Fecha de registro: 2026-08-20

## Entorno local

- Dominio oficial: `http://activos.nfc.local`
- Login principal: `http://activos.nfc.local/login/index.php`
- Login alternativo: `http://activos.nfc.local/login_ss/index.php`
- El acceso a `main-page/index.php` sin sesion redirige correctamente al login.

## Punto de restauracion

- Commit base: `aa446b63292ea70a7fd3c2bb1e4750ac3a03cf94`
- Commit corto: `aa446b6`
- Mensaje: `Eliminación de rastro de seguimiento del directorio .vscode`
- Estado al registrar la linea base: el plan y los recursos locales de Tabler aparecen como archivos no versionados; no se detectaron modificaciones a archivos existentes.

## Version y recursos de Tabler

- Paquete de origen: `@tabler/core`
- Version: `1.4.0`
- Licencia declarada: MIT
- Ubicacion local: `js_lib/plugins/tabler/`
- CSS: `js_lib/plugins/tabler/css/tabler.min.css`
- JavaScript: `js_lib/plugins/tabler/js/tabler.min.js`
- No se utilizara CDN para Tabler.

## Dependencias visuales actuales

### Recursos locales

- Bootstrap 4: `css/bootstrap.min.css` y `js_lib/plugins/bootstrap/`
- AdminLTE: `js_lib/dist/css/adminlte.min.css` y `js_lib/dist/js/adminlte.js`
- jQuery: `js_lib/plugins/jquery/`
- Font Awesome: `fontawesome/` y `js_lib/plugins/fontawesome-free/`
- Select2: `css/select2.css` y `js/select2.js`
- SweetAlert2: `css/sweetalert2.min.css` y `js/sweetalert2.min.js`
- Paginacion: `dist_pagination/`
- Chart.js: `js_lib/plugins/chart.js/`
- PDF.js: `js/pdf.min.mjs` y recursos de visor
- OverlayScrollbars, Summernote, Moment, jQuery UI y Tempus Dominus: `js_lib/plugins/`

### Dependencias externas detectadas

Estas dependencias no se modifican en esta fase y deberan evaluarse por separado si se busca una aplicacion completamente offline:

- Google Fonts / Source Sans Pro.
- Ionicons CDN.
- AlertifyJS CDN.
- UIKit CDN.
- Leaflet CDN.
- PDF.js CDN.

## Contrato de identificadores utilizado por JavaScript

Estos identificadores deben conservarse durante la migracion visual:

### Layout y navegacion

- `all-body`
- `shadow`
- `spinner`
- `carpeta-activa`
- `pagina-activa`
- `pagina`
- `nav_rol`
- `nav_area`
- `nav_usuario`
- `nav_producto`
- `nav_asignacion`
- `nav_inventario`
- `nav_depreciacion`
- `nav_reportes`

### Resultados y filtros por modulo

- `buscador-general`
- `for-pagination1`
- `for-pagination2`
- `rol-result`
- `busqueda_rol`
- `area-result`
- `busqueda_area`
- `usuario-result`
- `busqueda_usuario`
- `producto-result`
- `busqueda_producto`
- `asignacion-result`
- `busqueda_asignacion`
- `area_filter`
- `inventario-result`
- `busqueda_inventario`
- `gestion_filter`
- `depreciacion-result`
- `busqueda_depreciacion`

### Dashboard y reportes

- `total_bienes`
- `bienes_asignados`
- `bienes_no_asignados`
- `bienes_depreciados`
- `grafico-area`
- `graficoAreaAsignaciones`
- `tiempo-restante`
- `pdf-canvas`
- `page-num`
- `zoom-level`

## Capturas de referencia realizadas

- Login principal en escritorio.
- Login principal en movil, viewport de 390 x 844.
- Login alternativo `login_ss`.
- Layout principal en escritorio con sidebar abierto.
- Layout principal en movil con sidebar contraido.
- Dashboard en escritorio.
- Dashboard en movil, viewport de 390 x 844.
- Modulo de roles.
- Modulo de areas.
- Modulo de usuarios.
- Modulo de activos/productos.
- Modulo de activos/productos en movil.
- Modulo de asignaciones.
- Modulo de inspecciones.
- Modulo de depreciacion.
- Modulo de reportes.

Las capturas fueron realizadas desde el dominio local oficial durante la ejecucion de la Fase 0.

## Observaciones visuales iniciales

- El layout usa un sidebar azul con submenus expandibles y una barra superior compacta.
- El dashboard presenta cuatro indicadores, un grafico por area y una lista de depreciacion.
- Los modulos CRUD comparten buscador, card, tabla, paginacion y botones de accion.
- Asignaciones utiliza filas resaltadas para estados vencidos y badges para asignado/devuelto.
- En movil, la tabla de activos requiere desplazamiento vertical para consultar columnas adicionales.
- Reportes inicia con filtros y visor PDF vacio, sin documento seleccionado.
- La configuracion de perfil no esta expuesta como opcion visible en el menu principal.

## Pruebas interactivas realizadas

### Modal de eliminacion de area

- Modal probado: `modal_eliminar_area`.
- Encabezado, mensaje, botones Aceptar/Cancelar y boton de cierre visibles.
- Backdrop visible mientras el modal esta abierto.
- `body` recibe correctamente la clase `modal-open`.
- El cierre mediante tecla `Escape` funciona y elimina el backdrop.
- El boton `Cancelar` se visualiza, pero el navegador reporto que el contenido del mensaje intercepta sus eventos; queda pendiente revisar el cierre mediante clic.
- No se confirmo la eliminacion ni se modificaron datos.

### Alertas

- Alerta de exito probada mediante Alertify.
- Mensaje observado: `Guardado`.
- Clases observadas: `ajs-message ajs-success ajs-visible`.
- El notifier se ubica en la zona inferior derecha.
- El mensaje es temporal y puede desaparecer antes de capturar la pantalla completa.
- Pendiente probar mensajes de error, confirmacion y carga.

## Capturas pendientes

Las pantallas autenticadas requieren una sesion valida. No se utilizaron credenciales ni se intento evadir el control de acceso.

Pendientes cuando Activo-NFC tenga una URL local accesible:

- Login en escritorio y movil.
- Layout con sidebar abierto y cerrado.
- Dashboard.
- Roles, areas y usuarios.
- Productos, asignaciones, inventario y depreciacion.
- Reportes y configuracion.
- Modales, alertas, spinners y listas vacias.
- Configuracion de perfil desde una vista navegable.

## Hallazgos de linea base

- `login/index.php` responde correctamente con HTTP 200.
- `main-page/index.php` redirige al login cuando no existe sesion.
- `login_ss/index.php` solicita `login_ss/styles.css`, pero ese recurso responde HTTP 404.
- La pantalla principal del login no presento errores funcionales visibles durante la captura.
- Las dependencias externas existentes no fueron modificadas durante esta fase.

## Criterio de cierre de esta linea base

La linea base queda aceptada para iniciar la migracion. Las pruebas de eventos efimeros y las vistas no expuestas por navegacion se mantienen como limitaciones documentadas y podran revisarse durante la migracion de cada componente.
