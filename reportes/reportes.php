<?php
include("../conexion.php");
date_default_timezone_set('America/La_Paz');
$fechaActual = date("Y-m-d");
$sql = "SELECT idArea, area FROM tblArea ORDER BY area ASC";
$query = sqlsrv_query($con, $sql);
$listaAreas = array();
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    $listaAreas[] = $row;
}
$sql = "SELECT idDepreciacion, bien FROM tblDepreciacion WHERE estado = 1 ORDER BY bien ASC";
$query = sqlsrv_query($con, $sql);
$listaTipoBien = array();
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    $listaTipoBien[] = $row;
}
$sql = "SELECT idUsuario, nombre, apellidoPaterno, apellidoMaterno FROM tblUsuario ORDER BY apellidoPaterno ASC;";
$query = sqlsrv_query($con, $sql);
$listaUsuario = array();
while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
    $listaUsuario[] = $row;
}
?>
<input type="hidden" class="form-control" id="pagina" value="1">
<div class="page-header d-print-none mb-2 pb-3">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle">Gestión y análisis</div>
            <h2 class="page-title page-title-lg">Reportes de activos</h2>
            <div class="page-subtitle">Genera informes operativos y consulta sus documentos.</div>
            <ol class="breadcrumb breadcrumb-arrows mt-2 mb-0">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reportes</li>
            </ol>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Filtros de Reporte</h3>
            </div>
            <div class="card-body">
                <form id="form-filtros">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Reporte</label>
                            <select class="form-select" id="tipo_reporte" name="tipo_reporte" onchange="cambiarFiltros()">
                                <option value="">Seleccione un tipo</option>
                                <option value="actaEntrega">Acta de Entrega</option>
                                <option value="actaDevolucion">Acta de Devolución</option>
                                <option value="asignacion">Asignación</option>
                                <option value="depreciacion">Depreciación</option>
                                <option value="producto">Activos</option>
                                <option value="inventario">Inspecciones</option>
                                <option value="usuario">Usuario</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="divFechaInicio">
                            <label class="form-label">Fecha Inicial</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                        </div>
                        <div class="col-md-3" id="divFechaFinal">
                            <label class="form-label">Fecha Final</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                        </div>
                        <div class="col-md-3" id="divFecha">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fechaActual; ?>">
                        </div>
                        <div class="col-md-3" id="divEstado">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="">Todos</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="divArea">
                            <label class="form-label">Área</label>
                            <select class="form-select" id="area" name="area">
                                <option value="">Todos</option>
                                <?php
                                foreach ($listaAreas as $key => $value) {
                                    echo '<option value="' . $value['idArea'] . '">' . $value['area'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3" id="divTipoBien">
                            <label class="form-label">Tipo de Activo</label>
                            <select class="form-select" id="tipoProducto" name="tipo_bien" onchange="getBien()">
                                <option value="">Todos</option>
                                <?php
                                foreach ($listaTipoBien as $key => $value) {
                                    echo '<option value="' . $value['idDepreciacion'] . '">' . $value['bien'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3" id="divBien">
                            <label class="form-label">Activo</label>
                            <select class="form-select" id="bien" name="bien">
                                <option value="">Todos</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="divDisponibilidad">
                            <label class="form-label">Disponibilidad</label>
                            <select class="form-select" id="disponibilidad" name="disponibilidad">
                                <option value="">Todos</option>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="divUsuario">
                            <label class="form-label">Usuario</label>
                            <select class="form-select" id="usuario" name="usuario">
                                <?php
                                foreach ($listaUsuario as $key => $value) {
                                    echo '<option value="' . $value['idUsuario'] . '">' . $value['apellidoPaterno'] . ' ' . $value['apellidoMaterno'] . ' ' . $value['nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3" id="divAnios">
                            <label class="form-label">Años</label>
                            <select class="form-select" id="anios" name="anios">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-secondary me-2" onclick="limpiarFiltros()">Limpiar Filtros</button>
                <button type="button" class="btn btn-primary" onclick="generarReporte()">Generar Reporte</button>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Resultados del Reporte</h3>
            </div>
            <div class="card-body">
                <div id="reporte-resultados">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <button id="btn-descargar" class="btn btn-primary me-2" style="display: none;">
                            <i class="ti ti-download icon me-1"></i> Descargar PDF
                        </button>
                        <div class="btn-group">
                            <button id="prev-page" class="btn btn-outline-secondary btn-icon" title="Anterior página">
                                <i class="ti ti-chevron-left icon"></i>
                            </button>
                            <button id="next-page" class="btn btn-outline-secondary btn-icon" title="Siguiente página">
                                <i class="ti ti-chevron-right icon"></i>
                            </button>
                            <button id="zoom-in" class="btn btn-outline-secondary btn-icon" title="Zoom +">
                                <i class="ti ti-zoom-in icon"></i>
                            </button>
                            <button id="zoom-out" class="btn btn-outline-secondary btn-icon" title="Zoom -">
                                <i class="ti ti-zoom-out icon"></i>
                            </button>
                            <button id="rotate" class="btn btn-outline-secondary btn-icon" title="Rotar">
                                <i class="ti ti-rotate-clockwise icon"></i>
                            </button>
                        </div>
                        <span class="ms-2">Página: <span id="page-num">1</span> / <span id="page-count">?</span></span>
                        <span class="ms-2">Zoom: <span id="zoom-level">100%</span></span>
                    </div>
                    <div class="pdf-container text-center">
                        <div id="loading" class="text-center my-3" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                        <canvas id="pdf-canvas" class="border rounded max-w-full"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        cambiarFiltros();
    });
</script>