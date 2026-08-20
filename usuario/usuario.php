<?php
session_start();
$idRol = $_SESSION['idRol'];
?>
<input type="hidden" class="form-control" id="pagina" value="1">
<div class="page-header d-print-none mb-2 pb-3">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">Administración del sistema</div>
            <h2 class="page-title page-title-lg">Usuarios</h2>
            <div class="page-subtitle">Gestiona las cuentas, roles y áreas de los usuarios.</div>
            <ol class="breadcrumb breadcrumb-arrows mt-2 mb-0">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
            </ol>
        </div>
        <?php if ($idRol == 1) { ?>
        <div class="col-auto ms-auto page-header-actions d-print-none">
            <button class="btn btn-primary" onclick="add_usuario()">
                <i class="ti ti-plus icon me-1"></i> Añadir usuario
            </button>
        </div>
        <?php } ?>
    </div>
</div>

<div class="card card-body py-2 mb-2" id="buscador-general">
    <div class="d-flex align-items-center">
        <?php if ($idRol != 3) { ?>
        <div class="input-icon">
            <input class="form-control" id="busqueda_usuario" onkeyup="listar_usuario(1)" type="search" placeholder="Buscar..." aria-label="Search">
            <span class="input-icon-addon">
                <i class="ti ti-search icon"></i>
            </span>
        </div>
        <?php } ?>
    </div>
</div>

<div class="card" id="usuario-table">
    <div class="card-header">
        <div class="card-title">Listado de usuarios</div>
    </div>
    <div class="card-body p-0">
        <div id="usuario-result"></div>
    </div>
</div>
<div id="usuario-form" class="d-none"></div>