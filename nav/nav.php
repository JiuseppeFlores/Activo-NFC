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
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light sidebar-dark-primary">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>

  </ul>
</nav>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="" class="brand-link">
    <!-- <img src="<?php echo $url_imagen; ?>" alt="" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
    <span class="brand-text font-weight-light">Activos NFC</span>
  </a>

  <div class="sidebar">
    <div class="user-panel d-flex flex-column align-items-center justify-content-center mb-3">
      <div class="image">
        <img src="../images/empty.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo $_SESSION['nombre']; ?></a>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php if ($idRol == 1) { ?>
          <li class="nav-item">
            <a href="#rol" onclick="rol(1)" <?php echo $text_movil ?> id="nav_rol" class="nav-link">
              <i class="nav-icon fas fa-user-tag"></i>
              <p>
                Rol
              </p>
            </a>
          </li>
        <?php } ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Personal
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview bg-secondary rounded">
            <?php if ($idRol == 1) { ?>
              <li class="nav-item">
                <a href="#area" onclick="area(1)" <?php echo $text_movil ?> id="nav_area" class="nav-link">
                  <i class="nav-icon fas fa-building"></i>
                  <p>
                    Area
                  </p>
                </a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a href="#usuario" onclick="usuario(1)" <?php echo $text_movil ?> id="nav_usuario" class="nav-link">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                  Usuario
                </p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-desktop"></i>
            <p>
              Bien
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview bg-secondary rounded">
            <?php if ($idRol != 3) { ?>
              <li class="nav-item">
                <a href="#producto" onclick="producto(1)" <?php echo $text_movil ?> id="nav_producto" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>
                    Registro
                  </p>
                </a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a href="#asignacion" onclick="asignacion(1)" <?php echo $text_movil ?> id="nav_asignacion" class="nav-link">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                  Asignación
                </p>
              </a>
            </li>
          </ul>
        </li>
        <?php if ($idRol != 3) { ?>
          <li class="nav-item">
            <a href="#reportes" onclick="reportes(1)" <?php echo $text_movil ?> id="nav_reportes" class="nav-link">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                Reportes
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#inventario" onclick="inventario(1)" <?php echo $text_movil ?> id="nav_inventario" class="nav-link">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                Inventario
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#depreciacion" onclick="depreciacion(1)" <?php echo $text_movil ?> id="nav_depreciacion" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Depreciación
              </p>
            </a>
          </li>
        <?php } ?>
        <!-- <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-edit"></i>
            <p>
              Depreciación
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview bg-secondary rounded">
          </ul>
        </li> -->

        <li class="nav-item">
          <a href="../login/logout.php" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Cerrar sesión
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>