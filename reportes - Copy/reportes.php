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
                                        <select class="form-control" id="tipo_reporte" name="tipo_reporte">
                                            <option value="">Seleccione un tipo</option>
                                            <option value="area">Área</option>
                                            <option value="asignacion">Asignación</option>
                                            <option value="inventario">Inventario</option>
                                            <option value="producto">Producto</option>
                                            <option value="usuario">Usuario</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha Inicial</label>
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha Final</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Estado</label>
                                        <select class="form-control" id="estado" name="estado">
                                            <option value="">Todos</option>
                                            <option value="activo">Activo</option>
                                            <option value="inactivo">Inactivo</option>
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
                <!-- <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Resultados del Reporte</h3>
                    </div>
                    <div class="card-body">
                        <div id="reporte-resultados">
                            <button id="btn-descargar" class="btn btn-primary mb-3" style="display: none;">
                                Descargar PDF
                            </button>
                            <div class="mb-3">
                                <button id="prev-page" class="btn btn-secondary btn-sm">Anterior</button>
                                <button id="next-page" class="btn btn-secondary btn-sm">Siguiente</button>
                                <span class="ml-2">Página: <span id="page-num">1</span> / <span id="page-count">?</span></span>
                            </div>
                            <canvas id="pdf-canvas" style="border:1px solid #ccc;"></canvas>
                        </div>
                    </div>
                </div> -->
                <div class="card">
    <div class="card-header">
        <h3 class="card-title">Resultados del Reporte</h3>
    </div>
    <div class="card-body">
        <div id="reporte-resultados">
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
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</section>