<?php
session_start();
$idRol = $_SESSION['idRol'];
?>
<input type="hidden" class="form-control" id="pagina" value="1">
<div class="page-header d-print-none mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Usuario</h2>
        </div>
        <?php if ($idRol == 1) { ?>
        <div class="col-auto ms-auto d-print-none">
            <button class="btn btn-primary" onclick="add_usuario()">
                <i class="ti ti-plus icon me-1"></i> Añadir usuario
            </button>
        </div>
        <?php } ?>
    </div>
</div>

<div class="card">
    <div class="card-header" id="buscador-general">
        <?php if ($idRol != 3) { ?>
        <div class="input-icon">
            <input class="form-control" id="busqueda_usuario" onkeyup="listar_usuario(1)" type="search" placeholder="Buscar..." aria-label="Search">
            <span class="input-icon-addon">
                <i class="ti ti-search icon"></i>
            </span>
        </div>
        <?php } ?>
        <div class="card-actions ms-auto">
            <button type="button" class="btn-action" data-card-widget="collapse">
                <i class="ti ti-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div id="for-pagination1" class="text-center py-2"></div>
        <div id="usuario-result"></div>
        <div id="for-pagination2" class="text-center py-2"></div>
    </div>
</div>