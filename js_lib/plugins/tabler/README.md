# Tabler UI local

Recursos locales de Tabler UI para Activos NFC.

- Paquete de origen: `@tabler/core`
- Version: `1.4.0`
- Licencia declarada por el paquete: MIT
- Procedencia: paquete oficial descargado desde npm
- Integracion: archivos locales, sin CDN

Iconos:

- Paquete de origen: `@tabler/icons-webfont`
- Version: `3.46.0`
- Licencia declarada por el paquete: MIT
- Integracion: webfont y CSS locales, sin CDN

## Archivos

- `css/tabler.min.css`: estilos compilados de Tabler.
- `css/tabler.min.css.map`: mapa fuente de los estilos.
- `js/tabler.min.js`: JavaScript compilado de Tabler.
- `js/tabler.min.js.map`: mapa fuente del JavaScript.
- `icons/css/tabler-icons.min.css`: hoja local de Tabler Icons Webfont.
- `icons/fonts/tabler-icons.woff2`: fuente local de iconos.

Los recursos no deben editarse manualmente. Las personalizaciones de la aplicacion deben realizarse en las hojas CSS propias del proyecto.

## Estado de integracion

- `tabler.min.css` e iconos Tabler se cargan localmente desde `main-page/index.php`.
- El bundle de Tabler se basa en Bootstrap 5.3.7; no se descargara un Bootstrap 5 independiente.
- AdminLTE y Bootstrap 4 permanecen activos solo durante la transicion del layout.
- `tabler.min.js` todavia no se carga en el layout principal para evitar conflictos con el bundle actual de Bootstrap 4 y los eventos existentes.
- Font Awesome sera retirado durante la migracion de los archivos PHP de presentacion.
