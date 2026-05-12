<?php
session_start();
$idRol = $_SESSION['idRol'];

$agente = $_SERVER['HTTP_USER_AGENT'];
$esDispositivoMovil = preg_match('/android|blackberry|iemobile|opera mini/i', $agente);

?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <h1 class="m-0">Inicio</h1>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php if ($idRol != 3) { ?>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="total_bienes">0</h3>
                        <p>Total de Activos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <?php if ($idRol != 3) { ?>
                    <a href="#producto" onclick="producto(1)" class="small-box-footer">Ver detalles <i class="fas fa-arrow-circle-right"></i></a>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="bienes_asignados">0</h3>
                        <p>Activos asignados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <?php if ($idRol != 3) { ?>
                    <a href="#asignacion" onclick="asignacion(1)" class="small-box-footer">Ver detalles <i class="fas fa-arrow-circle-right"></i></a>
                    <?php } ?>
                </div>
            </div>
            <?php if ($idRol != 3) { ?>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="bienes_no_asignados">0</h3>
                        <p>Activos no asignados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <?php if ($idRol != 3) { ?>
                    <a href="#asignacion" onclick="asignacion(1)" class="small-box-footer">Ver detalles <i class="fas fa-arrow-circle-right"></i></a>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="bienes_depreciados">0</h3>
                        <p>Activos depreciados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <?php if ($idRol != 3) { ?>
                    <a href="#bienes_depreciados" onclick="reportes(1)" class="small-box-footer">Ver detalles <i class="fas fa-arrow-circle-right"></i></a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="row mt-4">
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
                        <!-- <div class="info-box mb-3 bg-success">
                            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Valor Total</span>
                                <span class="info-box-number" id="valor_total">$0.00</span>
                            </div>
                        </div> -->
                        <!-- <div class="progress-group">
                            <span class="progress-text">Valor por Área</span>
                            <span class="float-right"><b>0%</b></span>
                            <div class="progress sm">
                                <div class="progress-bar bg-success" style="width: 0%"></div>
                            </div>
                        </div> -->
                        <!-- <div class="progress-group">
                            <span class="progress-text">Tiempo restante de vida útil</span>
                            <span class="float-right"><b>0%</b></span>
                            <div class="progress sm">
                                <div class="progress-bar bg-success" style="width: 0%"></div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
