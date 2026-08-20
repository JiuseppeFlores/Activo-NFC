# Compatibilidad de Tabler durante la migracion

## Estado actual

- Tabler UI: `@tabler/core` `1.4.0`.
- Bootstrap actual de la aplicacion: Bootstrap 4, en proceso de sustitucion.
- Bootstrap de la distribucion Tabler: Bootstrap 5.3.7.
- AdminLTE actual: AdminLTE 3 JavaScript temporal; su CSS ya fue retirado del layout.
- Tabler CSS local: activo en `main-page/index.php`.
- Tabler JavaScript: activo desde `main-page/index.php`.
- Bootstrap 4 JavaScript: retirado del layout principal.

## Decisiones transitorias

1. No cargar simultaneamente el JavaScript de Tabler y el bundle JavaScript de Bootstrap 4.
2. No retirar AdminLTE CSS/JavaScript del layout hasta migrar el layout y validar cada componente.
3. Mantener los identificadores HTML usados por JavaScript.
4. Usar Tabler Icons local y retirar Font Awesome durante la migracion del markup.
5. No crear tokens ni estilos visuales propios; usar la configuracion predeterminada de Tabler.
6. No editar los bundles distribuidos de Tabler.

## Riesgos a revisar

- Colisiones de estilos entre Bootstrap 4, AdminLTE y Tabler.
- Diferencias entre atributos `data-toggle` actuales y atributos `data-bs-*` de componentes Bootstrap modernos.
- Diferencias de espaciado, tipografia y variables visuales.
- Cambios de z-index en modales, dropdowns y alertas.
- Componentes que dependan de eventos JavaScript de AdminLTE.

## Regla para activar Tabler JavaScript

Tabler JavaScript esta activo; `tabler-compat.js` mantiene temporalmente la compatibilidad de atributos heredados y llamadas jQuery de modal mientras se migran los plugins restantes.

## Verificacion inicial

- [x] El CSS local responde con HTTP 200.
- [x] El panel continua cargando tras incorporar Tabler CSS.
- [x] No se requiere descargar un Bootstrap 5 independiente: Tabler declara e integra Bootstrap 5.3.7 en su distribucion.
- [ ] Validar todos los componentes visuales con Tabler CSS.
- [ ] Migrar componentes a la API JavaScript de Tabler cuando corresponda.
- [ ] Retirar dependencias equivalentes de AdminLTE al final de la migracion.
