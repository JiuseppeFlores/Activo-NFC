<?php
include("../verify/verify.php");
$idRol = $_SESSION['idRol'];
$text_movil = "";
if ($device) {
  $text_movil = 'data-widget="pushmenu"';
}
$t = time();
$id_personita = $_SESSION['idUsuario'];
if (file_exists("../sistem_images/logo.png")) {
  $url_imagen = "../sistem_images/logo.png?r=" . $t;
} else {
  $url_imagen = "../images/empty.jpg";
}
?>
<header class="navbar navbar-expand-md d-print-none">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="true" aria-label="Abrir menu">
      <i class="ti ti-menu-2"></i>
    </button>
    <h1 class="navbar-brand navbar-brand-autodark d-none d-md-block mb-0">Activos NFC</h1>
  </div>
</header>

<aside class="navbar navbar-vertical navbar-expand-lg collapse d-lg-block" id="sidebar-menu">
  <div class="container-fluid">
    <h1 class="navbar-brand navbar-brand-autodark d-md-none">Activos NFC</h1>
    <div class="navbar-nav flex-row d-md-none">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebar-menu" aria-label="Cerrar menu">
        <i class="ti ti-x"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse show" id="sidebar-navigation">
      <div class="navbar-nav pt-lg-3">
        <div class="nav-item py-3 text-center">
          <img src="../images/empty.jpg" class="avatar avatar-lg" alt="Imagen de usuario">
          <div class="mt-2"><a href="#" class="nav-link justify-content-center"><?php echo $_SESSION['nombre']; ?></a></div>
        </div>
        <?php if ($idRol == 1) { ?>
          <div class="nav-item">
            <a href="#rol" onclick="rol(1)" id="nav_rol" class="nav-link">
              <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-user-cog"></i></span><span class="nav-link-title">Rol</span>
            </a>
          </div>
        <?php } ?>
        <div class="nav-item dropdown">
          <a href="#navbar-personal" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#navbar-personal" aria-expanded="false">
            <span class="nav-link-icon"><i class="ti ti-users"></i></span><span class="nav-link-title">Personal</span>
          </a>
          <div class="collapse" id="navbar-personal">
            <div class="nav nav-pills flex-column">
            <?php if ($idRol == 1) { ?>
              <div class="nav-item">
                <a href="#area" onclick="area(1)" id="nav_area" class="nav-link">
                  <span class="nav-link-icon"><i class="ti ti-building"></i></span><span class="nav-link-title">Area</span>
                </a>
              </div>
            <?php } ?>
            <div class="nav-item">
              <a href="#usuario" onclick="usuario(1)" id="nav_usuario" class="nav-link">
                <span class="nav-link-icon"><i class="ti ti-user-shield"></i></span><span class="nav-link-title">Usuario</span>
              </a>
            </div>
            </div>
          </div>
        </div>
        <div class="nav-item dropdown">
          <a href="#navbar-assets" class="nav-link dropdown-toggle" data-toggle="collapse" data-target="#navbar-assets" aria-expanded="false">
            <span class="nav-link-icon"><i class="ti ti-device-desktop"></i></span><span class="nav-link-title">Activos</span>
          </a>
          <div class="collapse" id="navbar-assets">
            <div class="nav nav-pills flex-column">
            <?php if ($idRol != 3) { ?>
              <div class="nav-item">
                <a href="#producto" onclick="producto(1)" id="nav_producto" class="nav-link">
                  <span class="nav-link-icon"><i class="ti ti-book"></i></span><span class="nav-link-title">Registro</span>
                </a>
              </div>
            <?php } ?>
            <div class="nav-item">
              <a href="#asignacion" onclick="asignacion(1)" id="nav_asignacion" class="nav-link">
                <span class="nav-link-icon"><i class="ti ti-clipboard-list"></i></span><span class="nav-link-title">Asignación</span>
              </a>
            </div>
            </div>
          </div>
        </div>
        <?php if ($idRol != 3) { ?>
          <div class="nav-item">
            <a href="#reportes" onclick="reportes(1)" id="nav_reportes" class="nav-link">
              <span class="nav-link-icon"><i class="ti ti-chart-bar"></i></span><span class="nav-link-title">Reportes</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="#inventario" onclick="inventario(1)" id="nav_inventario" class="nav-link">
              <span class="nav-link-icon"><i class="ti ti-list-check"></i></span><span class="nav-link-title">Inspecciones</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="#depreciacion" onclick="depreciacion(1)" id="nav_depreciacion" class="nav-link">
              <span class="nav-link-icon"><i class="ti ti-chart-line"></i></span><span class="nav-link-title">Depreciación</span>
            </a>
          </div>
        <?php } ?>
        <div class="nav-item mt-auto">
          <a href="../login/logout.php" class="nav-link">
            <span class="nav-link-icon"><i class="ti ti-logout"></i></span><span class="nav-link-title">Cerrar sesión</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</aside>