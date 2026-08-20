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
// Avatar del usuario logueado
$url_avatar_nav = "../Images/usuario/" . $id_personita . ".png";
if (!file_exists($url_avatar_nav)) {
  $url_avatar_nav = "../images/empty.jpg";
}
$url_avatar_nav .= "?r=" . $t;
?>
<header class="navbar navbar-expand-md navbar-dark bg-primary d-print-none">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="true" aria-label="Abrir menu">
      <i class="ti ti-menu-2"></i>
    </button>
    <div class="d-flex align-items-center me-auto">
      <i class="ti ti-nfc text-white me-2 fs-4"></i>
      <h1 class="navbar-brand navbar-brand-autodark d-none d-md-block mb-0 fs-5 fw-bold text-white">Sistema de Gestión de Activos</h1>
    </div>
    <!-- Dropdown de usuario en el navbar -->
    <div class="navbar-nav ms-auto">
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex align-items-center lh-1 text-reset p-0 px-2" data-bs-toggle="dropdown" aria-expanded="false" id="navbarUserDropdown">
          <span class="avatar avatar-sm rounded-circle me-2" style="background-image: url(<?php echo $url_avatar_nav; ?>)"></span>
          <div class="d-none d-md-block">
            <div class="fw-medium text-white small"><?php echo $_SESSION['nombre']; ?></div>
            <div class="text-white-50" style="font-size: 0.7rem;">
              <?php
                if ($idRol == 1) echo 'Administrador';
                elseif ($idRol == 2) echo 'Responsable';
                else echo 'Usuario';
              ?>
            </div>
          </div>
          <i class="ti ti-chevron-down ms-1 text-white-50 small"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarUserDropdown">
          <div class="dropdown-header">
            <div class="d-flex align-items-center">
              <span class="avatar avatar-sm rounded-circle me-2" style="background-image: url(<?php echo $url_avatar_nav; ?>)"></span>
              <div>
                <div class="fw-bold small"><?php echo $_SESSION['nombre']; ?></div>
                <div class="text-muted" style="font-size: 0.7rem;">
                  <?php
                    if ($idRol == 1) echo 'Administrador';
                    elseif ($idRol == 2) echo 'Responsable';
                    else echo 'Usuario';
                  ?>
                </div>
              </div>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" onclick="configUser(1)">
            <i class="ti ti-user-circle me-2 text-muted"></i> Mi Perfil
          </a>
          <?php if ($idRol == 1) { ?>
          <a class="dropdown-item" href="#" onclick="configWeb(1)">
            <i class="ti ti-settings me-2 text-muted"></i> Configuración
          </a>
          <?php } ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item text-danger" href="../login/logout.php">
            <i class="ti ti-logout me-2"></i> Cerrar Sesión
          </a>
        </div>
      </div>
    </div>
  </div>
</header>

<aside class="navbar navbar-dark navbar-vertical navbar-expand-lg collapse d-lg-block" id="sidebar-menu" data-bs-theme="dark">
  <div class="container-fluid">
    <div class="d-flex align-items-center d-lg-none py-2">
      <i class="ti ti-nfc text-white me-2 fs-4"></i>
      <h1 class="navbar-brand navbar-brand-autodark mb-0 fs-5 fw-bold text-white">T.S.J.M.</h1>
      <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-label="Cerrar menu">
        <i class="ti ti-x"></i>
      </button>
    </div>

    <div class="navbar-collapse overflow-auto" id="sidebar-navigation">
      <div class="navbar-nav pt-lg-3">

        <div class="nav-item pb-2 border-bottom border-secondary border-opacity-25 mb-2">
          <div class="nav-link px-2 py-2 text-white">
            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-building-bank"></i></span>
            <span class="nav-link-title fw-bold">T.S.J.M.</span>
          </div>
        </div>

        <!-- Separador de sección: Administración -->
        <?php if ($idRol == 1) { ?>
          <div class="nav-item">
            <div class="subheader px-2">Administración</div>
          </div>
          <div class="nav-item">
            <a href="#rol" onclick="rol(1)" id="nav_rol" class="nav-link">
              <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-user-cog"></i></span><span class="nav-link-title">Roles</span>
            </a>
          </div>
        <?php } ?>

        <!-- Separador de sección: Personal -->
        <div class="nav-item mt-2">
          <div class="subheader px-2">Personal</div>
        </div>
        <?php if ($idRol == 1) { ?>
          <div class="nav-item">
            <a href="#area" onclick="area(1)" id="nav_area" class="nav-link">
              <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-building"></i></span><span class="nav-link-title">Áreas</span>
            </a>
          </div>
        <?php } ?>
        <div class="nav-item">
          <a href="#usuario" onclick="usuario(1)" id="nav_usuario" class="nav-link">
            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-user-shield"></i></span><span class="nav-link-title">Usuarios</span>
          </a>
        </div>

        <!-- Separador de sección: Activos -->
        <div class="nav-item mt-2">
          <div class="subheader px-2">Activos</div>
        </div>
        <?php if ($idRol != 3) { ?>
          <div class="nav-item">
            <a href="#producto" onclick="producto(1)" id="nav_producto" class="nav-link">
              <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-device-desktop"></i></span><span class="nav-link-title">Registro</span>
            </a>
          </div>
        <?php } ?>
        <div class="nav-item">
          <a href="#asignacion" onclick="asignacion(1)" id="nav_asignacion" class="nav-link">
            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-clipboard-list"></i></span><span class="nav-link-title">Asignaciones</span>
          </a>
        </div>

        <!-- Separador de sección: Gestión -->
        <?php if ($idRol != 3) { ?>
          <div class="nav-item mt-2">
            <div class="subheader px-2">Gestión</div>
          </div>
          <div class="nav-item">
            <a href="#reportes" onclick="reportes(1)" id="nav_reportes" class="nav-link">
              <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-chart-bar"></i></span><span class="nav-link-title">Reportes</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="#inventario" onclick="inventario(1)" id="nav_inventario" class="nav-link">
              <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-list-check"></i></span><span class="nav-link-title">Inspecciones</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="#depreciacion" onclick="depreciacion(1)" id="nav_depreciacion" class="nav-link">
              <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-chart-line"></i></span><span class="nav-link-title">Depreciación</span>
            </a>
          </div>
        <?php } ?>

      </div>
    </div>
  </div>
</aside>
