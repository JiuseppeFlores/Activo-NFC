<?php
session_start();
$idRol = $_SESSION['idRol'];

$agente = $_SERVER['HTTP_USER_AGENT'];
$esDispositivoMovil = preg_match('/android|blackberry|iemobile|opera mini/i', $agente);

?>
<div class="page-header d-print-none mb-2 pb-3">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">Panel principal</div>
            <h2 class="page-title page-title-lg">Resumen de gestión</h2>
            <div class="page-subtitle">Consulta el estado general de los activos y sus indicadores.</div>
        </div>
    </div>
</div>
<div class="row g-3">
    <?php if ($idRol != 3) { ?>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-blue-lt text-blue avatar">
                            <i class="ti ti-box"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium h2 mb-0" id="total_bienes">0</div>
                        <div class="text-secondary">Total de Activos</div>
                    </div>
                </div>
            </div>
            <?php if ($idRol != 3) { ?>
            <div class="card-footer px-3 py-2 bg-body-tertiary">
                <a href="#producto" onclick="producto(1)" class="d-flex align-items-center justify-content-between text-reset text-decoration-none">
                    <small>Ver detalles</small>
                    <i class="ti ti-chevron-right fs-4"></i>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-green-lt text-green avatar">
                            <i class="ti ti-circle-check"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium h2 mb-0" id="bienes_asignados">0</div>
                        <div class="text-secondary">Activos asignados</div>
                    </div>
                </div>
            </div>
            <?php if ($idRol != 3) { ?>
            <div class="card-footer px-3 py-2 bg-body-tertiary">
                <a href="#asignacion" onclick="asignacion(1)" class="d-flex align-items-center justify-content-between text-reset text-decoration-none">
                    <small>Ver detalles</small>
                    <i class="ti ti-chevron-right fs-4"></i>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>

    <?php if ($idRol != 3) { ?>
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-yellow-lt text-yellow avatar">
                            <i class="ti ti-tools"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium h2 mb-0" id="bienes_no_asignados">0</div>
                        <div class="text-secondary">Activos no asignados</div>
                    </div>
                </div>
            </div>
            <?php if ($idRol != 3) { ?>
            <div class="card-footer px-3 py-2 bg-body-tertiary">
                <a href="#asignacion" onclick="asignacion(1)" class="d-flex align-items-center justify-content-between text-reset text-decoration-none">
                    <small>Ver detalles</small>
                    <i class="ti ti-chevron-right fs-4"></i>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-red-lt text-red avatar">
                            <i class="ti ti-alert-triangle"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium h2 mb-0" id="bienes_depreciados">0</div>
                        <div class="text-secondary">Activos depreciados</div>
                    </div>
                </div>
            </div>
            <?php if ($idRol != 3) { ?>
            <div class="card-footer px-3 py-2 bg-body-tertiary">
                <a href="#bienes_depreciados" onclick="reportes(1)" class="d-flex align-items-center justify-content-between text-reset text-decoration-none">
                    <small>Ver detalles</small>
                    <i class="ti ti-chevron-right fs-4"></i>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="row g-3 mt-0">
    <?php if (!$esDispositivoMovil) { ?>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribución por Área</h3>
                </div>
                <div class="card-body">
                    <div id="grafico-area" style="height: 300px; width: 100%; position: relative;">
                        <canvas id="graficoAreaAsignaciones"></canvas>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Porcentaje de depreciación</h3>
            </div>
            <div class="card-body" id="tiempo-restante" style="max-height: 400px; overflow-y: auto;">
            </div>
        </div>
    </div>
</div>
