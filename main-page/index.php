<?php
include("../conexion.php");
if (isset($_SESSION['idUsuario']) && intval($_SESSION['idUsuario']) > 0) {
} else {
    header('Location: ../login/index.php');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../js_lib/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../js_lib/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../js_lib/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Tabler local: base visual de Tabler UI. -->
    <link rel="stylesheet" href="../js_lib/plugins/tabler/css/tabler.min.css">
    <link rel="stylesheet" href="../js_lib/plugins/tabler/icons/css/tabler-icons.min.css">
    <link rel="stylesheet" href="../js_lib/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../js_lib/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../js_lib/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/css/uikit.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.17/js/uikit-icons.min.js"></script>
    <link rel="stylesheet" href="../css/reports-template.css">
    <link rel="stylesheet" href="../dist_pagination/simplePagination.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" />

    <link rel="stylesheet" href="../css/select2.css" />
    <link rel="stylesheet" href="../css/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf_viewer.css">

    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
    <script src="../js_lib/plugins/jquery/jquery.min.js"></script>
    <script src="../js_lib/plugins/tabler/js/tabler.min.js"></script>
</head>

<body>
    <input type="hidden" id="carpeta-activa">
    <input type="hidden" id="pagina-activa">
    <div class="page">

        <!-- Preloader -->
        <div class="preloader position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-body z-3">
            <div class="text-center">
                <img src="../js_lib/dist/img/engranaje.png" class="avatar avatar-xl mb-3" alt="Cargando">
                <div class="text-secondary">Cargando...</div>
            </div>
        </div>

        <?php
        include("../nav/nav.php");
        ?>

        <!-- INICIO TODO Content Wrapper. Contains page content -->
        <div class="page-wrapper">
            <div class="page-body" id="all-body">
            </div>
        </div>
        <!-- FINAL -->
        <div id="spinner">
        </div>

        <!-- /.content-wrapper -->
        <footer class="footer footer-transparent d-print-none">
            <div class="container-fluid">
                <div class="text-center"></div>
            </div>
        </footer>
    </div>



    <?php
    include("../area/modal_eliminar.php");
    include("../rol/modal_eliminar.php");
    include("../usuario/modal_eliminar.php");
    include("../producto/modal_eliminar.php");
    include("../asignacion/modal_eliminar.php");
    include("../usuario/modal_reporte.php");
    include("../producto/modal_reporte.php");
    include("../asignacion/modal_reporte.php");
    include("../asignacion/modal_documento.php");
    include("../inventario/modal_reporte.php");
    ?>

    <div id="shadow" class="position-fixed top-0 start-0 w-100 h-100 bg-white bg-opacity-75 z-3"></div>

    <script src="../js_lib/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="../js_lib/plugins/jquery-knob/jquery.knob.min.js"></script>
    <script src="../js_lib/plugins/chart.js/Chart.min.js"></script>
    <script src="../js_lib/plugins/sparklines/sparkline.js"></script>
    <script src="../js_lib/plugins/moment/moment.min.js"></script>
    <script src="../js_lib/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="../js_lib/plugins/summernote/summernote-bs4.min.js"></script>
    <script src="../js_lib/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="../js_lib/plugins/tabler/tabler-compat.js"></script>
    <script src="../js_lib/dist/js/app_text.js"></script>
    <script src="js/app.js"></script>
    <script src="js/abm.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src="../dist_pagination/jquery.simplePagination.js"></script>

    <script src="../js/select2.js"></script>
    <script src="../js/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <script src="../area/js/app.js"></script>
    <script src="../rol/js/app.js"></script>
    <script src="../usuario/js/app.js"></script>
    <script src="../producto/js/app.js"></script>
    <script src="../asignacion/js/app.js"></script>
    <script src="../inventario/js/app.js"></script>
    <script src="../depreciacionTabla/js/app.js"></script>
    <script src="../reportes/js/app.js"></script>
    <script src="../dashboard/js/app.js"></script>

</body>

</html>