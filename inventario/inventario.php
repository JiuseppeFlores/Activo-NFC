<?php
session_start();
date_default_timezone_set('America/La_Paz');
$listaGestion = array();
$gestionActual = date('Y');
$numeroGestiones = 5;

for ($i = 0; $i < $numeroGestiones; $i++) {
    $listaGestion[] = $gestionActual - $i;
}

$idRol = $_SESSION['idRol'];
?>
<input type="hidden" class="form-control" id="pagina" value="1">
<div class="page-header d-print-none mb-2 pb-3">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">Control operativo</div>
            <h2 class="page-title page-title-lg">Inspecciones de activos</h2>
            <div class="page-subtitle">Registra y consulta las inspecciones físicas de los activos.</div>
            <ol class="breadcrumb breadcrumb-arrows mt-2 mb-0">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inspecciones</li>
            </ol>
        </div>
        <?php if ($idRol == 1) { ?>
        <div class="col-auto ms-auto page-header-actions d-print-none">
            <button class="btn btn-primary" onclick="add_inventario()">
                <i class="ti ti-plus icon me-1"></i> Nueva Inspección
            </button>
        </div>
        <?php } ?>
    </div>
</div>

<div class="card card-body py-2 mb-2" id="buscador-general">
    <div class="d-flex align-items-center w-100">
        <div class="row g-2 align-items-center w-100 me-2">
            <div class="col-md-5 col-lg-4">
                <div class="input-icon">
                    <input class="form-control" id="busqueda_inventario" onkeyup="listar_inventario(1)" type="search" placeholder="Buscar..." aria-label="Search">
                    <span class="input-icon-addon">
                        <i class="ti ti-search icon"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-5 col-lg-4">
                <div class="d-flex align-items-center">
                    <label class="form-label mb-0 me-2 text-nowrap">Gestión:</label>
                    <select id="gestion_filter" class="form-select" onchange="listar_inventario(1)">
                        <option value="">Todas las gestiones</option>
                        <?php foreach ($listaGestion as $gestion) { ?>
                            <option value="<?php echo $gestion; ?>"><?php echo $gestion; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" id="inventario-table">
    <div class="card-header">
        <div class="card-title">Historial de inspecciones</div>
    </div>
    <div class="card-body p-0">
        <div id="inventario-result"></div>
    </div>
</div>
<div id="inventario-form" class="d-none"></div>