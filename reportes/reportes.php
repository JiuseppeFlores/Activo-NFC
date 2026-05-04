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
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <input type="hidden" class="form-control" id="pagina" value="1">
                <h1 class="m-0" style="display:inline-block">Reportes</h1>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Filtros de Reporte</h3>
                    </div>
                    <div class="card-body">
                        <form id="form-filtros">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tipo de Reporte</label>
                                        <select class="form-control" id="tipo_reporte" name="tipo_reporte" onchange="cambiarFiltros()">
                                            <option value="">Seleccione un tipo</option>
                                            <!-- <option value="area">Área</option> -->
                                            <option value="actaEntrega">Acta de Entrega</option>
                                            <option value="actaDevolucion">Acta de Devolución</option>
                                            <option value="asignacion">Asignación</option>
                                            <option value="depreciacion">Depreciación</option>
                                            <option value="producto">Bien</option>
                                            <option value="inventario">Inventario</option>
                                            <option value="usuario">Usuario</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="divFechaInicio">
                                    <div class="form-group">
                                        <label>Fecha Inicial</label>
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                                    </div>
                                </div>
                                <div class="col-md-3" id="divFechaFinal">
                                    <div class="form-group">
                                        <label>Fecha Final</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                    </div>
                                </div>
                                <div class="col-md-3" id="divFecha">
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fechaActual; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3" id="divEstado">
                                    <div class="form-group">
                                        <label>Estado</label>
                                        <select class="form-control" id="estado" name="estado">
                                            <option value="">Todos</option>
                                            <option value="activo">Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="divArea">
                                    <div class="form-group">
                                        <label>Área</label>
                                        <select class="form-control" id="area" name="area">
                                            <option value="">Todos</option>
                                            <?php
                                            foreach ($listaAreas as $key => $value) {
                                                echo '<option value="' . $value['idArea'] . '">' . $value['area'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="divTipoBien">
                                    <div class="form-group">
                                        <label>Tipo de Bien</label>
                                        <select class="form-control" id="tipoProducto" name="tipo_bien" onchange="getBien()">
                                            <option value="">Todos</option>
                                            <?php
                                            foreach ($listaTipoBien as $key => $value) {
                                                echo '<option value="' . $value['idDepreciacion'] . '">' . $value['bien'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="divBien">
                                    <div class="form-group">
                                        <label>Bien</label>
                                        <select class="form-control" id="bien" name="bien">
                                            <option value="">Todos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="divUsuario">
                                    <div class="form-group">
                                        <label>Usuario</label>
                                        <select class="form-control" id="usuario" name="usuario">
                                            <?php
                                            foreach ($listaUsuario as $key => $value) {
                                                echo '<option value="' . $value['idUsuario'] . '">' . $value['apellidoPaterno'] . ' ' . $value['apellidoMaterno'] . ' ' . $value['nombre'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="divAnios">
                                    <div class="form-group">
                                        <label>Años</label>
                                        <select class="form-control" id="anios" name="anios">
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
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" onclick="generarReporte()">Generar Reporte</button>
                                    <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar Filtros</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Resultados del Reporte</h3>
                    </div>
                    <div class="card-body">
                        <!-- <div id="reporte-resultados">
                            <button id="btn-descargar" class="btn btn-primary mb-3" style="display: none;">
                                <i class="fas fa-download"></i> Descargar PDF
                            </button>
                            <div class="mb-3">
                                <div class="btn-group" role="group">
                                    <button id="prev-page" class="btn btn-secondary btn-sm" title="Anterior página">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                    <button id="next-page" class="btn btn-secondary btn-sm" title="Siguiente página">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                    <button id="zoom-in" class="btn btn-secondary btn-sm" title="Zoom +">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button id="zoom-out" class="btn btn-secondary btn-sm" title="Zoom -">
                                        <i class="fas fa-search-minus"></i>
                                    </button>
                                    <button id="rotate" class="btn btn-secondary btn-sm" title="Rotar">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                                <span class="ml-2">Página: <span id="page-num">1</span> / <span id="page-count">?</span></span>
                                <span class="ml-2">Zoom: <span id="zoom-level">100%</span></span>
                            </div>
                            <div class="pdf-container">
                                <canvas id="pdf-canvas" style="border:1px solid #ccc;"></canvas>
                                <div id="loading" class="text-center mt-3" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Cargando...</span>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div id="reporte-resultados">
                            <div class="d-flex align-items-center mb-3">
                                <button id="btn-descargar" class="btn btn-primary mr-2" style="display: none;">
                                    <i class="fas fa-download"></i> Descargar PDF
                                </button>
                                <div class="btn-group">
                                    <button id="prev-page" class="btn btn-secondary btn-sm" title="Anterior página">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                    <button id="next-page" class="btn btn-secondary btn-sm" title="Siguiente página">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                    <button id="zoom-in" class="btn btn-secondary btn-sm" title="Zoom +">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button id="zoom-out" class="btn btn-secondary btn-sm" title="Zoom -">
                                        <i class="fas fa-search-minus"></i>
                                    </button>
                                    <button id="rotate" class="btn btn-secondary btn-sm" title="Rotar">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </div>
                                <span class="ml-2">Página: <span id="page-num">1</span> / <span id="page-count">?</span></span>
                                <span class="ml-2">Zoom: <span id="zoom-level">100%</span></span>
                            </div>
                            <div class="pdf-container">
                                <div id="loading" class="text-center mt-3" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Cargando...</span>
                                    </div>
                                </div>
                                <canvas id="pdf-canvas" style="border:1px solid #ccc;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        cambiarFiltros();
    });
</script>